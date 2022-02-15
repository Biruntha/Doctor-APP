<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\UserPermission;
use Illuminate\Database\Seeder;
use DB;
class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([
            // Roles
            ['name' => "can-add-role",'display_name' => "Can add role",'type' => "Role"],
            ['name' => "can-view-role",'display_name' => "Can view role",'type' => "Role"],
            ['name' => "can-edit-role",'display_name' => "Can edit role",'type' => "Role"],
            ['name' => "can-delete-role",'display_name' => "Can delete role",'type' => "Role"],

            // Specialization
            ['name' => "can-add-specialization",'display_name' => "Can add specialization",'type' => "specialization"],
            ['name' => "can-view-specialization",'display_name' => "Can view specialization",'type' => "specialization"],
            ['name' => "can-edit-specialization",'display_name' => "Can edit specialization",'type' => "specialization"],
            ['name' => "can-delete-specialization",'display_name' => "Can delete specialization",'type' => "specialization"],

            // problemtypes
            ['name' => "can-add-problemtype",'display_name' => "Can add problemtype",'type' => "problemtype"],
            ['name' => "can-view-problemtype",'display_name' => "Can view problemtype",'type' => "problemtype"],
            ['name' => "can-edit-problemtype",'display_name' => "Can edit problemtype",'type' => "problemtype"],
            ['name' => "can-delete-problemtype",'display_name' => "Can delete problemtype",'type' => "problemtype"],

             // problems
            ['name' => "can-add-problem",'display_name' => "Can add problem",'type' => "problem"],
            ['name' => "can-view-problem",'display_name' => "Can view problem",'type' => "problem"],
            ['name' => "can-edit-problem",'display_name' => "Can edit problem",'type' => "problem"],
            ['name' => "can-delete-problem",'display_name' => "Can delete problem",'type' => "problem"],

            // cancellationreasons
            ['name' => "can-add-cancellationreason",'display_name' => "Can add cancellationreason",'type' => "cancellationreason"],
            ['name' => "can-view-cancellationreason",'display_name' => "Can view cancellationreason",'type' => "cancellationreason"],
            ['name' => "can-edit-cancellationreason",'display_name' => "Can edit cancellationreason",'type' => "cancellationreason"],
            ['name' => "can-delete-cancellationreason",'display_name' => "Can delete cancellationreason",'type' => "cancellationreason"],

            // transferreasons
            ['name' => "can-add-transferreason",'display_name' => "Can add transferreason",'type' => "transferreason"],
            ['name' => "can-view-transferreason",'display_name' => "Can view transferreason",'type' => "transferreason"],
            ['name' => "can-edit-transferreason",'display_name' => "Can edit transferreason",'type' => "transferreason"],
            ['name' => "can-delete-transferreason",'display_name' => "Can delete transferreason",'type' => "transferreason"],

        ]);

        /**
        DB::table('roles')->insert([
            'name' => "Admin"
        ]);
        DB::table('roles')->insert([
            'name' => "Manager"
        ]);**/


        //$admin = DB::table('roles')->where('name', 'Admin')->first()->id;

        //DB::table('role_user')->insert(['user_id'=>1 , 'role_id'=>$admin]);
        $permissions = Permission::all(); //

        foreach ($permissions as $key => $permission) {
           PermissionRole::updateOrCreate(['permission_id'=>$permission->id , 'role_id'=>1]);
           UserPermission::updateOrCreate(['permission_id'=>$permission->id , 'user_id'=>1]);
        }
    }
}
