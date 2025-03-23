<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use App\Helpers\adLDAP;

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
        if(env('APP_ENV') === 'local') {
            $request->authenticate();
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        $request->validate([
            'name' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // التحقق من وجود المستخدم في قاعدة البيانات المحلية
        $user = User::where('name', $request->name)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'name' => 'المستخدم غير مسجل',
            ]);
        }

        // مدير النظام تحقق محلي فقط
        if ($user->type === '0') {
            $request->authenticate();
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        try {
            // التحقق عبر LDAP للمستخدم
            $adldap = new adLDAP();
            if (!$adldap->authenticate($request->name, $request->password)) {
                throw ValidationException::withMessages([
                    'name' => 'خطأ في البيانات المدخلة.',
                ]);
            }

            Auth::login($user);

            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'name' => 'خطأ في البيانات المدخلة.',
            ]);
        }
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
