<?php

namespace App\Models;

use App\Enums\PrefixEnums;
use App\Events\UserSaved;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'prefixname',
        'firstname',
        'middlename',
        'lastname',
        'suffixname',
        'username',
        'photo',
        'type',
        'email',
        'password',
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
     * The attributes that should be appended to the model's array and JSON form.
     *
     * @var array
     */
    protected $appends = [
        'avatar',
        'fullname',
        'gender',
        'middleinitial',
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
            'password'          => 'hashed',
        ];
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::saved(function ($user) {
            event(new UserSaved($user));
        });
    }

    /**
     * Retrieve the default photo from storage.
     * Supply a base64 png image if the `photo` column is null.
     *
     * @return string|null
     */
    public function getAvatarAttribute():? string
    {
        if ($this->photo) {
            return asset('storage/avatars/' . $this->photo);
        }

        // Path to a default image
        $path = resource_path('img/default-avatar.png');

        // Check if file exists and is readable
        if (file_exists($path) && is_readable($path)) {
            // Read the image file, encode it in base64, and prepend the appropriate URI scheme header
            return 'data:image/png;base64,' . base64_encode(file_get_contents($path));
        }

        // Return null if the image file not found
        return null;
    }

    /**
     * Retrieve the user's full name in the format:
     *  [firstname][ mi?][ lastname]
     * Where:
     *  [ mi?] is the optional middle initial.
     *
     * @return string
     */
    public function getFullnameAttribute(): string
    {
        $fullname = $this->firstname;
        if (!empty($this->middlename)) {
            $fullname .= ' ' . strtoupper(substr($this->middlename, 0, 1)) . '.';
        }

        $fullname .= ' ' . $this->lastname;

        return $fullname;
    }

    /**
     * Get the user's middle initial.
     *
     * @return string|null
     */
    public function getMiddleinitialAttribute():? string
    {
        if (!empty($this->middlename)) {
            return strtoupper(substr($this->middlename, 0, 1)) . '.';
        }

        return null;
    }

    /**
     * Get the user's gender.
     *
     * @return string|null
     */
    public function getGenderAttribute(): ?string
    {
        if ($this->prefixname) {
            $prefixEnum = PrefixEnums::tryFrom($this->prefixname);

            if ($prefixEnum) {
                return $prefixEnum->gender();
            }
        }

        return null;
    }

    /**
     * @return HasMany
     */
    public function details(): HasMany
    {
        return $this->hasMany(Detail::class);
    }

    /**
     * @param Builder $builder
     * @param array   $ids
     *
     * @return Builder
     */
    public function scopeForIds(Builder $builder, array $ids): Builder
    {
        return $builder->whereIn('id', $ids);
    }
}
