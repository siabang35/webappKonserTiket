<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\LoginRequest;
use App\Http\Requests\Admin\RegisterRequest;
use App\Services\AdminActivityLogService;
use Illuminate\Validation\ValidationException;

class AdminAuthController extends Controller
{
    protected $activityLogService;

    public function __construct(AdminActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    public function showLoginForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->validated();

            if (!Auth::guard('admin')->attempt($credentials, $request->remember)) {
                throw ValidationException::withMessages([
                    'email' => __('auth.failed'),
                ]);
            }

            $admin = Auth::guard('admin')->user();

            // Update last login
            $admin->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip()
            ]);

            // Log activity
            $this->activityLogService->log(
                'login',
                'Admin logged in successfully',
                $admin->id
            );

            return redirect()
                ->intended(route('admin.dashboard'))
                ->with('success', 'Welcome back, ' . $admin->name);
        } catch (ValidationException $e) {
            $this->activityLogService->log(
                'login_failed',
                'Failed login attempt for email: ' . $request->email,
                null,
                ['ip' => $request->ip()]
            );
            throw $e;
        }
    }

    public function logout(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $this->activityLogService->log(
            'logout',
            'Admin logged out',
            $admin->id
        );

        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('admin.login')
            ->with('success', 'You have been successfully logged out');
    }

    public function showRegisterForm()
    {
        return view('admin.auth.register');
    }

    public function register(RegisterRequest $request)
{
    try {
        $validated = $request->validated();

        $admin = Admin::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'], // setter akan otomatis hash
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->activityLogService->log(
            'register',
            'New admin account created',
            $admin->id
        );

        return redirect()
            ->route('admin.login')
            ->with('success', 'Admin account created successfully. Please login.');
    } catch (\Exception $e) {
        return back()
            ->withInput()
            ->withErrors(['error' => 'Failed to create admin account. Please try again.']);
    }
}

}
