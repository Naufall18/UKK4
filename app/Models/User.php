<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string $name
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $role
 * @property string $nis
 * @property string $kelas
 * @property string $no_hp
 * @property string $no_anggota
 * @property bool $status_aktif
 * @property string|null $foto_profil
 * @property-read string|null $foto_profil_url
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'nis',
        'kelas',
        'no_hp',
        'no_anggota',
        'status_aktif',
        'foto_profil',
    ];

    protected $appends = ['foto_profil_url'];

    public function getFotoProfilUrlAttribute()
    {
        return $this->foto_profil ? asset('storage/' . $this->foto_profil) : null;
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'status_aktif' => 'boolean',
    ];

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isSiswa()
    {
        return $this->role === 'siswa';
    }
}
