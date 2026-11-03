<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
        $users = User::all();
		

        return view('roles.index', compact('roles', 'users'));

    }
	public function edit(Request $request)
    {
        $roles = Role::where('id', $request->id)->first();

		if($request->isMethod('post')) {
			// FIXED: Delete ALL existing permissions for this role first
			// This ensures that un-ticking a checkbox actually REMOVES the permission
			DB::delete("DELETE FROM role_has_permissions WHERE role_id = ?", [$request->id]);

			// Then re-insert only the currently checked/submitted permissions
			if($request->permissions){
				foreach($request->permissions as $p){
					DB::insert('insert into role_has_permissions (role_id, permission_id) values (?,?)', [$request->id, $p]);
				}
			}
			
			// CLEAR SPATIE PERMISSION CACHE
			app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
		}

		$permissions = DB::select("SELECT permissions.*, role_has_permissions.permission_id FROM permissions LEFT JOIN role_has_permissions ON permissions.id = role_has_permissions.permission_id and role_has_permissions.role_id='".$request->id."' "); 
		return view('roles.edit', compact('roles','permissions'));

    }
	
	public function create(Request $request)
    {
		if($request->permissions && $request->role){
			$insert =DB::insert('insert into roles (name, guard_name) values ("'.$request->role.'","web")');
			$role_id =DB::select('Select id from roles where name ="'.$request->role.'"');
			foreach($request->permissions as $p){
				$insert =DB::insert('insert into role_has_permissions (role_id, permission_id) values ("'.$role_id[0]->id.'","'.$p.'")');
			}
			// CLEAR SPATIE PERMISSION CACHE
			app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
			
			$roles = Role::all();
			 return view('roles.index', compact('roles'));
		}
        $permissions = DB::select("SELECT permissions.* FROM permissions "); 
        return view('roles.create', compact('permissions'));

    }

    
}
