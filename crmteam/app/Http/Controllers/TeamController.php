<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::withCount('users')->get();
        return view('teams.index', compact('teams'));
    }

    public function create()
    {
        return view('teams.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:teams,name',
        ]);

        Team::create($request->only('name', 'description'));

        return redirect()->route('teams.index')->with('success', 'Team created successfully.');
    }

    public function edit($id)
    {
        $team = Team::with(['users' => function($query) {
            $query->withPivot('role_id', 'parent_id');
        }])->findOrFail($id);
        
        $allUsers = User::all();
        $roles = Role::all();
        $permissions = Permission::all();

        return view('teams.edit', compact('team', 'allUsers', 'roles', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        $team = Team::findOrFail($id);
        $team->update($request->only('name', 'description'));

        return redirect()->route('teams.index')->with('success', 'Team updated successfully.');
    }

    public function addMember(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        $team = Team::findOrFail($id);
        
        // Check if already a member
        if ($team->users()->where('user_id', $request->user_id)->exists()) {
            return back()->with('error', 'User is already a member of this team.');
        }

        $team->users()->attach($request->user_id, [
            'role_id' => $request->role_id,
            'parent_id' => $request->parent_id ?? null
        ]);

        return back()->with('success', 'Member added successfully.');
    }

    public function updateMember(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        $team = Team::findOrFail($id);
        
        $team->users()->updateExistingPivot($request->user_id, [
            'role_id' => $request->role_id,
            'parent_id' => $request->parent_id ?? null
        ]);

        return back()->with('success', 'Member details updated successfully.');
    }

    public function removeMember(Request $request, $id)
    {
        $team = Team::findOrFail($id);
        $team->users()->detach($request->user_id);
        return back()->with('success', 'Member removed successfully.');
    }

    public function hierarchy($id)
    {
        $team = Team::findOrFail($id);
        $user = auth()->user();
        $allMembers = TeamMember::where('team_id', $id)->with(['user', 'role', 'parent'])->get();
        
        if ($user->hasRole('Admin')) {
            // Admin sees everything starting from roots
            $memberIds = $allMembers->pluck('user_id')->toArray();
            $rootMembers = $allMembers->filter(function($m) use ($memberIds) {
                return $m->parent_id == null || !in_array($m->parent_id, $memberIds);
            });
            $members = $allMembers;
        } else {
            // Regular user only sees their sub-tree and their immediate parent
            $currentUserInTeam = $allMembers->where('user_id', $user->id)->first();
            
            if (!$currentUserInTeam) {
                return back()->with('error', 'You are not a member of this team.');
            }

            $descendantIds = $this->getDescendantIds($allMembers, $user->id);
            $visibleIds = array_merge([$user->id, $currentUserInTeam->parent_id], $descendantIds);
            
            $members = $allMembers->whereIn('user_id', $visibleIds);
            
            // For a regular user, the "root" is either their parent or themselves if they have no parent
            if ($currentUserInTeam->parent_id && in_array($currentUserInTeam->parent_id, $allMembers->pluck('user_id')->toArray())) {
                $rootMembers = $allMembers->where('user_id', $currentUserInTeam->parent_id);
            } else {
                $rootMembers = collect([$currentUserInTeam]);
            }
        }

        return view('teams.hierarchy', compact('team', 'rootMembers', 'members'));
    }

    private function getDescendantIds($members, $parentId)
    {
        $ids = [];
        $children = $members->where('parent_id', $parentId);
        foreach ($children as $child) {
            $ids[] = $child->user_id;
            $ids = array_merge($ids, $this->getDescendantIds($members, $child->user_id));
        }
        return $ids;
    }
    
    public function updateRolePermissions(Request $request)
    {
        $role = Role::findOrFail($request->role_id);
        $role->syncPermissions($request->permissions ?? []);
        return back()->with('success', 'Role permissions updated successfully.');
    }

    public function getRolePermissions($role_id)
    {
        $role = Role::findOrFail($role_id);
        
        return response()->json([
            'permissions' => $role->permissions->pluck('name')->toArray()
        ]);
    }

    public function getUserPermissions($user_id)
    {
        $user = User::findOrFail($user_id);
        
        // Permissions from roles
        $rolePermissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();
        
        // Direct permissions
        $directPermissions = $user->getDirectPermissions()->pluck('name')->toArray();
        
        $revoked = $user->revoked_permissions ? json_decode($user->revoked_permissions, true) : [];
        
        return response()->json([
            'user_name' => $user->name,
            'role_permissions' => $rolePermissions,
            'direct_permissions' => $directPermissions,
            'revoked_permissions' => $revoked
        ]);
    }

    public function updateUserPermissions(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $permissions = $request->permissions ?? [];
        
        // Find which role permissions were unchecked by user and mark them revoked
        $rolePerms = $user->getPermissionsViaRoles()->pluck('name')->toArray();
        $revoked = array_diff($rolePerms, $permissions);
        $user->revoked_permissions = json_encode(array_values($revoked));
        $user->saveQuietly();
        
        $user->syncPermissions($permissions);
        
        return back()->with('success', 'Individual permissions for ' . $user->name . ' updated successfully.');
    }

    public function destroy($id)
    {
        $team = Team::findOrFail($id);
        
        // Remove all members first to prevent orphan records
        $team->users()->detach();
        
        $team->delete();
        
        return back()->with('success', 'Team deleted successfully.');
    }
}
