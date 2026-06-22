<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepo
    ) {}

    public function register(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        return $this->userRepo->create($data);
    }

    public function login(array $credentials): bool
    {
        if (Auth::attempt($credentials)) {
            session()->regenerate();
            return true;
        }
        return false;
    }

    public function logout(): void
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
    }
}
