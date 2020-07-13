<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Service\Entity\UserService;
use App\Validator\EntityValidator;

class UserController extends AbstractController
{

    
    /**
    * @Route("/register", name="api_register", methods={"POST"})
    */
    public function register(EntityManagerInterface $om, UserPasswordEncoderInterface $passwordEncoder, Request $request, EntityValidator $validator)
    {
        $user = new User();
        $email                  = $request->request->get("email");
        $password               = $request->request->get("password");
        $passwordConfirmation   = $request->request->get("password_confirmation");
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
            $encodedPassword = $passwordEncoder->encodePassword($user, $password);
            $user->setEmail($email);
            $user->setPassword($encodedPassword);
            $isValid = $validator->validate($user);
            if($isValid)
            {
                // Save entity
                $om->persist($user);
                $om->flush();
                return $this->json([
                    'user' => $user
                ]);
            }
            else
            {
                $errors = $validator->getErrors();
            }
        }
                
        return $this->json([
            'errors' => $errors
        ], 400);
    }

    /**
    * @Route("/login", name="api_login", methods={"POST"})
    */
    public function login()
    {
        return $this->json(['result' => true]);
    }

    /**
     * @Route("/profile", name="api_profile")
     * @IsGranted("ROLE_USER")
     */
    public function profile()
    {
        return $this->json([
            'user' => $this->getUser()
        ]);
    }
}
