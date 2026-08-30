<?php

namespace App\Http\Controllers\Auth;

use App\Helper\LoginThrottle;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Package;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    // use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected function redirectTo(Request $request)
    {
        if ($request->auth()->guard('admin'))
            return route('admin.home');
        else
            return route('user.home');
    }

    //= '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('guest')->except('logout');
        // $this->middleware('auth')->only('logout');
    }


    public function user_login()
    {
        $user=Admin::first();
        // dd(array_key_exists('created_by', $user->getAttributes()) );
        
        if (Auth::guard('web')->check())
            return redirect()->route('user.home');
        return view('backend.user.auth.login');
    }

    public function user_login_post(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $check = $request->all();

        $user = User::where('email', $check['email'])->first();
        if (!$user) {
            return redirect()->back()->with('error', 'No account found with this email');
        }

        if (LoginThrottle::isLocked($user)) {
            $minutes = LoginThrottle::lockMinutesRemaining($user);
            return redirect()->back()->with('error', "Too many failed attempts. Your account is locked, please try again in {$minutes} minute(s).");
        }

        if (!$user->is_active) {
            return redirect()->back()->with('error', 'Your account is deactivated. Please contact admin.');
        }

        $data = [
            'email' => $check['email'],
            'password' => $check['password'],
        ];
        if (Auth::guard('web')->attempt($data, $check['remember'] ?? false)) {
            LoginThrottle::reset($user);
            return redirect()->route('user.home');
        }

        LoginThrottle::registerFailure($user);
        if (LoginThrottle::isLocked($user)) {
            $minutes = LoginThrottle::lockMinutesRemaining($user);
            return redirect()->back()->with('error', "Too many failed attempts. Your account is locked, please try again in {$minutes} minute(s).");
        }

        return redirect()->back()->with('error', 'Incorrect password');
    }


    public function user_logout()
    {
        Auth::guard('web')->logout();
        return redirect()->route('user.login')->with('success', 'Logged out successfully');
    }
    public function admin_login()
    {
        if (Auth::guard('admin')->check())
            return redirect()->route('admin.home');
        return view('backend.admin.auth.login');
    }

    public function admin_login_post(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'project_code' => 'required|string|max:255',
        ]);

        $check = $request->all();

        $admin = Admin::with('projects')->where('email', $check['email'])->first();
        if (!$admin) {
            return redirect()->back()->with('error', 'Invalid credentials');
        }

        if (LoginThrottle::isLocked($admin)) {
            $minutes = LoginThrottle::lockMinutesRemaining($admin);
            return redirect()->back()->with('error', "Too many failed attempts. Your account is locked, please try again in {$minutes} minute(s).");
        }

        if (!$admin->is_active) {
            return redirect()->back()->with('error', 'Your account is deactivated. Please contact admin.');
        }

        $data = [
            'email' => $check['email'],
            'password' => $check['password'],
        ];
        if (Auth::guard('admin')->attempt($data, $check['remember'] ?? false)) {
            $project = $admin->projects->first(function ($project) use ($check) {
                return strtoupper($project->code) === strtoupper($check['project_code']);
            });

            if ($project) {
                LoginThrottle::reset($admin);
                $this->finalize_admin_login($check['project_code']);
                return redirect()->route('admin.home');
            }

            Auth::guard('admin')->logout();
            return redirect()->back()->with('error', 'You have no permission to this project. Please select the correct project code.');
        }

        LoginThrottle::registerFailure($admin);
        if (LoginThrottle::isLocked($admin)) {
            $minutes = LoginThrottle::lockMinutesRemaining($admin);
            return redirect()->back()->with('error', "Too many failed attempts. Your account is locked, please try again in {$minutes} minute(s).");
        }

        return redirect()->back()->with('error', 'Invalid credentials');
    }
    private function finalize_admin_login($project_code)
    {
        $project = Project::whereRaw('UPPER(code) = ?', [strtoupper($project_code)])->first();
        Admin::where('id', Auth::guard('admin')->user()->id)
            ->update([
                'project_id' => $project->id,
                'project_code' => $project->code,
            ]);
    }
    public function admin_logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login')->with('success', 'Logged out successfully');
    }
}
