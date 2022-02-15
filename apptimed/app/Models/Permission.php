<?php

namespace App\Models;

use Trebol\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    protected $fillable = [
        'name',
        'display_name ',
        'type ',
        'description',
    ];


    public function roles(){
        return $this->belongsToMany('App\Models\Role');
    }

}
