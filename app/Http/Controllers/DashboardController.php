<?php

namespace App\Http\Controllers;

use App\Models\LoginAttempt;
use Illuminate\Http\Request;


class DashboardController extends Controller
{
    public function redirect()
    {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('staff.dashboard');
    }

    public function admin()
    {
        // Count failed login attempts in the last 15 minutes.
        $failedAttempts = LoginAttempt::where('successful', false)
            ->where('created_at', '>=', now()->subMinutes(15))
            ->count();

        return view('admin.dashboard', compact('failedAttempts'));
    }

    public function staff()
    {
        return view('staff.dashboard');
    }
}
