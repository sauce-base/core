<?php

namespace Modules\Auth\Actions;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class ResetPasswordAction
{
    public function execute(string $email, string $password, string $token): bool
    {
        $validator = Validator::make([
            'email' => $email,
            'password' => $password,
            'token' => $token,
        ], [
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', Rules\Password::defaults()],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $status = Password::reset(
            [
                'email' => $email,
                'password' => $password,
                'password_confirmation' => $password,
                'token' => $token,
            ],
            function ($user) use ($password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return true;
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
}
