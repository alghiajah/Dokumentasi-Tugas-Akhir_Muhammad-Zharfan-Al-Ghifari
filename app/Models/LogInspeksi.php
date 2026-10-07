<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogInspeksi extends Model
{
    protected $table = 'log_inspeksi';

    protected $primaryKey = 'id_log';

    public $timestamps = false;

    protected $fillable = ['id_alat', 'username', 'status', 'catatan'];

    protected function casts(): array
    {
        return ['waktu_inspeksi' => 'datetime'];
    }

    public function alat(): BelongsTo
    {
        return $this->belongsTo(AlatInventaris::class, 'id_alat', 'id_alat');
    }

    public function asisten(): BelongsTo
    {
        return $this->belongsTo(Asisten::class, 'username', 'username');
    }
}
