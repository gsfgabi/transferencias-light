<?php

namespace PHPSTORM_META {
    // Spatie Permission
    override(\Spatie\Permission\Models\Role::class, map([
        '' => '@|\Spatie\Permission\Models\Role',
    ]));
    
    override(\Spatie\Permission\Models\Permission::class, map([
        '' => '@|\Spatie\Permission\Models\Permission',
    ]));
    
    override(\Spatie\Permission\PermissionRegistrar::class, map([
        '' => '@|\Spatie\Permission\PermissionRegistrar',
    ]));
    
    // Laravel Eloquent
    override(\Illuminate\Database\Eloquent\Model::class, map([
        'role' => '@|\Spatie\Permission\Models\Role',
        'permission' => '@|\Spatie\Permission\Models\Permission',
    ]));
    
    // Laravel Auth
    override(\Illuminate\Contracts\Auth\Authenticatable::class, map([
        'user' => '@|\App\Models\User',
    ]));
}