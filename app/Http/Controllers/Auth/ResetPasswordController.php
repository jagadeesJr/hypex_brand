<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
     */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    public function resetPassword(Request $request)
    {
        try {

            // Validate input
            $validator = Validator::make($request->all(), [
                'token' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:8|confirmed',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->errors());
                //  return Helpers::ifValidatorFails($validator);
            }

            // Determine the broker based on the user type
            $broker = $request->userType === 'creator_users' ? 'creator_users' : 'brand_users';
            // Reset the password
            $status = Password::broker($broker)->reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->password = bcrypt($password);
                    $user->save();
                }
            );
            if ($status === Password::PASSWORD_RESET) {
                return redirect()->back()->with('success', 'Password has been reset');
            } else {
                return redirect()->back()->with('error', 'Failed to reset password');
            }
        } catch (Exception $e) {
            return Helpers::catchResponse($e);
        }
    }
}
