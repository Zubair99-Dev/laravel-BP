<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    
    public function store(LoginRequest $request): RedirectResponse
    {
        // Check if captcha is enabled
        $captchaEnabled = DB::table('settings')->where('key', 'captcha_enabled')->value('value');
    
        if ($captchaEnabled == '1') {
            // Validate the captcha with a custom error message from the configuration file
            $request->validate([
                'captcha' => 'required|captcha',
            ], [
                'captcha.captcha' => config('captcha.error_message'),
            ]);
        }
    
        // Authenticate the user
        $request->authenticate();
    
        // Regenerate session after successful authentication
        $request->session()->regenerate();
    
        return redirect()->intended(route('dashboard', absolute: false));
    }
    
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
