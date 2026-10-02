<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUserSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_both_administrators_with_the_configured_password(): void
    {
        config(['auth.admin_password' => 'haslo-z-env']);

        $this->seed(AdminUserSeeder::class);

        $admins = User::orderBy('username')->get();

        $this->assertSame(['alakotowska', 'sebsqu'], $admins->pluck('username')->all());
        $this->assertSame('Ala Kotowska', $admins[0]->name);

        foreach ($admins as $admin) {
            $this->assertTrue($admin->is_admin);
            $this->assertTrue(Hash::check('haslo-z-env', $admin->password));
        }
    }

    public function test_running_it_again_keeps_existing_accounts_and_passwords(): void
    {
        config(['auth.admin_password' => 'stare-haslo']);
        $this->seed(AdminUserSeeder::class);

        config(['auth.admin_password' => 'nowe-haslo']);
        $this->seed(AdminUserSeeder::class);

        $this->assertSame(2, User::count());
        $this->assertTrue(User::get()->every(fn (User $admin) => Hash::check('stare-haslo', $admin->password)));
    }

    public function test_it_creates_only_the_missing_account(): void
    {
        $existing = User::factory()->admin()->create(['username' => 'alakotowska']);
        config(['auth.admin_password' => 'haslo-z-env']);

        $this->seed(AdminUserSeeder::class);

        $this->assertTrue(Hash::check('password', $existing->refresh()->password));
        $this->assertTrue(Hash::check('haslo-z-env', User::where('username', 'sebsqu')->sole()->password));
    }

    public function test_it_generates_one_shared_password_when_none_is_configured(): void
    {
        config(['auth.admin_password' => null]);

        $this->seed(AdminUserSeeder::class);

        $admins = User::get();
        $this->assertCount(2, $admins);
        $this->assertTrue($admins->every(fn (User $admin) => $admin->is_admin));
    }
}
