<?php

namespace App\Models;

use Trebol\Entrust\EntrustRole;
use App\Services\ActivityLogService;
class Role extends EntrustRole
{

    protected $fillable = [
        'name',
        'display_name ',
        'description',
    ];

    // public static function boot()
    // {
    //     parent::boot();
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

        // self::created(function($model){

        //     $activity = new ActivityLogService();
        //     $activity->createActivityLog(ucfirst ($model->table),'add',$model->name,$model->id);
        // });
        // self::updated(function($model){
        //     $activity = new ActivityLogService();
        //     $activity->createActivityLog(ucfirst ($model->table),'update',$model->name,$model->id);
        // });
        // self::deleted(function($model){
        //     $activity = new ActivityLogService();
        //     $activity->createActivityLog(ucfirst ($model->table),'delete',$model->name,$model->id);
        // });
    // }


    public function users(){
        return $this->belongsToMany('App\Models\User');
    }

    public function permissionRole(){
        return $this->hasMany('App\Models\PermissionRole');
    }


    public function permissions(){
        return $this->belongsToMany('App\Models\Permission');
    }

    public function getNameAttribute():string{
        return ucwords($this->attributes['name']);
    }
    public function getDescriptionAttribute():string{
        return ucwords($this->attributes['description']);
    }
}
