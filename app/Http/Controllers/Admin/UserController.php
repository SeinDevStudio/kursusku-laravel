<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function dashboard(): View
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'inactive_users' => User::where('is_active', false)->count(),
            'total_admins' => User::where('role', 'admin')->count(),
        ];

        $recentUsers = User::latest()->take(5)->get();
        $recentLogins = User::whereNotNull('last_login_at')
            ->orderByDesc('last_login_at')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentLogins'));
    }

    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        $role = (string) $request->query('role', '');
        $status = (string) $request->query('status', '');

        $users = User::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($subQuery) use ($q) {
                    $subQuery->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('username', 'like', "%{$q}%");
                });
            })
            ->when(in_array($role, ['admin', 'user'], true), function ($query) use ($role) {
                $query->where('role', $role);
            })
            ->when(in_array($status, ['active', 'inactive'], true), function ($query) use ($status) {
                $query->where('is_active', $status === 'active');
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'q', 'role', 'status'));
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $user = new User([
            'name' => $validated['name'],
            'username' => $validated['username'] ?? null,
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
        ]);
        $user->role = $validated['role'];
        $user->is_active = (bool) $validated['is_active'];
        $user->email_verified_at = now();
        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', "Akun pengguna {$user->name} berhasil dibuat.");
    }

    public function show(User $user): View
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();
        $newRole = $validated['role'];
        $newActive = (bool) $validated['is_active'];
        $currentUser = $request->user();

        if ($user->id === $currentUser->id && !$newActive) {
            return back()->withInput()
                ->withErrors(['is_active' => 'Anda tidak dapat menonaktifkan akun Anda sendiri.']);
        }

        if ($user->id === $currentUser->id && $newRole !== 'admin') {
            return back()->withInput()
                ->withErrors(['role' => 'Anda tidak dapat menurunkan role akun Anda sendiri.']);
        }

        $isLosingAdminPower = $user->isAdmin() && ($newRole !== 'admin' || !$newActive);
        if ($isLosingAdminPower && $this->otherActiveAdminsCount($user->id) === 0) {
            return back()->withInput()
                ->withErrors(['role' => 'Sistem harus memiliki minimal satu akun administrator yang aktif.']);
        }

        $user->fill([
            'name' => $validated['name'],
            'username' => $validated['username'] ?? null,
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
        ]);

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->role = $newRole;
        $user->is_active = $newActive;
        $user->save();

        return redirect()->route('admin.users.show', $user)
            ->with('success', "Data pengguna {$user->name} berhasil diperbarui.");
    }

    public function toggleStatus(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->withErrors(['status' => 'Anda tidak dapat menonaktifkan akun Anda sendiri.']);
        }

        $targetActive = !$user->is_active;

        if ($user->isAdmin() && !$targetActive && $this->otherActiveAdminsCount($user->id) === 0) {
            return back()->withErrors(['status' => 'Tidak dapat menonaktifkan satu-satunya administrator aktif.']);
        }

        $user->is_active = $targetActive;
        $user->save();

        $label = $targetActive ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Akun {$user->name} berhasil {$label}.");
    }

    private function otherActiveAdminsCount(int $excludeUserId): int
    {
        return User::where('role', 'admin')
            ->where('is_active', true)
            ->where('id', '!=', $excludeUserId)
            ->count();
    }
}
