<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_renders_for_guests(): void
    {
        $response = $this->get(route('admin.login'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component('Admin/Login'));
    }

    public function test_admin_can_log_in_with_valid_credentials(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->post(route('admin.login.store'), [
            'username' => $admin->username,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->from(route('admin.login'))->post(route('admin.login.store'), [
            'username' => $admin->username,
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect(route('admin.login'));
        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_user_without_admin_flag_cannot_log_in(): void
    {
        $user = User::factory()->create();

        $response = $this->post(route('admin.login.store'), [
            'username' => $user->username,
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_login_is_blocked_after_too_many_failed_attempts(): void
    {
        $this->freezeTime();
        $admin = User::factory()->admin()->create();

        foreach (range(1, 5) as $attempt) {
            $this->post(route('admin.login.store'), ['username' => $admin->username, 'password' => 'wrong-password']);
        }

        $response = $this->post(route('admin.login.store'), [
            'username' => $admin->username,
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors(['username' => 'Zbyt wiele prób logowania. Spróbuj ponownie za 60 s.']);
        $this->assertGuest();
    }

    public function test_logged_in_admin_is_redirected_away_from_login_page(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('admin.login'));

        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_admin_can_log_out(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.logout'));

        $response->assertRedirect(route('admin.login'));
        $this->assertGuest();
    }
}
