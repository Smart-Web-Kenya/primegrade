<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response
            ->assertOk()
            ->assertSeeVolt('pages.auth.login');
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $component = Volt::test('pages.auth.login')
            ->set('form.email', $user->email)
            ->set('form.password', 'password');

        $component->call('login');

        $component
            ->assertHasNoErrors()
            ->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticated();
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $component = Volt::test('pages.auth.login')
            ->set('form.email', $user->email)
            ->set('form.password', 'wrong-password');

        $component->call('login');

        $component
            ->assertHasErrors()
            ->assertNoRedirect();

        $this->assertGuest();
    }

    public function test_dashboard_uses_admin_sidebar(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->get('/dashboard')
            ->assertOk()
            ->assertSee('Admin Dashboard')
            ->assertSee('CMS')
            ->assertSeeVolt('pages.dashboard');
    }

    public function test_cms_dashboard_is_separate_from_main_dashboard(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->get('/cms')
            ->assertOk()
            ->assertSee('CMS Dashboard')
            ->assertSeeVolt('pages.cms.dashboard');
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->post(route('logout'))
            ->assertRedirect('/');

        $this->assertGuest();
    }
}
