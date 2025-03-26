<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\PasswordStrength;

class CreateUserDTO {
    public function __construct(
        #[Assert\Email]
        public string $email,
    
        #[Assert\Length(min: 4)]
        public string $displayName,
    
        #[Assert\PasswordStrength(minScore: PasswordStrength::STRENGTH_WEAK)]
        public string $password,
    ) {}
}