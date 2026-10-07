<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AlatInventaris extends Model
{
    protected $table = 'alat_inventaris';

    protected $primaryKey = 'id_alat';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['id_alat', 'nama_alat', 'lokasi_meja'];

    public function logInspeksi(): HasMany
    {
        return $this->hasMany(LogInspeksi::class, 'id_alat', 'id_alat');
    }
}
