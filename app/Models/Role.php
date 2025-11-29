<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }

    public function hasPermission($permission)
    {
        if (is_string($permission)) {
            // Check by slug so we can use permission identifiers like "manage-webpages"
            return $this->permissions->contains('slug', $permission);
        }
        return $this->permissions->contains('id', $permission->id);
    }

    public function assignPermission($permission)
    {
        if (is_string($permission)) {
            // Resolve by slug when a string is provided
            $permission = Permission::where('slug', $permission)->firstOrFail();
        }
        $this->permissions()->syncWithoutDetaching([$permission->id]);
    }

    public function removePermission($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('slug', $permission)->firstOrFail();
        }
        $this->permissions()->detach($permission->id);
    }
}

