<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Services\ActivityLogService;
class RoleUser extends Pivot
{


    protected $table = 'role_user';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'role_id',

    ];
    public function user(){
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function role(){
        return $this->hasOne('App\Models\Role', 'id', 'user_id');
    }
    public static function boot()
    {
        parent::boot();
        // static::creating(function($user)
        // {
        //     if(Auth::check()){
        //         $user->added_by = Auth::user()->username;
        //     }
        //         $user->verified_token = uniqid(rand(), true);
        // });

        // static::updating(function($user)
        // {
        //     if(Auth::check()){
        //         $user->updated_by = Auth::user()->username;
        //     }
        // });

        // static::deleting(function($user)
        // {
        //     if(Auth::check()){
        //         $user->deleted_by = Auth::user()->username;
        //     }
        // });

        self::created(function($model){
            $activity = new ActivityLogService();
            $activity->createActivityLog("Role User",'add'," ",$model->id);
        });
        self::updated(function($model){
            $activity = new ActivityLogService();
            $activity->createActivityLog("Role User",'update'," ",$model->id);
        });
        self::deleted(function($model){
            $activity = new ActivityLogService();
            $activity->createActivityLog("Role User",'delete'," ",$model->id);
        });
    }
}
