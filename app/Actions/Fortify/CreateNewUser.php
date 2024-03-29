<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
            'id' => [
                'required',
                'max:9',
                Rule::unique(User::class),
            ],
            'phone' => [
                'required',
                'max:10',
                Rule::unique(User::class),
            ],
            'department_id' => [
                'required',
                'max:2',
                Rule::unique(User::class),
            ],

        ])->validate();

        return User::create([
            'name' => $input['name'],
            'id' => $input['id'],
            'department_id' => $input['department_id'],
            'phone' => $input['phone'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
    }
}
