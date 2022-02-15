<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Services\ActivityLogService;
class PermissionRole extends Pivot
{
    protected $table = 'permission_role';
    public $timestamps = false;

    protected $fillable = [
        'permission_id',
        'role_id ',

    ];
    public function permission(){
        return $this->hasOne('App\Models\Permission', 'id', 'permission_id');
    }

    public function role(){
        return $this->hasOne('App\Models\Role', 'id', 'user_id');
    }

}
