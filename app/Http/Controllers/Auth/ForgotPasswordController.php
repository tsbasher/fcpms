<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
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
     * Display the "forgot password" link request form.
     *
     * @param  string  $portal  user|admin
     */
    public function showLinkRequestForm(string $portal)
    {
        $this->setPortal($portal);

        return view($this->viewPrefix . '.auth.passwords.email');
    }

    /**
     * Send a reset link to the given email address.
     *
     * @param  string  $portal  user|admin
     */
    public function sendResetLinkEmail(Request $request, string $portal)
    {
        $this->setPortal($portal);

        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::broker($this->broker)->sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
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