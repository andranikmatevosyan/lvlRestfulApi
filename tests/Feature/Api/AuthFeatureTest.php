<?php

namespace Tests\Feature\Api;

use Tests\TestCase;

use App\User;

class AuthFeatureTest extends TestCase
{
    public function testsUsersAreCreatedCorrectly()
    {
        $latestUser = User::orderBy('id', 'desc')->first();
        $numb = 1;

        if($latestUser) {
            $numb = $latestUser->id + 1;
        }

        $payload = [
            'name' => 'Name',
            'email' => 'mailcreate'. $numb .'@mail.ru',
            'password' => 'password'
        ];

        $this->json('POST', '/api/v1/user/register', $payload)
            ->assertStatus(201);
    }


    public function testsUsersSigninCorrectly()
    {
        $latestUser = User::orderBy('id', 'desc')->first();
        $numb = 1;

        if($latestUser) {
            $numb = $latestUser->id + 1;
        }

        $user = factory(User::class)->create([
            'name' => 'Name',
            'email' => 'mailsign'. $numb .'@mail.ru',
            'password' => bcrypt('password')
        ]);

        $payload = [
            'email' => $user->email,
            'password' => 'password'
        ];

        $this->json('POST', '/api/v1/user/signin', $payload)
            ->assertStatus(201);
    }
}
