<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $query = User::with(['roles', 'tl1', 'tl2', 'tl3', 'tl4', 'tl5']);
        
        if ($user->hasRole('Admin')) {
            $users = $query->paginate(15);
        } elseif ($user->hasRole('Team Lead')) {
            $users = $query->where('id', $user->id)
                ->orWhere('tl_1', $user->id)
                ->orWhere('tl_2', $user->id)
                ->orWhere('tl_3', $user->id)
                ->orWhere('tl_4', $user->id)
                ->orWhere('tl_5', $user->id)
                ->paginate(15);
        } else {
            $users = $query->where('id', $user->id)->paginate(15);
        }

        return view('useraccounts.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::where('name', '!=', 'PANKAJ SINHA')->get();

        return view('useraccounts.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'email',
            'password' => 'required|confirmed',
            'role' => 'required',
        ]);

        $photo = null; $aadhar = null; $pancard = null; $police_verification = null;
        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $photo = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('/storage/galeryImages/'), $photo);
        }
        if ($request->hasFile('aadhar')) {
            $image = $request->file('aadhar');
            $aadhar = time().'_aadhar.'.$image->getClientOriginalExtension();
            $image->move(public_path('/storage/galeryImages/'), $aadhar);
        }
        if ($request->hasFile('pancard')) {
            $image = $request->file('pancard');
            $pancard = time().'_pan.'.$image->getClientOriginalExtension();
            $image->move(public_path('/storage/galeryImages/'), $pancard);
        }
        if ($request->hasFile('police_verification')) {
            $image = $request->file('police_verification');
            $police_verification = time().'_police.'.$image->getClientOriginalExtension();
            $image->move(public_path('/storage/galeryImages/'), $police_verification);
        }
        $user_verification = null;
        if ($request->hasFile('user_verification')) {
            $image = $request->file('user_verification');
            $user_verification = time().'_uverif.'.$image->getClientOriginalExtension();
            $image->move(public_path('/storage/galeryImages/'), $user_verification);
        }

		// Process new documents using array
		$uploadedDocs = [];
		$docFields = ['aadhar_front', 'aadhar_back', 'pan_card_front', 'pan_card_back', 'resume'];
		foreach ($docFields as $docField) {
			if ($request->hasFile($docField)) {
				$image = $request->file($docField);
				$filename = time() . '_' . $image->getClientOriginalName();
				$image->move(public_path('/storage/galeryImages/'), $filename);
				$uploadedDocs[$docField] = $filename;
			}
		}

        DB::beginTransaction();
        try {
            $role = Role::findorfail($request->input('role'));

            $user = User::create([
                'name' => ucwords($request->input('name')),
                'email' => $request->input('email'),
                'password' => $request->input('password'),
                'phone' => $request->input('phone') ?? null,
                'address' => $request->input('address') ?? null,
                'status' => $request->input('status') ?? null,
                'export' => $request->input('export') ?? null,
                'location' => $request->input('location') ?? null,
                'salary' => $request->input('salary') ?? null,
                'tl_1' => $request->input('tl_1') ?? null,
                'tl_2' => $request->input('tl_2') ?? null,
                'tl_3' => $request->input('tl_3') ?? null,
                'tl_4' => $request->input('tl_4') ?? null,
                'tl_5' => $request->input('tl_5') ?? null,
                'police_verification_no' => $request->input('police_verification_no') ?? null,
                'reference_name_1' => $request->input('reference_name_1') ?? null,
                'reference_phone_1' => $request->input('reference_phone_1') ?? null,
                'reference_name_2' => $request->input('reference_name_2') ?? null,
                'reference_phone_2' => $request->input('reference_phone_2') ?? null,
                'incentive_percentage' => $request->input('incentive_percentage') ?? null,
            ]);
            $user->assignRole($role);
            
            if($photo) $user->photo_url = $photo;
            if($aadhar) $user->aadhar = $aadhar;
            if($pancard) $user->pancard = $pancard;
            if($police_verification) $user->police_verification = $police_verification;
            if($user_verification) $user->user_verification = $user_verification;
            
			foreach ($docFields as $docField) {
				if (isset($uploadedDocs[$docField])) {
					$user->$docField = $uploadedDocs[$docField];
				}
			}
            
            $user->saveQuietly();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('useraccounts.create')->withErrors([
                'db_error' => $e->getMessage(),
            ]);
        }
        DB::commit();

        return redirect(route('useraccounts.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findorfail($id);

        return view('useraccounts.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findorfail($id);
        $roles = Role::where('name', '!=', 'PANKAJ SINHA')->get();
        $permissions = Permission::all();
        $userDirectPermissions = $user->getDirectPermissions()->pluck('id')->toArray();

        return view('useraccounts.edit', compact('user', 'roles', 'permissions', 'userDirectPermissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'email',
            //'password' => 'required|confirmed',
            'role' => 'required',
            'status' => 'required',
        ]);

        DB::beginTransaction();
        try {
			
			DB::table('model_has_roles')->where('model_id', $id)->delete();
            $role = Role::findorfail($request->input('role'));
            $user = User::findorfail($id);
			
            $user->update([
                'name' => ucwords($request->input('name')),
                'email' => $request->input('email'),
                'status' => $request->input('status'),
                'export' => $request->input('export'),
                'phone' => $request->input('phone'),
                'address' => $request->input('address'),
                'salary' => $request->input('salary'),
                'location' => $request->input('location'),
                'tl_1' => $request->input('tl_1'),
                'tl_2' => $request->input('tl_2'),
                'tl_3' => $request->input('tl_3'),
                'tl_4' => $request->input('tl_4'),
                'tl_5' => $request->input('tl_5'),
                'police_verification_no' => $request->input('police_verification_no'),
                'reference_name_1' => $request->input('reference_name_1'),
                'reference_phone_1' => $request->input('reference_phone_1'),
                'reference_name_2' => $request->input('reference_name_2'),
                'reference_phone_2' => $request->input('reference_phone_2'),
                //'password' => '',
				'incentive_percentage' => $request->input('incentive_percentage'),
            ]);
            //$user->setPasswordAttribute($request->input('password'));
            $user->assignRole($role);
            $user->saveQuietly();

            // handle image if its present
            if ($request->hasFile('photo')) {
                // delete old photo if present
                if($user->photo_url !== null) {
                    $file_path = public_path('/storage/galeryImages/' . $user->photo_url);
                    @unlink($file_path);
                }

                // now add new photo
                $image = $request->file('photo');
				//echo "<pre>";print_r($image->getClientOriginalName());exit;
				$profile_photo = $image->getClientOriginalName();
				$destinationPath = public_path('/storage/galeryImages/');
				$image->move($destinationPath, $profile_photo);
				 $user->photo_url = $profile_photo;
                $user->saveQuietly();
            }
			if ($request->hasFile('aadhar')) {
                // delete old photo if present
                if($user->aadhar !== null) {
                    $file_path = public_path('/storage/galeryImages/' . $user->aadhar);
                    @unlink($file_path);
                }

                // now add new photo
                $image = $request->file('aadhar');
				//echo "<pre>";print_r($image);exit;
				$aadhar = $image->getClientOriginalName();
				$destinationPath = public_path('/storage/galeryImages/');
				$image->move($destinationPath, $aadhar);
				 $user->aadhar = $aadhar;
                $user->saveQuietly();
            }
			if ($request->hasFile('pancard')) {
                // delete old photo if present
                if($user->pancard !== null) {
                    $file_path = public_path('/storage/galeryImages/' . $user->pancard);
                    @unlink($file_path);
                }

                // now add new photo
                $image = $request->file('pancard');
				//echo "<pre>";print_r($image);exit;
				$pancard = $image->getClientOriginalName();
				$destinationPath = public_path('/storage/galeryImages/');
				$image->move($destinationPath, $pancard);
				$user->pancard = $pancard;
                $user->saveQuietly();
            }

			// New Documents Here
			$docFields = ['aadhar_front', 'aadhar_back', 'pan_card_front', 'pan_card_back', 'resume'];
			foreach ($docFields as $docField) {
				if ($request->hasFile($docField)) {
					if ($user->$docField !== null) {
						$file_path = public_path('/storage/galeryImages/' . $user->$docField);
						@unlink($file_path);
					}
					$image = $request->file($docField);
					$filename = time() . '_' . $image->getClientOriginalName();
					$destinationPath = public_path('/storage/galeryImages/');
					$image->move($destinationPath, $filename);
					$user->$docField = $filename;
					$user->saveQuietly();
				}
			}
			if ($request->hasFile('police_verification')) {
                if($user->police_verification !== null) {
                    $file_path = public_path('/storage/galeryImages/' . $user->police_verification);
                    @unlink($file_path);
                }

                $image = $request->file('police_verification');
				$police_verification = $image->getClientOriginalName();
				$destinationPath = public_path('/storage/galeryImages/');
				$image->move($destinationPath, $police_verification);
				$user->police_verification = $police_verification;
                $user->saveQuietly();
            }
			if ($request->hasFile('user_verification')) {
                if($user->user_verification !== null) {
                    $file_path = public_path('/storage/galeryImages/' . $user->user_verification);
                    @unlink($file_path);
                }

                $image = $request->file('user_verification');
				$user_verification = $image->getClientOriginalName();
				$destinationPath = public_path('/storage/galeryImages/');
				$image->move($destinationPath, $user_verification);
				$user->user_verification = $user_verification;
                $user->saveQuietly();
            }
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors([
                'db_error' => $e->getMessage(),
            ]);
        }
        DB::commit();

        return redirect(route('useraccounts.index'));
    }

    public function updateDirectPermissions(Request $request, $id)
    {
        $user = User::findorfail($id);
        $permissions = $request->input('permissions', []);
        
        // Find which role permissions were unchecked by user and mark them revoked
        $rolePerms = $user->getPermissionsViaRoles()->pluck('name')->toArray();
        $revoked = array_diff($rolePerms, $permissions);
        $user->revoked_permissions = json_encode(array_values($revoked));
        $user->saveQuietly();

        // syncPermissions sets ONLY direct permissions (does not touch role permissions)
        $user->syncPermissions($permissions);
        return redirect()->back()->with('success', 'Direct permissions for ' . $user->name . ' updated successfully.');
    }
 public function update_password(Request $request, $id)
    {
        $this->validate($request, [
			'password' => 'required|confirmed',
        ]);

        DB::beginTransaction();
        try {			
            $user = User::findorfail($id);
            $user->password = $request->input('password');
            $user->saveQuietly();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors([
                'db_error' => $e->getMessage(),
            ]);
        }
        DB::commit();

        return redirect(route('useraccounts.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $user = User::findorfail($id);
        //$user->deletedBy()->associate(auth()->user());
       // $user->saveQuietly();
        $user->forceDelete();

        return back();
    }

    public function acceptUser($id)
    {
        $user = User::findOrFail($id);
        $user->status = 1;
        $user->saveQuietly();
        return redirect()->back()->with('success', 'User accepted and activated successfully.');
    }

    public function declineUser($id)
    {
        $user = User::findOrFail($id);
        $user->forceDelete();
        return redirect()->back()->with('success', 'User declined and removed successfully.');
    }
}
