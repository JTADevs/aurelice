<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->admin()->create(['name' => 'Ala Kotowska']);
    }

    public function test_user_without_admin_flag_cannot_manage_accounts(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get(route('admin.users.index'))->assertForbidden();
        $this->post(route('admin.users.store'))->assertForbidden();
    }

    public function test_admin_sees_only_administrator_accounts(): void
    {
        $otherAdmin = User::factory()->admin()->create(['name' => 'Basia']);
        User::factory()->create();

        $response = $this->actingAs($this->admin)->get(route('admin.users.index'));

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Admin/Users/Index')
            ->has('users', 2)
            ->where('users.0.id', $this->admin->id)
            ->where('users.0.is_current', true)
            ->where('users.1.id', $otherAdmin->id)
            ->where('users.1.is_current', false)
        );
    }

    public function test_admin_creates_an_administrator_account(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.users.store'), [
            'name' => 'Basia Nowak',
            'username' => ' BNowak ',
            'email' => '',
            'password' => 'bardzo-tajne-haslo',
            'password_confirmation' => 'bardzo-tajne-haslo',
        ]);

        $response->assertRedirect(route('admin.users.index'));

        $user = User::where('username', 'bnowak')->sole();
        $this->assertTrue($user->is_admin);
        $this->assertNull($user->email);
        $this->assertTrue(Hash::check('bardzo-tajne-haslo', $user->password));
    }

    public function test_account_creation_is_validated(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.users.store'), [
            'name' => '',
            'username' => $this->admin->username,
            'email' => 'nie-email',
            'password' => 'krotkie',
            'password_confirmation' => 'inne',
        ]);

        $response->assertSessionHasErrors(['name', 'username', 'email', 'password']);
        $this->assertSame(1, User::count());
    }

    public function test_admin_updates_another_account_and_resets_its_password(): void
    {
        $other = User::factory()->admin()->create();

        $response = $this->actingAs($this->admin)->put(route('admin.users.update', $other), [
            'name' => 'Basia Nowak',
            'username' => 'basia',
            'email' => 'basia@aurelice.pl',
            'password' => 'nowe-haslo-basi',
            'password_confirmation' => 'nowe-haslo-basi',
        ]);

        $response->assertRedirect(route('admin.users.edit', $other));

        $other->refresh();
        $this->assertSame('basia', $other->username);
        $this->assertSame('basia@aurelice.pl', $other->email);
        $this->assertTrue(Hash::check('nowe-haslo-basi', $other->password));
    }

    public function test_leaving_the_password_empty_keeps_the_current_one(): void
    {
        $other = User::factory()->admin()->create();

        $this->actingAs($this->admin)->put(route('admin.users.update', $other), [
            'name' => 'Basia',
            'username' => $other->username,
            'password' => '',
            'password_confirmation' => '',
        ])->assertSessionHasNoErrors();

        $this->assertTrue(Hash::check('password', $other->refresh()->password));
    }

    public function test_changing_own_password_requires_the_current_password(): void
    {
        $payload = [
            'name' => $this->admin->name,
            'username' => $this->admin->username,
            'password' => 'moje-nowe-haslo',
            'password_confirmation' => 'moje-nowe-haslo',
        ];

        $this->actingAs($this->admin)
            ->put(route('admin.users.update', $this->admin), [...$payload, 'current_password' => 'zle-haslo'])
            ->assertSessionHasErrors('current_password');

        $this->actingAs($this->admin)
            ->put(route('admin.users.update', $this->admin), [...$payload, 'current_password' => 'password'])
            ->assertSessionHasNoErrors();

        $this->assertTrue(Hash::check('moje-nowe-haslo', $this->admin->refresh()->password));
    }

    public function test_admin_deletes_another_account(): void
    {
        $other = User::factory()->admin()->create();

        $response = $this->actingAs($this->admin)->delete(route('admin.users.destroy', $other));

        $response->assertRedirect(route('admin.users.index'));
        $this->assertModelMissing($other);
    }

    public function test_admin_cannot_delete_own_account(): void
    {
        $response = $this->actingAs($this->admin)
            ->from(route('admin.users.index'))
            ->delete(route('admin.users.destroy', $this->admin));

        $response->assertRedirect(route('admin.users.index'));
        $this->assertModelExists($this->admin);
    }

    public function test_accounts_without_admin_flag_are_outside_the_module(): void
    {
        $customer = User::factory()->create();

        $this->actingAs($this->admin)->get(route('admin.users.edit', $customer))->assertNotFound();
        $this->actingAs($this->admin)->delete(route('admin.users.destroy', $customer))->assertNotFound();
        $this->assertModelExists($customer);
    }
}
