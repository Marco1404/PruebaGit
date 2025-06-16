<?php

class ValidadorEmail
{
    public function esValido($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}
