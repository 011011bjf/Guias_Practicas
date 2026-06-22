<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_register_form(): void
    {
        $response = $this->get(route('register'));

        $response->assertStatus(200);
        $response->assertSee('Crea tu Cuenta');
    }

    public function test_user_can_register_with_valid_data(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('users', [
            'email' => 'john.doe@example.com',
        ]);
        $this->assertAuthenticated();
    }

    public function test_registration_fails_with_duplicate_email(): void
    {
        User::create([
            'name' => 'Existing User',
            'email' => 'john.doe@example.com',
            'password' => 'Password123',
        ]);

        $response = $this->from(route('register'))->post(route('register'), [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
        ]);

        $response->assertRedirect(route('register'));
        $response->assertSessionHasErrors('email');
    }
}
