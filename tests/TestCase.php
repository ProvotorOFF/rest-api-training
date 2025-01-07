<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\Sanctum;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected User $user;

    protected function signIn(string $role = 'user'): User {
        $this->user = User::factory()->createOne(['role' => $role]);
        Sanctum::actingAs($this->user);
        return $this->user;
    }
}
