<?php

namespace Tests\Feature;

use App\Models\Asisten;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class InspectionWorkflowTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('asisten', function (Blueprint $table): void {
            $table->string('username', 50)->primary();
            $table->string('nama_asisten', 100);
            $table->string('password', 255);
        });

        Schema::create('alat_inventaris', function (Blueprint $table): void {
            $table->string('id_alat', 50)->primary();
            $table->string('nama_alat', 100);
            $table->string('lokasi_meja', 50);
        });

        Schema::create('log_inspeksi', function (Blueprint $table): void {
            $table->increments('id_log');
            $table->string('id_alat', 50);
            $table->string('username', 50)->nullable();
            $table->dateTime('waktu_inspeksi')->nullable()->useCurrent();
            $table->string('status', 20);
            $table->text('catatan')->nullable();
            $table->foreign('id_alat')->references('id_alat')->on('alat_inventaris');
        });
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Masuk petugas');

        $this->get(route('dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_existing_assistant_can_log_in_without_changing_legacy_password(): void
    {
        DB::table('asisten')->insert([
            'username' => 'petugas1',
            'nama_asisten' => 'Petugas Laboratorium',
            'password' => 'kata-sandi-lama',
        ]);

        $this->post(route('login.store'), [
            'username' => 'petugas1',
            'password' => 'kata-sandi-lama',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs(Asisten::query()->find('petugas1'));
        $this->assertDatabaseHas('asisten', [
            'username' => 'petugas1',
            'password' => 'kata-sandi-lama',
        ]);
    }

    public function test_dashboard_summarizes_daily_inspection_and_unchecked_assets(): void
    {
        $this->addAssistant();
        $this->addAsset('ALAT-01', 'Multimeter');
        $this->addAsset('ALAT-02', 'Osiloskop');
        $this->addAsset('ALAT-03', 'Catu daya');

        DB::table('log_inspeksi')->insert([
            [
                'id_alat' => 'ALAT-01',
                'username' => 'petugas1',
                'waktu_inspeksi' => now(),
                'status' => 'Lengkap',
                'catatan' => null,
            ],
            [
                'id_alat' => 'ALAT-02',
                'username' => 'petugas1',
                'waktu_inspeksi' => now(),
                'status' => 'Rusak',
                'catatan' => 'Kabel longgar',
            ],
        ]);

        $this->actingAs(Asisten::query()->find('petugas1'))
            ->get(route('dashboard', ['tanggal' => now()->toDateString()]))
            ->assertOk()
            ->assertSee('67%')
            ->assertSee('Multimeter')
            ->assertSee('Kabel longgar')
            ->assertSee('ALAT-03')
            ->assertSee('Perlu perhatian');
    }

    public function test_inspection_is_saved_with_authenticated_assistant_and_status(): void
    {
        $this->addAssistant();
        $this->addAsset('QR-005', 'Tang potong');

        $assistant = Asisten::query()->find('petugas1');

        $this->actingAs($assistant)
            ->post(route('inspeksi.create'), ['id_alat' => 'QR-005'])
            ->assertOk()
            ->assertViewIs('inspeksi.create');

        $this->post(route('inspeksi.store'), [
            'id_alat' => 'QR-005',
            'status' => 'Hilang',
            'catatan' => 'Tidak ditemukan di meja',
        ])->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('log_inspeksi', [
            'id_alat' => 'QR-005',
            'username' => 'petugas1',
            'status' => 'Hilang',
            'catatan' => 'Tidak ditemukan di meja',
        ]);

        $this->assertNotNull(
            DB::table('log_inspeksi')->where('id_alat', 'QR-005')->value('waktu_inspeksi'),
        );
    }

    public function test_unknown_qr_returns_to_scanner_with_clear_error(): void
    {
        $this->addAssistant();

        $this->actingAs(Asisten::query()->find('petugas1'))
            ->post(route('inspeksi.create'), ['id_alat' => 'TIDAK-ADA'])
            ->assertRedirect(route('inspeksi.scan'))
            ->assertSessionHasErrors('id_alat');
    }

    public function test_assistant_can_add_inventory_without_duplicate_ids(): void
    {
        $this->addAssistant();

        $this->actingAs(Asisten::query()->find('petugas1'))
            ->post(route('inventaris.store'), [
                'id_alat' => 'QR-006',
                'nama_alat' => 'Tang potong',
                'lokasi_meja' => 'Meja praktikum 2',
            ])
            ->assertRedirect(route('inventaris.index'));

        $this->assertDatabaseHas('alat_inventaris', [
            'id_alat' => 'QR-006',
            'nama_alat' => 'Tang potong',
            'lokasi_meja' => 'Meja praktikum 2',
        ]);

        $this->from(route('inventaris.create'))
            ->post(route('inventaris.store'), [
                'id_alat' => 'QR-006',
                'nama_alat' => 'Alat duplikat',
                'lokasi_meja' => 'Meja praktikum 1',
            ])
            ->assertSessionHasErrors('id_alat');
    }

    public function test_assistant_can_open_printable_qr_labels(): void
    {
        $this->addAssistant();
        $this->addAsset('QR-005', 'Tang potong');

        $this->actingAs(Asisten::query()->find('petugas1'))
            ->get(route('inventaris.labels'))
            ->assertOk()
            ->assertSee('QR-005')
            ->assertSee('Cetak semua label')
            ->assertSee('data-qr="QR-005"', false);
    }

    public function test_assistant_can_log_out(): void
    {
        $this->addAssistant();

        $this->actingAs(Asisten::query()->find('petugas1'))
            ->post(route('logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    private function addAssistant(): void
    {
        DB::table('asisten')->insert([
            'username' => 'petugas1',
            'nama_asisten' => 'Petugas Laboratorium',
            'password' => 'kata-sandi-lama',
        ]);
    }

    private function addAsset(string $id, string $name): void
    {
        DB::table('alat_inventaris')->insert([
            'id_alat' => $id,
            'nama_alat' => $name,
            'lokasi_meja' => 'Meja praktikum',
        ]);
    }
}
