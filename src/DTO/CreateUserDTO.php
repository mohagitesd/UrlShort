<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateUserDTO {
    public function __construct(
        #[Assert\Email]
        public string $email,
    
        #[Assert\Length(min: 4)]
        public string $displayName,
    
        #[Assert\PasswordStrength(
            minLength: 8,
            minStrength: 3,// 3 out of 4 character types
            message: 'Password must be at least 8 characters long and contain at least 3 of the following: uppercase, lowercase, number, special character'
        )]
        public string $password,
    ) {}
}