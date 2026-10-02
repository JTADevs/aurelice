<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    /**
     * Display the list of administrator accounts.
     */
    public function index(Request $request): Response
    {
        $users = User::query()
            ->where('is_admin', true)
            ->orderBy('name')
            ->get()
            ->map(fn (User $user): array => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'is_current' => $user->is($request->user()),
            ]);

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
        ]);
    }

    /**
     * Show the form for creating a new administrator account.
     */
    public function create(): Response
    {
        return Inertia::render('Admin/Users/Create');
    }

    /**
     * Store a new administrator account.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $user = new User($request->safe()->only(['name', 'username', 'email', 'password']));
        $user->is_admin = true;
        $user->save();

        Inertia::flash('success', "Dodano konto „{$user->username}”.");

        return redirect()->route('admin.users.index');
    }

    /**
     * Show the form for editing an administrator account.
     */
    public function edit(Request $request, User $user): Response
    {
        $this->ensureIsAdministrator($user);

        return Inertia::render('Admin/Users/Edit', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'is_current' => $user->is($request->user()),
            ],
        ]);
    }

    /**
     * Update an administrator account; the password changes only when a new one is given.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $this->ensureIsAdministrator($user);

        $user->fill($request->safe()->only(['name', 'username', 'email']));

        if ($request->filled('password')) {
            $user->password = $request->validated('password');
        }

        $user->save();

        Inertia::flash('success', 'Zapisano zmiany konta.');

        return redirect()->route('admin.users.edit', $user);
    }

    /**
     * Delete an administrator account other than the signed-in one.
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        $this->ensureIsAdministrator($user);

        if ($user->is($request->user())) {
            Inertia::flash('error', 'Nie możesz usunąć własnego konta.');

            return back();
        }

        $user->delete();

        Inertia::flash('success', "Usunięto konto „{$user->username}”.");

        return redirect()->route('admin.users.index');
    }

    /**
     * Limit the module to administrator accounts.
     */
    private function ensureIsAdministrator(User $user): void
    {
        abort_unless($user->is_admin, 404);
    }
}
