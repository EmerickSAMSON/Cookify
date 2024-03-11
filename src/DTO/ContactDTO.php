<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ContactDTO{

    #[Assert\NotBlank()]
    #[Assert\Length(min: 3, max: 150)]
    public string $name = "";
    
    #[Assert\NotBlank()]
    #[Assert\Email()]
    public string $email = "";
    
    #[Assert\NotBlank()]
    #[Assert\Length(min: 3)]
    public string $message = "";
    
    #[Assert\NotBlank()]
    public string $service = "";
}