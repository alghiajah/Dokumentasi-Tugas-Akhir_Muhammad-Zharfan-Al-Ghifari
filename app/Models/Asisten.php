<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Asisten extends Authenticatable
{
    protected $table = 'asisten';

    protected $primaryKey = 'username';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $hidden = ['password'];

    public function getAuthPasswordName(): string
    {
        return 'password';
    }

    public function getNameAttribute(): string
    {
        return $this->nama_asisten;
    }

    public function logInspeksi(): HasMany
    {
        return $this->hasMany(LogInspeksi::class, 'username', 'username');
    }
}
