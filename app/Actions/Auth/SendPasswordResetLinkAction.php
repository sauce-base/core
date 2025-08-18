<?php

namespace App\Actions\Auth;

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SendPasswordResetLinkAction
{
    public function execute(string $email): bool
    {
        $validator = Validator::make([
            'email' => $email,
        ], [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $status = Password::sendResetLink(['email' => $email]);

        if ($status == Password::RESET_LINK_SENT) {
            return true;
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
}
