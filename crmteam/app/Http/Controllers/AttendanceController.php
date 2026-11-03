<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class AttendanceController extends Controller
{
    /**
     * Ensure the user has verified OTP for attendance access.
     * If not verified, generate OTP, email it, and redirect to OTP form.
     */
    private function ensureAttendanceVerified(string $intendedRoute)
    {
        if (!session()->has('attendance_verified')) {
            $otp = rand(100000, 999999);
            // Store OTP and intended route in session
            session([
                'attendance_otp'       => $otp,
                'attendance_intended'  => $intendedRoute,
                'attendance_otp_email' => Auth::user()->email,
            ]);

            $userEmail = Auth::user()->email;
            $userName  = Auth::user()->name;

            Mail::raw(
                "Hello $userName,\n\nYour OTP for Attendance Access is: $otp\n\nThis OTP is valid for this session only.",
                function ($message) use ($userEmail, $userName) {
                    $message->to($userEmail, $userName)
                            ->subject('Attendance Access OTP');
                }
            );

            return redirect()->route('attendance.verify_otp_form')
                             ->with('success', 'OTP has been sent to your email.');
        }
        return null;
    }

    public function index(Request $request)
    {
        // Ensure OTP verification before showing attendance list
        if ($redirect = $this->ensureAttendanceVerified('attendance.index')) {
            return $redirect;
        }

        $user = Auth::user();
        $isAdmin = $user->hasRole('Admin');

        $query = Attendance::with('user');

        if ($isAdmin) {
            // Admin can filter by any user
            if ($request->has('user_id') && $request->user_id != '') {
                $query->where('user_id', $request->user_id);
            }
        } else {
            // Non-admin can only see their own records
            $query->where('user_id', $user->id);
        }

        if ($request->has('year') && $request->year != '') {
            $query->whereYear('date', $request->year);
        }
        if ($request->has('month') && $request->month != '') {
            $query->whereMonth('date', $request->month);
        }

        $attendances = $query->orderBy('date', 'desc')->get();
        $users = $isAdmin ? User::all() : collect([]);

        // Calculations — only computed for Admin
        $percentagePresent = 0;
        $percentageAbsent = 0;
        $percentageLeave = 0;
        $percentageShortTime = 0;

        if ($isAdmin) {
            $totalPresent = $attendances->where('attendance_type', 'Present')->count();
            $totalAbsent = $attendances->where('attendance_type', 'Absent')->count();
            $totalLeave = $attendances->where('attendance_type', 'Leave')->count();
            $totalShortTime = $attendances->where('attendance_type', 'Short Time')->count();
            $totalRecords = $attendances->count();

            $percentagePresent = $totalRecords > 0 ? round(($totalPresent / $totalRecords) * 100, 2) : 0;
            $percentageAbsent = $totalRecords > 0 ? round(($totalAbsent / $totalRecords) * 100, 2) : 0;
            $percentageLeave = $totalRecords > 0 ? round(($totalLeave / $totalRecords) * 100, 2) : 0;
            $percentageShortTime = $totalRecords > 0 ? round(($totalShortTime / $totalRecords) * 100, 2) : 0;
        }

        return view('attendance.index', compact('attendances', 'users', 'percentagePresent', 'percentageAbsent', 'percentageLeave', 'percentageShortTime', 'isAdmin'));
    }

    public function create()
    {
        // Ensure OTP verification before showing create form
        if ($redirect = $this->ensureAttendanceVerified('attendance.create')) {
            return $redirect;
        }
        $users = User::all();
        return view('attendance.create', compact('users'));
    }

    public function store(Request $request)
    {
        if (empty($request->location_in) && empty($request->location_out)) {
            return back()->withInput()->withErrors(['location' => 'Location captures are mandatory. Please click Mark IN or Mark OUT and allow location access.']);
        }

        $data = $request->all();
        $data['days'] = date('l', strtotime($request->date));

        // Directly save attendance — module-level OTP was already verified on entry
        Attendance::create($data);

        return redirect()->route('attendance.index')->with('success', 'Attendance recorded successfully.');
    }

    public function verifyOtpForm()
    {
        if (!session()->has('attendance_data') && !session()->has('attendance_intended')) {
            return redirect()->route('attendance.index')->with('error', 'No pending verification found.');
        }
        return view('attendance.verify_otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required']);

        if ($request->otp == session('attendance_otp')) {
            // Mark attendance access as verified for this session
            session(['attendance_verified' => true]);

            // Clear OTP and intended route from session
            $intended = session('attendance_intended', 'attendance.index');
            session()->forget(['attendance_otp', 'attendance_intended']);

            // If there is pending attendance data (from store flow), save it
            if (session()->has('attendance_data')) {
                $data = session('attendance_data');
                Attendance::create($data);
                session()->forget('attendance_data');
                return redirect()->route('attendance.index')->with('success', 'Attendance recorded successfully.');
            }

            // Redirect to originally intended attendance route
            return redirect()->route($intended)->with('success', 'OTP verified. You can now access the attendance module.');
        }

        return back()->with('error', 'Invalid OTP. Please try again.');
    }

    public function edit($id)
    {
        // Ensure OTP verification before editing attendance
        if ($redirect = $this->ensureAttendanceVerified('attendance.edit')) {
            return $redirect;
        }
        $attendance = Attendance::findOrFail($id);
        $users = User::all();
        return view('attendance.edit', compact('attendance', 'users'));
    }

    public function update(Request $request, $id)
    {
        if (empty($request->location_in) && empty($request->location_out)) {
            return back()->withInput()->withErrors(['location' => 'Location captures are mandatory. Please click Mark IN or Mark OUT and allow location access.']);
        }

        $attendance = Attendance::findOrFail($id);
        $data = $request->all();
        $data['days'] = date('l', strtotime($request->date));
        $attendance->update($data);
        return redirect()->route('attendance.index')->with('success', 'Attendance updated successfully.');
    }

    public function destroy($id)
    {
        Attendance::findOrFail($id)->delete();
        return back()->with('success', 'Attendance deleted successfully.');
    }
}
