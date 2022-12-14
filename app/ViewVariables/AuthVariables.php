<?php

namespace App\ViewVariables;

class AuthVariables
{
    public function getName(): string
    {
        return 'auth';
    }

    public function getValue($value = ""):array
    {
        return [
            'confirmation' => "$value"
        ];
    }
}