<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'user')->with('wallet');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by KYC status
        if ($request->filled('kyc_status')) {
            $query->where('kyc_status', $request->kyc_status);
        }

        $users = $query->latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'required|date',
            'gender' => 'nullable|in:male,female,other',
            'country' => 'required|string|size:2',
            'currency' => 'required|string|size:3',
            'status' => 'required|in:active,suspended,banned',
            'kyc_status' => 'required|in:pending,verified,rejected',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['referral_code'] = User::generateReferralCode();

        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'Kullanıcı başarıyla oluşturuldu.');
    }

    /**
     * Display the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function show(User $user)
    {
        $user->load(['wallet', 'transactions' => function($query) {
            $query->latest()->take(10);
        }, 'bets' => function($query) {
            $query->latest()->take(10);
        }, 'deposits' => function($query) {
            $query->latest()->take(5);
        }, 'withdrawals' => function($query) {
            $query->latest()->take(5);
        }]);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'required|date',
            'gender' => 'nullable|in:male,female,other',
            'country' => 'required|string|size:2',
            'currency' => 'required|string|size:3',
            'status' => 'required|in:active,suspended,banned',
            'kyc_status' => 'required|in:pending,verified,rejected',
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8']);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('admin.users.show', $user)->with('success', 'Kullanıcı bilgileri güncellendi.');
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        if ($user->isAdmin()) {
            return redirect()->back()->with('error', 'Admin kullanıcıları silinemez.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Kullanıcı başarıyla silindi.');
    }

    /**
     * Toggle user status.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleStatus(User $user)
    {
        $newStatus = match($user->status) {
            'active' => 'suspended',
            'suspended' => 'active',
            'banned' => 'active',
        };

        $user->update(['status' => $newStatus]);

        return redirect()->back()->with('success', "Kullanıcı durumu '{$newStatus}' olarak güncellendi.");
    }
}