<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mail;
use Illuminate\Support\Facades\DB;


use App\Models\User;

class AccountsController extends Controller
{
    protected $redirectTo = '/';

    public function index()
    {
        // If the users table is empty then redirect
        // to the signup page.
        // Route 'signup.index' is missing, removing logic to prevent 500 error on prod.
        /* $users = User::all();
        if (count($users) < 1) {
            return redirect()->route('signup.index');
        } */

        return view('signin.index');
    }

    public function signin(Request $request)
    {
        $request->flashExcept('password');

        $credentials = $request->only('email', 'password');
        $credentials['status'] = 1; // Block inactive users from logging in

        $remember = false;

        if ($request->has('remember')) {
            $remember = true;
        }
		
        // Attempt to login directly without OTP
        if (Auth::attempt($credentials, $remember)) {
            // Regenerate session to prevent fixation
            $request->session()->regenerate();
            return redirect()->intended('/')->with('success', 'Login successful.');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records or your account is currently inactive.',
        ]);
    }

    public function signout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('signin.index');
    }

    public function register()
    {
        return view('signin.register');
    }

    public function storeRegister(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'role' => 'required',
            'phone' => 'required',
        ]);

        $photo = null;
        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $photo = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('/storage/galeryImages/'), $photo);
        }

        DB::beginTransaction();
        try {
            $role = \Spatie\Permission\Models\Role::where('name', $request->input('role'))->first();

            $user = User::create([
                'name' => ucwords($request->input('name')),
                'email' => $request->input('email'),
                'password' => $request->input('password'),
                'phone' => $request->input('phone'),
                'address' => $request->input('address') ?? null,
                'status' => 0, // Inactive until OTP is verified
            ]);
            if ($role) {
                $user->assignRole($role);
            }
            if ($photo) {
                $user->photo_url = $photo;
            }
            $user->saveQuietly();

            $code = rand(100000, 999999);
            DB::table('user_codes')->updateOrInsert(
                ['user_id' => $user->id],
                ['code' => $code]
            );

            $info = [
                'name' => $user->name,
                'code' => $code
            ];
            
            Mail::send('mail_password', $info, function ($message) use ($user) {
                $message->to($user->email, 'A2P Realtech Pvt. Ltd.')
                    ->subject('OTP for Account Verification');
                $message->from('a2prealtechpvtltd@gmail.com', 'A2P Realtech Pvt. Ltd.');
            });

            DB::commit();

            session(['register_user_id' => $user->id, 'register_email' => $user->email]);
            return redirect()->route('register.otp.form');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()])->withInput();
        }
    }

    public function showRegisterOtpForm()
    {
        if (!session()->has('register_user_id')) {
            return redirect()->route('register');
        }
        return view('signin.register_otp');
    }

    public function verifyRegisterOtp(Request $request)
    {
        $request->validate(['otp' => 'required']);
        $userId = session('register_user_id');
        
        $data = DB::table('user_codes')->where('user_id', $userId)->where('code', $request->otp)->first();
        
        if ($data) {
            // Do NOT activate the user here. Admin must approve them.
            DB::table('user_codes')->where('user_id', $userId)->update(['code' => '0']);
            session()->forget(['register_user_id', 'register_email']);
            
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false, 'message' => 'Invalid OTP.'], 400);
    }

    public function myAccount()
    {
        return view('myaccount.index');
    }

    // Show login OTP form
    public function showLoginOtpForm()
    {
        if (!session()->has('login_user_id')) {
            return redirect()->route('signin.index')->with('error', 'No pending login found.');
        }
        return view('auth.login_otp');
    }

    // Verify login OTP
    public function verifyLoginOtp(Request $request)
    {
        $request->validate(['otp' => 'required']);
        if ($request->otp == session('login_otp')) {
            $userId = session('login_user_id');
            // Log the user in
            Auth::loginUsingId($userId);
            // Regenerate session
            $request->session()->regenerate();
            // Clear OTP session data
            session()->forget(['login_user_id', 'login_otp']);
            return redirect()->intended('/')->with('success', 'Login successful.');
        }
        return back()->with('error', 'Invalid OTP.');
    }

    public function updateMyPersonalDetails(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'email',
            'photo' => 'image|max:1999',
        ]);

        $user = User::findorfail($id);

        $user->update([
            'name' => ucwords($request->input('name')),
            'email' => $request->input('email'),
        ]);

        // handle image if its present
        if ($request->hasFile('photo')) {
            // delete old photo if present
            if($user->photo_url !== null) {
                $file_path = public_path('/storage/galeryImages/' . $user->photo_url);
                @unlink($file_path);
            }

            // now add new photo
            $image = $request->file('photo');
            $fileNameToStore = time() . '_' . $image->getClientOriginalName();
            $destinationPath = public_path('/storage/galeryImages/');
            $image->move($destinationPath, $fileNameToStore);
            
            $user->photo_url = $fileNameToStore;
            $user->saveQuietly();
        }

        return back()->with('success', 'You have successfully changed your personal details.');
    }

    public function changeMyPassword(Request $request, $id)
    {
        $this->validate($request, [
            'current_password' => 'required|current_password',
            'password' => 'required|confirmed',
        ]);
		$code = rand(100000,999999);
		$code_admin = rand(100000,999999);
		$insert_update  = DB::table('user_codes')
			->updateOrInsert(
				['user_id' => Auth::user()->id],
				['code' => $code,'admin_code'=>$code_admin]
			);
        $user = User::findorfail($id);
        /* */
		$info = array(
					'name' => $user->name,
					'code' => $code
				);
		$info_1 = array(
					'name' => 'Admin',
					'username' => $user->name,
					'code' => $code_admin
				);
		Mail::send( 'mail_password', $info, function ($message)
		{
			$message->to(Auth::user()->email, 'A2P Realtech Pvt. Ltd.')
				->subject('2 Factor Authentication for change password');
			$message->from('a2prealtechpvtltd@gmail.com', 'A2P Realtech Pvt. Ltd.');
		});
		/* $admin = User::findorfail(1);
		$a_email= $admin->email; */
		Mail::send( 'mail_password_admin', $info_1, function ($message)
		{
			$message->to('adminrealtech@yopmail.com', 'A2P Realtech Pvt. Ltd.')
				->subject('2 Factor Authentication for change password');
			$message->from('a2prealtechpvtltd@gmail.com', 'A2P Realtech Pvt. Ltd.');
		});
		$password= $request->password;
		return view('myaccount.2fa', compact('password'));


       // return back()->with('success', 'You have successfully changed your password.');
    }
	 public function two_factor_auth(Request $request)
    {
        if($request->code){
			$data = DB::select('select * from user_codes where user_id ="'.Auth::user()->id.'" and code = "'.$request->code.'"');
			if($data){
				return redirect()->intended('/');
			}else{
				return back()->withErrors([
					'code' => 'The provided verification code do not match our records.',
				]);
			}
		}
		
        return view('signin.2fa');
    }
	 public function two_factor_auth_pwd(Request $request)
    {
        if($request->code && $request->admin_code){
			$data = DB::select('select * from user_codes where user_id ="'.Auth::user()->id.'" and code = "'.$request->code.'" and admin_code ="'.$request->admin_code.'"');
			if($data){
				//echo "<pre>";print_r($request->input('password'));exit;
				$user = User::findorfail(Auth::user()->id);
				$user->password = $request->input('password');
				$user->save();
				$insert_update  = DB::table('notifications')
				->updateOrInsert(
					['user_id' => Auth::user()->id],
					['notification_text' => "Your Password has been changed successfully",'status'=>0]
				);
				$user = User::findorfail(Auth::user()->id);
				$insert_update  = DB::table('notifications')
				->updateOrInsert(
					['user_id' => 1],
					['notification_text' => $user->name ." has changed password successfully",'status'=>0]
				);
				//echo "<pre>";print_r($user->save());exit;
				  return view('myaccount.index');
			}else{
				return back()->withErrors([
					'code' => 'The provided verification code do not match our records.',
				]);
			}
		}
		
        return view('myaccount.2fa');
    } 
	public function notification(Request $request)
    {
		//echo $request->mark_read;exit;
		if(isset($request->mark_read)){
			//echo Auth::user()->id;exit;
			$update = DB::table('notifications')
				->where('user_id' , Auth::user()->id)
				->update(['status'=>1]);
		}
        $notifications = DB::select('SELECT * FROM notifications where status = 0 and   user_id = "'.Auth::user()->id.'" Order by id desc');
        return view('myaccount.navbar-notification', compact('notifications'));
    }
	public function send_notification(Request $request)
    {
		$user =user::findorfail(auth()->user()->id);
		$notification =$request->notification;
		//echo "<pre>";print_r($notification['text']);exit;
		$insert_update  = DB::table('notifications')
				->Insert(
					['user_id' => 1,'notification_text' => 'New Notification "'.$notification['text']. '" has been sent  by '.$user->name,"status"=>0],
				);
        $notifications = DB::select('SELECT * FROM notifications where status = 0 and   user_id = "'.Auth::user()->id.'" Order by id desc');
        return view('myaccount.navbar-notification', compact('notifications'));
    }

    public function sendPasswordOtp(Request $request)
    {
        $code = rand(100000, 999999);
        $user = auth()->user();
        
        DB::table('user_codes')->updateOrInsert(
            ['user_id' => $user->id],
            ['code' => $code]
        );
        
        $info = [
            'name' => $user->name,
            'code' => $code
        ];
        
        Mail::send('mail_password', $info, function ($message) use ($user) {
            $message->to($user->email, 'A2P Realtech Pvt. Ltd.')
                ->subject('OTP to change your password');
            $message->from('a2prealtechpvtltd@gmail.com', 'A2P Realtech Pvt. Ltd.');
        });
        
        session()->put('password_otp_sent', true);
        return back()->with('success', 'An OTP has been sent to your email. Please verify it to change your password.');
    }

    public function changePasswordWithOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);
        
        $user_id = auth()->user()->id;
        $data = DB::select('select * from user_codes where user_id ="'.$user_id.'" and code = "'.$request->otp.'"');
        
        if ($data) {
            $user = User::findOrFail($user_id);
            $user->password = $request->password;
            $user->save();
            
            // clear the code to prevent reuse
            DB::table('user_codes')->where('user_id', $user_id)->update(['code' => '0']);
            session()->forget('password_otp_sent');
            
            DB::table('notifications')->updateOrInsert(
                ['user_id' => $user_id],
                ['notification_text' => 'Your Password has been changed successfully via OTP.', 'status' => 0]
            );
            
            return redirect(route('myaccount.index'))->with('success', 'Your password has been changed successfully.');
        } else {
            return back()->withErrors(['otp' => 'The provided OTP is incorrect.']);
        }
    }

    public function forgotPasswordIndex()
    {
        return view('signin.forgot-password');
    }

    public function sendForgotPasswordOts(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'We could not found any user with this email address.']);
        }

        $code_user = rand(100000, 999999);
        $code_tl = rand(100000, 999999);
        $code_admin = rand(100000, 999999);

        DB::table('user_codes')->updateOrInsert(
            ['user_id' => $user->id],
            [
                'code' => $code_user,
                'tl_code' => $code_tl,
                'admin_code' => $code_admin
            ]
        );

        // Find Team Lead email correctly (parent_id is the user_id of the parent)
        $tl_email = null;
        $teamMember = \App\Models\TeamMember::where('user_id', $user->id)->first();
        if ($teamMember && $teamMember->parent_id) {
            $tl_user = User::find($teamMember->parent_id);
            if ($tl_user) {
                $tl_email = $tl_user->email;
            }
        }

        // Send OTP to User
        Mail::send('mail_password', ['name' => $user->name, 'code' => $code_user], function ($message) use ($user) {
            $message->to($user->email)->subject('Password Reset OTP - User Validation');
        });

        // Send OTP to Team Lead
        if ($tl_email) {
            Mail::send('mail_password', ['name' => 'Team Leader', 'code' => $code_tl], function ($message) use ($tl_email, $user) {
                $message->to($tl_email)->subject('Password Reset OTP - Team Lead Validation (For '.$user->name.')');
            });
        }

        // Send OTP to ALL Admins
        $adminEmails = User::role('Admin')->pluck('email')->toArray();
        if(empty($adminEmails)) $adminEmails = ['adminrealtech@yopmail.com']; // fallback
        
        foreach($adminEmails as $adminEmail) {
            Mail::send('mail_password', ['name' => 'Admin', 'code' => $code_admin], function ($message) use ($adminEmail, $user) {
                $message->to($adminEmail)->subject('Password Reset OTP - Admin Validation (For '.$user->name.')');
            });
        }

        session([
            'fp_user_id' => $user->id,
            'fp_email' => $user->email,
            'tl_email' => $tl_email
        ]);

        return back()->with('success', 'OTPs have been successfully sent.');
    }

    public function resendForgotPasswordOtp(Request $request)
    {
        $request->validate(['target' => 'required|in:user,tl,admin']);

        if (!session('fp_user_id')) {
            return redirect()->route('signin.index')->withErrors(['session' => 'Session expired. Please try again.']);
        }

        $user_id = session('fp_user_id');
        $user = User::findOrFail($user_id);
        
        $target = $request->target;
        $new_code = rand(100000, 999999);
        
        if ($target === 'user') {
            DB::table('user_codes')->where('user_id', $user_id)->update(['code' => $new_code]);
            Mail::send('mail_password', ['name' => $user->name, 'code' => $new_code], function ($message) use ($user) {
                $message->to($user->email)->subject('Password Reset OTP (Resent) - User Validation');
            });
            return back()->with('success', 'OTP successfully resent to Your Email.');
        } 
        elseif ($target === 'tl') {
            if (!session('tl_email')) return back()->withErrors(['tl' => 'No Team Leader email found to resend to.']);
            DB::table('user_codes')->where('user_id', $user_id)->update(['tl_code' => $new_code]);
            Mail::send('mail_password', ['name' => 'Team Leader', 'code' => $new_code], function ($message) use ($user) {
                $message->to(session('tl_email'))->subject('Password Reset OTP (Resent) - Team Lead Validation');
            });
            return back()->with('success', 'OTP successfully resent to Team Leader.');
        } 
        elseif ($target === 'admin') {
            DB::table('user_codes')->where('user_id', $user_id)->update(['admin_code' => $new_code]);
            $adminEmails = User::role('Admin')->pluck('email')->toArray();
            if(empty($adminEmails)) $adminEmails = ['adminrealtech@yopmail.com'];
            foreach($adminEmails as $adminEmail) {
                Mail::send('mail_password', ['name' => 'Admin', 'code' => $new_code], function ($message) use ($adminEmail, $user) {
                    $message->to($adminEmail)->subject('Password Reset OTP (Resent) - Admin Validation');
                });
            }
            return back()->with('success', 'OTP successfully resent to Admin.');
        }
        
        return back();
    }

    public function verifyForgotPasswordAndReset(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed|min:6',
        ]);

        if (!session('fp_user_id')) {
            return redirect()->route('signin.index')->withErrors(['session' => 'Session expired. Please try again.']);
        }

        $user_id = session('fp_user_id');
        $dbCodes = DB::table('user_codes')->where('user_id', $user_id)->first();

        if (!$dbCodes) {
            return back()->withErrors(['otp' => 'No active OTP request found.']);
        }

        $matchedCount = 0;

        $isValidOtp = function($input, $dbValue) {
            return !empty($input) && !empty($dbValue) && $dbValue !== '0' && (string)$input === (string)$dbValue;
        };

        if ($isValidOtp($request->otp_user, $dbCodes->code)) {
            $matchedCount++;
        }
        if ($isValidOtp($request->otp_tl, $dbCodes->tl_code)) {
            $matchedCount++;
        }
        if ($isValidOtp($request->otp_admin, $dbCodes->admin_code)) {
            $matchedCount++;
        }

        if ($matchedCount >= 2) {
            // Validated! Reset password
            $user = User::findOrFail($user_id);
            $user->password = $request->password;
            $user->save();

            // Clear codes and session
            DB::table('user_codes')->where('user_id', $user_id)->update([
                'code' => '0',
                'tl_code' => '0',
                'admin_code' => '0'
            ]);
            session()->forget(['fp_user_id', 'fp_email', 'tl_email']);

            $request->session()->regenerate();
            Auth::loginUsingId($user_id);

            return redirect()->intended('/')->with('success', 'Your password has been reset successfully and you are logged in.');
        } else {
            return back()->withErrors(['otp' => 'You must provide at least 2 correct OTPs to verify (You provided '.$matchedCount.' correct).']);
        }
    }
}
