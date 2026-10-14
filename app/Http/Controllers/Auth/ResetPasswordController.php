<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    /**
     * The password broker to use (users or admins).
     */
    protected string $broker = 'users';

    /**
     * The view prefix (backend.user or backend.admin).
     */
    protected string $viewPrefix = 'backend.user';

    /**
     * Display the password reset form.
     *
     * @param  string  $portal  user|admin
     */
    public function showResetForm(Request $request, string $portal, string $token = null)
    {
        $this->setPortal($portal);

        return view($this->viewPrefix . '.auth.passwords.reset', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Reset the given user's password.
     *
     * @param  string  $portal  user|admin
     */
    public function reset(Request $request, string $portal)
    {
        $this->setPortal($portal);

        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::broker($this->broker)->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill(['password' => $password])->save();
                if ($user->must_change_password) {
                    $user->forceFill(['must_change_password' => 0])->save();
                }
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route($this->broker === 'admins' ? 'admin.login' : 'user.login')->with('success', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Resolve broker + view prefix from the portal segment.
     */
    protected function setPortal(string $portal): void
    {
        if ($portal === 'admin') {
            $this->broker = 'admins';
            $this->viewPrefix = 'backend.admin';
        } else {
            $this->broker = 'users';
            $this->viewPrefix = 'backend.user';
        }
    }
}