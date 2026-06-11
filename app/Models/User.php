<?php

namespace App\Models;

use App\Support\PublicStorageUrl;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'photo',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getPhotoUrlAttribute(): string
    {
        $photo = $this->photo ?: $this->linkedProfilePhotoPath();

        if (empty($photo)) {
            $name = urlencode((string) $this->name);

            return 'https://ui-avatars.com/api/?name=' . $name . '&background=eff6ff&color=0284c7';
        }

        if (filter_var($photo, FILTER_VALIDATE_URL)) {
            return $photo;
        }

        $photoPath = ltrim($photo, '/');
        $photoPath = preg_replace('#^storage_public/#', '', $photoPath) ?? $photoPath;
        $photoPath = preg_replace('#^storage/#', '', $photoPath) ?? $photoPath;

        return PublicStorageUrl::storageUrl($photoPath) ?? asset('storage/' . $photoPath);
    }

    private function linkedProfilePhotoPath(): ?string
    {
        if (empty($this->email)) {
            return null;
        }

        return match ($this->role) {
            'siswa' => Student::query()->where('email', $this->email)->value('photo_path'),
            'guru' => Teacher::query()->where('email', $this->email)->value('photo_path'),
            default => null,
        };
    }
}
