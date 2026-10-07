<?php

namespace App\Models;

use App\Helper\ExtendedModel;

class AuditLog extends ExtendedModel
{
    protected $keyType = 'string';
    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'id',
        'user_type',
        'user_id',
        'method',
        'path',
        'route_name',
        'module',
        'status_code',
        'duration_ms',
        'payload',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'payload' => 'array',
        'duration_ms' => 'float',
    ];

    protected $appends = ['user_name'];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'user_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getUserNameAttribute(): ?string
    {
        if ($this->user_type === 'admin') {
            return $this->admin?->name;
        }

        if ($this->user_type === 'user') {
            return $this->user?->name;
        }

        return null;
    }
}