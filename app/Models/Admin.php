<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Helper\ExtendedModel;
use Illuminate\Support\Facades\Auth;
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
        'password',
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
    
    public function packages()
    {
        return $this->belongsToMany(Package::class, 'admin_packages', 'admin_id', 'package_id');
    }
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }
}
