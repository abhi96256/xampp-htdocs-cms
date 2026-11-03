<?php

namespace App\Http\Controllers;

use App\Mail\AgreementOtpMail;
use App\Mail\DocumentUploadMail;
use App\Models\User;
use App\Models\UserAgreement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserAgreementController extends Controller
{
    // ─── Admin: List all agreements ──────────────────────────────────
    public function index()
    {
        $agreements = UserAgreement::with('user')->latest()->paginate(20);
        return view('agreements.index', compact('agreements'));
    }

    // ─── Admin: Show "Send Agreement" form ───────────────────────────
    public function create()
    {
        $users = User::where('status', 1)->orderBy('name')->get();
        return view('agreements.create', compact('users'));
    }

    // ─── Admin: Send Agreement + OTP Email ───────────────────────────
    public function send(Request $request)
    {
        $request->validate([
            'user_id'        => 'required|exists:users,id',
            'agreement_type' => 'required|in:salary,commission,channel_partner',
            'document'       => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:10240',
        ]);

        $user = User::findOrFail($request->user_id);

        if (!$user->email) {
            return back()->withErrors(['user_id' => 'Selected user does not have an email address.']);
        }

        // Generate OTP and token
        $otp   = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        $token = Str::random(60);

        $agreementData = [
            'user_id'            => $user->id,
            'agreement_type'     => $request->agreement_type,
            'otp_code'           => $otp,
            'otp_expires_at'     => Carbon::now()->addHours(24),
            'verification_token' => $token,
            'status'             => 'pending',
        ];

        if ($request->hasFile('document')) {
            $file     = $request->file('document');
            $filename = 'agreement_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path     = $file->storeAs('agreement_docs', $filename, 'public');
            
            $agreementData['uploaded_document'] = $path;
            $agreementData['document_sent_at']  = Carbon::now();
        }

        $agreement = UserAgreement::create($agreementData);

        $signUrl = route('agreements.verify.page', ['token' => $token]);

        // Send Email
        Mail::to($user->email)->send(new AgreementOtpMail($agreement, $signUrl, $user->name));

        return redirect()->route('agreements.index')
                         ->with('success', "Agreement sent to {$user->name} ({$user->email}) successfully!");
    }

    // ─── Public: Show signing page (no auth required) ────────────────
    public function verifyPage($token)
    {
        $agreement = UserAgreement::with('user')
            ->where('verification_token', $token)
            ->where('status', 'pending')
            ->firstOrFail();

        // Check if expired
        if (Carbon::now()->isAfter($agreement->otp_expires_at)) {
            $agreement->update(['status' => 'expired']);
            return view('agreements.expired');
        }

        return view('agreements.sign', compact('agreement', 'token'));
    }

    // ─── Public: Submit OTP to digitally sign ────────────────────────
    public function submitOtp(Request $request, $token)
    {
        $request->validate([
            'otp'       => 'required|digits:6',
            'signature' => 'required', // base64 string
        ]);

        $agreement = UserAgreement::with('user')
            ->where('verification_token', $token)
            ->where('status', 'pending')
            ->firstOrFail();

        // Check expiry
        if (Carbon::now()->isAfter($agreement->otp_expires_at)) {
            $agreement->update(['status' => 'expired']);
            return back()->withErrors(['otp' => 'This OTP has expired. Please ask admin to resend the agreement.']);
        }

        // Check OTP match
        if ($request->otp !== $agreement->otp_code) {
            return back()->withErrors(['otp' => 'Invalid OTP. Please try again.']);
        }

        // Mark as signed
        $agreement->update([
            'status'     => 'signed',
            'ip_address' => $request->ip(),
            'signature'  => $request->signature,
            'signed_at'  => Carbon::now(),
        ]);

        return view('agreements.success', compact('agreement'));
    }

    // ─── Admin: Resend agreement ──────────────────────────────────────
    public function resend($id)
    {
        $old = UserAgreement::findOrFail($id);
        $old->update(['status' => 'expired']);

        $user  = $old->user;
        $otp   = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        $token = Str::random(60);

        $agreement = UserAgreement::create([
            'user_id'            => $user->id,
            'agreement_type'     => $old->agreement_type,
            'otp_code'           => $otp,
            'otp_expires_at'     => Carbon::now()->addHours(24),
            'verification_token' => $token,
            'status'             => 'pending',
        ]);

        $signUrl = route('agreements.verify.page', ['token' => $token]);
        Mail::to($user->email)->send(new AgreementOtpMail($agreement, $signUrl, $user->name));

        return back()->with('success', "Agreement resent to {$user->name} successfully!");
    }

    // ─── Admin/Public: Print/View Signed Agreement Document ───────────
    public function printDoc($id)
    {
        $agreement = UserAgreement::with('user')->findOrFail($id);
        
        if ($agreement->status !== 'signed') {
            return redirect()->route('agreements.index')->withErrors(['msg' => 'You can only view/print agreements that have been signed.']);
        }
        
        return view('agreements.print', compact('agreement'));
    }

    // ─── Admin: Upload Document & Send to User Email ──────────────────
    public function uploadDocument(Request $request, $id)
    {
        $request->validate([
            'document'      => 'required|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:10240',
            'upload_note'   => 'nullable|string|max:1000',
            'notify_email'  => 'required|email',
        ]);

        $agreement = UserAgreement::with('user')->findOrFail($id);

        // Store file
        $file     = $request->file('document');
        $filename = 'agreement_' . $id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path     = $file->storeAs('agreement_docs', $filename, 'public');

        // Save to DB
        $agreement->update([
            'uploaded_document' => $path,
            'upload_note'       => $request->upload_note,
            'document_sent_at'  => Carbon::now(),
        ]);

        // Build download URL
        $downloadUrl = route('agreements.document.download', $id);

        // Send email
        try {
            Mail::to($request->notify_email)
                ->send(new DocumentUploadMail($agreement, $request->upload_note ?? '', $downloadUrl));
            $sent = true;
        } catch (\Exception $e) {
            $sent = false;
        }

        $msg = $sent
            ? "Document uploaded and sent to {$request->notify_email} successfully!"
            : "Document uploaded but email could not be sent. Please check mail config.";

        return back()->with('success', $msg);
    }

    // ─── Download Uploaded Document ───────────────────────────────────
    public function downloadDocument($id)
    {
        $agreement = UserAgreement::findOrFail($id);

        if (!$agreement->uploaded_document) {
            abort(404, 'No document found.');
        }

        $filePath = storage_path('app/public/' . $agreement->uploaded_document);

        if (!file_exists($filePath)) {
            abort(404, 'File not found on server.');
        }

        return response()->download($filePath, basename($filePath));
    }

    // ─── View Uploaded Document ───────────────────────────────────────
    public function viewDocument($id)
    {
        $agreement = UserAgreement::findOrFail($id);

        if (!$agreement->uploaded_document) {
            abort(404, 'No document found.');
        }

        $filePath = storage_path('app/public/' . $agreement->uploaded_document);

        if (!file_exists($filePath)) {
            abort(404, 'File not found on server.');
        }

        return response()->file($filePath);
    }

    // ─── Admin: Delete Agreement ──────────────────────────────────────
    public function destroy($id)
    {
        $agreement = UserAgreement::findOrFail($id);

        // Delete uploaded document from storage if exists
        if ($agreement->uploaded_document) {
            Storage::disk('public')->delete($agreement->uploaded_document);
        }

        $agreement->delete();

        return redirect()->route('agreements.index')
                         ->with('success', 'Agreement deleted successfully.');
    }
}
