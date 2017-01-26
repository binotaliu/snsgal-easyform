<?php


namespace App\Repositories;



use App\Eloquent\User;
use Auth0\Login\Repository\Auth0UserRepository;

class UserRepository extends Auth0UserRepository
{
    protected $user;

    function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUserByDecodedJWT($jwt)
    {
        $jwt['user_id'] = $jwt->sub;

        return $this->upsertUser($jwt);
    }

    public function getUserByUserInfo($userInfo): User
    {
        return $this->upsertUser($userInfo['profile']);
    }

    protected function upsertUser($profile): User
    {
        $user = $this->user->where('auth0id', $profile['user_id'])->first();

        if ($user !== null) return $user;

        return $this->user->create([
            'email' => $profile['email'],
            'auth0id' => $profile['user_id'],
            'name' => $profile['name']
        ]);
    }
}