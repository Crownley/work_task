<?php

namespace App\Service\Entity;

use App\Validator\EntityValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class UserService
{
    private $validator;
    private $manager;
    private $passwordEncoder;
    private $errors = [];
    private $user;


    public function __construct(EntityValidator $validator, EntityManagerInterface $manager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->validator       = $validator;
        $this->manager             = $manager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function create($properties = [])
    {
        $email                = isset($properties['email']) ? $properties['email'] : "";
        $password             = isset($properties['password']) ? $properties['password'] : "";
        $passwordConfirmation = isset($properties['password_confirmation']) ? $properties['password_confirmation'] : "";

        $errors = [];
        if($password != $passwordConfirmation)
        {
            $errors[] = "Password does not match the password confirmation.";
        }

        if(strlen($password) < 6)
        {
            $errors[] = "Password should be at least 6 characters.";
        }

        if(!$errors)
        {
            $user = new User();
            $encodedPassword = $this->passwordEncoder->encodePassword($user, $password);
            $user->setEmail($email);
            $user->setPassword($encodedPassword);

            $isValid = $this->validator->validate($user);
            if($isValid)
            {
                // Save entity
                $this->manager->persist($user);
                $this->manager->flush();

                $this->user = $user;
                return true;
            }
            else
            {
                $errors = $this->validator->getErrors();
            }
        }

        $this->errors = $errors;

        return false;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}