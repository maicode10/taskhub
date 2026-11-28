<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\LoginAttempt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // how many wrong passwords before we lock the account
    private $maxAttempts = 5;

    // how long the account stays locked, in minutes
    private $lockMinutes = 15;


    // ---------- show the login page ----------
    public function showLogin()
    {
        return view('auth.login');
    }


    // ---------- process the login ----------
    public function login(Request $request)
    {
        // THIS PART IS FOR INPUT VALIDATION
        // it checks the inputs before we touch the database
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // look for the user with that email
        // Eloquent uses prepared statements, so SQL injection cannot work here
        $user = User::where('email', $request->email)->first();

        // if the email does not exist, record it and stop
        if (!$user) {
            $this->recordAttempt($request->email, false);
            return back()->with('error', 'Wrong email or password.');
        }

        // THIS PART IS FOR THE LOCKOUT
        // if the account is still locked, we do not even check the password
        if ($user->isLocked()) {
            $minutes = now()->diffInMinutes($user->locked_until) + 1;
            $this->recordAttempt($user->email, false);
            return back()->with('error', "This account is locked. Try again in {$minutes} minute(s).");
        }

        // THIS PART IS FOR THE PASSWORD CHECKING
        // Hash::check hashes what the user typed and compares it with the
        // hash saved in the database. we never compare plain passwords.
        if (!Hash::check($request->password, $user->password)) {

            // add 1 to the wrong-password counter
            $user->failed_attempts++;

            // if he reached the limit, lock the account
            if ($user->failed_attempts >= $this->maxAttempts) {
                $user->locked_until    = now()->addMinutes($this->lockMinutes);
                $user->failed_attempts = 0;
                $user->save();

                $this->recordAttempt($user->email, false);
                $this->writeLog('ACCOUNT_LOCKED', "Account {$user->email} was locked after {$this->maxAttempts} failed logins", $user->id);

                return back()->with('error', "Too many wrong passwords. This account is locked for {$this->lockMinutes} minutes.");
            }

            $user->save();
            $this->recordAttempt($user->email, false);

            // tell him how many tries are left
            $left = $this->maxAttempts - $user->failed_attempts;
            return back()->with('error', "Wrong email or password. {$left} try/tries left before lockout.");
        }

        // if the account was turned off by the admin
        if (!$user->is_active) {
            return back()->with('error', 'This account is disabled. Please contact the admin.');
        }

        // ---------- the password is correct ----------

        // clear the counter so old mistakes do not add up
        $user->failed_attempts = 0;
        $user->locked_until    = null;
        $user->save();

        // log him in
        Auth::login($user);

        // THIS PART IS FOR SESSION SECURITY
        // regenerate gives a brand new session id, so an old stolen
        // session id cannot be reused. this stops session fixation.
        $request->session()->regenerate();

        $this->recordAttempt($user->email, true);
        $this->writeLog('LOGIN', "{$user->name} logged in", $user->id);

        return redirect('/dashboard');
    }


    // ---------- logout ----------
    public function logout(Request $request)
    {
        $this->writeLog('LOGOUT', Auth::user()->name . ' logged out', Auth::id());

        Auth::logout();

        // delete the old session completely
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out.');
    }


    // ---------- show the change password page ----------
    public function showChangePassword()
    {
        return view('auth.change-password');
    }


    // ---------- save the new password ----------
    public function changePassword(Request $request)
    {
        // THIS PART IS FOR THE PASSWORD RULES
        // regex explanation:
        //   (?=.*[a-z])  must have a small letter
        //   (?=.*[A-Z])  must have a capital letter
        //   (?=.*[0-9])  must have a number
        //   (?=.*[\W])   must have a symbol like @ # $ !
        $request->validate([
            'current_password' => 'required',
            'new_password'     => [
                'required',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[\W]).+$/',
            ],
        ], [
            'new_password.regex' => 'Password must have an uppercase letter, a lowercase letter, a number, and a symbol.',
            'new_password.min'   => 'Password must be at least 8 characters.',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // check if the old password he typed is really his
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Your current password is wrong.');
        }

        // THIS PART IS FOR THE HASHING
        // the User model has 'password' => 'hashed', so Laravel
        // automatically turns this into a bcrypt hash before saving
        $user->password = $request->new_password;
        $user->save();

        $this->writeLog('PASSWORD_CHANGED', "{$user->name} changed his password", $user->id);

        return back()->with('success', 'Password changed successfully.');
    }


    // ---------- helper: save every login try ----------
    // this table is what the intrusion alert will read later
    private function recordAttempt(string $email, bool $successful)
    {
        LoginAttempt::create([
            'email'      => $email,
            'ip_address' => request()->ip(),
            'successful' => $successful,
        ]);
    }


    // ---------- helper: save to the audit log ----------
    private function writeLog(string $action, string $description, ?int $userId = null)
    {
        AuditLog::create([
            'user_id'     => $userId,
            'action'      => $action,
            'description' => $description,
            'ip_address'  => request()->ip(),
        ]);
    }
}