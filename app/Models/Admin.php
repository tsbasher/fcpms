<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Helper\ExtendedModel;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class Admin extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $guard = 'admin';
    protected $keyType = 'string';
    public $incrementing = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'phone',
        'password',
        'project_id',
        'project_code',
        'role_id',
        'is_active',
        'must_change_password',
        'last_seen_at',
        'login_attempts',
        'locked_until',
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
            'must_change_password' => 'boolean',
            'last_seen_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
            if (array_key_exists('created_by', $model->getAttributes()) && empty($model->created_by)) {
                $model->created_by = Auth::guard('admin')->user()->id ?? null;
            }
        });
        static::updating(function ($model) {
            if (array_key_exists('updated_by', $model->getAttributes()) && empty($model->updated_by)) {
                $model->updated_by = Auth::guard('admin')->user()->id ?? null;
            }
        });
    }


    public function projects()
    {
        return $this->belongsToMany(Project::class, 'admin_projects', 'admin_id', 'project_id');
    }

    public function getIsOnlineAttribute(): bool
    {
        return $this->last_seen_at !== null && $this->last_seen_at->gte(now()->subMinutes(5));
    }
    
    public function packages()
    {
        return $this->belongsToMany(Package::class, 'admin_packages', 'admin_id', 'package_id');
    }
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function isSuperAdmin(): bool
    {
        return $this->role !== null && $this->role->is_super_admin;
    }

    public function hasPermission(string $slug): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->role !== null && $this->role->hasPermission($slug);
    }

    public function sendPasswordResetNotification($token)
    {
        $url = url(route('admin.password.reset', ['token' => $token, 'email' => $this->email], false));
        Mail::to($this->email)->send(new ResetPasswordMail($url, true));
    }
}
