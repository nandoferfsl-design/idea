<?php

use App\Models\User;
use App\Notifications\EmailChanged;
use Illuminate\Support\Facades\Notification;

it('requires authentication', function (): void {
    $this->get(route('profile.edit'))->assertRedirect('/login');
});

it('edits a profile', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);
    visit(route('profile.edit'))
        ->assertValue('name', $user->name)
        ->fill('name', 'New Name')
        ->assertValue('email', $user->email)
        ->fill('email', 'new@example.com')
        ->click('Update Account')
        ->assertSee('Profile updated!');

    expect($user->fresh())->toMatchArray([
        'name' => 'New Name',
    ]);
});

it('notifies the original email if updated', function (): void {

    $user = User::factory()->create();

    $this->actingAs($user);

    Notification::fake();

    $originalEmail = $user->email;

    visit(route('profile.edit'))
        ->assertValue('name', $user->name)
        ->fill('email', 'new@example.com')
        ->click('Update Account')
        ->assertSee('Profile updated!');

    Notification::assertSentOnDemand(EmailChanged::class, function (EmailChanged $notification, $routes, $notifiable) use ($originalEmail) {
        return $notifiable->routes['mail'] === $originalEmail;
    });

});
