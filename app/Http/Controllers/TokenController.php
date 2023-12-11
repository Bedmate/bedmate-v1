<?php

namespace App\Http\Controllers;

use App\Mail\OtpVerificationMail;
use App\Models\ResetToken;
use App\Models\User;
use App\Notifications\PasswordResetNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class TokenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function send(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|exists:users,email'
            ]);

            $user = User::where('email', $request->email)->first();
          
            if($user && $request->email == auth()->user()->email) {
                $token = strtoupper(Str::random(6));
                ResetToken::where('email', $request->email)->delete();

                ResetToken::create([
                    'email' => $request->email,
                    'token' => $token
                ]);

                Mail::to($request->email)->send(new OtpVerificationMail($token, $user->name));

                return get_success_response(['msg' => 'Please check your email for your reset token']);
            }
            return get_error_response(['error' => "User not found"]);
        } catch (\Throwable $th) {
            return get_error_response(['error' => $th->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function verify(Request $request)
    {
        try {
            $request->validate([
                'token' => 'required'
            ]);

            // check if token exists
          	$token = $request->token;
          	$user  = $request->user();
            $tokenExists = ResetToken::where(['email' => $user->email, 'token' => $token])->first();
            if ($tokenExists && $tokenExists->delete()) {
                return get_success_response(['succes' => "Token verified successfully"]);
            }
            return get_error_response(['error' => "Invalid token provided"], 401);
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
