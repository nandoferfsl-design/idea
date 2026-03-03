<?php

use App\Models\Idea;
use App\Models\User;

test('it belongs to a user', function (): void {
    $idea = Idea::factory()->create();

    expect($idea->user)->toBeInstanceOf(User::class);
});

test('it can have steps', function (): void {
    $idea = Idea::factory()->create();

    expect($idea->steps)->toBeEmpty();

    $idea->steps()->create([
        'description' => 'Do the thing',
    ]);

    expect($idea->fresh()->steps)->toHaveCount(1);
});

test('it can format a description using markdown', function (): void {
    $idea = new Idea(['description' => 'Hello *world*']);
    expect($idea->formattedDescription)->toEqual("<p>Hello <em>world</em></p>\n");
});
