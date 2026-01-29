<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, \App\Traits\LogsActivity, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'church_id',
        'profile_photo_path',
        'notification_preferences',
    ];

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
            'notification_preferences' => 'array',
        ];
    }

    /**
     * Get default notification preferences.
     */
    public static function defaultNotificationPreferences(): array
    {
        return [
            'email' => true,
            'push' => true,
            'database' => true,
            'channels' => [
                'expenses' => ['email' => true, 'push' => true, 'database' => true],
                'activities' => ['email' => true, 'push' => true, 'database' => true],
                'diocese' => ['email' => true, 'push' => true, 'database' => true],
                'contracts' => ['email' => true, 'push' => true, 'database' => true],
                'evangelism' => ['email' => true, 'push' => true, 'database' => true],
            ],
        ];
    }

    /**
     * Get notification preferences with defaults.
     */
    public function getNotificationPreferencesAttribute($value): array
    {
        $preferences = $value ? json_decode($value, true) : [];
        return array_merge(self::defaultNotificationPreferences(), $preferences);
    }

    /**
     * Check if user wants email notifications for a category.
     */
    public function wantsEmailNotification(string $category = null): bool
    {
        $prefs = $this->notification_preferences;
        if (!$prefs['email']) return false;
        if ($category && isset($prefs['channels'][$category])) {
            return $prefs['channels'][$category]['email'] ?? true;
        }
        return true;
    }

    /**
     * Check if user wants push notifications for a category.
     */
    public function wantsPushNotification(string $category = null): bool
    {
        $prefs = $this->notification_preferences;
        if (!$prefs['push']) return false;
        if ($category && isset($prefs['channels'][$category])) {
            return $prefs['channels'][$category]['push'] ?? true;
        }
        return true;
    }

    /**
     * Relationships
     */
    public function church()
    {
        return $this->belongsTo(Church::class);
    }
    
    /**
     * Get the URL to the user's profile photo.
     *
     * @return string
     */
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo_path) {
            return asset('storage/' . $this->profile_photo_path);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class)
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function managedChurches()
    {
        return $this->hasMany(Church::class, 'pastor_id');
    }

    public function supervisedChurches()
    {
        return $this->hasMany(Church::class, 'archid_id');
    }
}
