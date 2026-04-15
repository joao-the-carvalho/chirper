<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
    'name',
    'email',
    'password',
    'username',
    'bio',
    'avatar',
];
public function avatarUrl(): string
{
    if ($this->avatar) {
        try {
            if (app()->isProduction()) {
                // S3
                if (Storage::disk('s3')->exists($this->avatar)) {
                    return Storage::disk('s3')->url($this->avatar);
                }
            } else {
                // Local: verifica se o arquivo existe fisicamente
                $localPath = storage_path('app/public/' . $this->avatar);
                if (file_exists($localPath)) {
                    return asset('storage/' . $this->avatar);
                }
            }
        } catch (\Exception $e) {
            \Log::error('Erro no avatar: ' . $e->getMessage());
        }
    }
    
    // Fallback
    $name = urlencode($this->name ?? $this->username ?? explode('@', $this->email)[0]);
    return "https://ui-avatars.com/api/?name={$name}&background=6366f1&color=fff&size=200";
}
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function chirps(): HasMany
{
    return $this->hasMany(Chirp::class);
}
}


