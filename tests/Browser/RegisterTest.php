<?php

use Illuminate\Support\Facades\Auth;

use function Pest\Laravel\assertAuthenticated;

it('registers a user', function (): void {
    visit('/register')
        ->fill('name', 'John Doe')
        ->fill('email', 'john@example.com')
        ->fill('password', 'password123!@#')
        ->click('Create Account')
        ->assertPathIs('/ideas');

    assertAuthenticated();

    expect(Auth::user()->toArray())->toMatchArray([
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ]);
});

it('requires a valid email', function (): void {
    visit('/register')
        ->fill('name', 'John Doe')
        ->fill('email', 'john123')
        ->fill('password', 'password123!@#')
        ->click('Create Account')
        ->assertPathIs('/register');
});
