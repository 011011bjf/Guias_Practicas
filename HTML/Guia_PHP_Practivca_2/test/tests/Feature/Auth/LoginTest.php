<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_login_form(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertSee('Iniciar Sesión');
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::create([
            'name' => 'Admin Test',
            'email' => 'admin.test@example.com',
            'password' => 'password123',
        ]);

        $response = $this->post(route('login'), [
            'email' => 'admin.test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_invalid_password(): void
    {
        User::create([
            'name' => 'Admin Test',
            'email' => 'admin.test@example.com',
            'password' => 'password123',
        ]);

        $response = $this->from(route('login'))->post(route('login'), [
            'email' => 'admin.test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }
}
