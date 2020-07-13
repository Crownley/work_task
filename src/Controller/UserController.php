<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use App\Service\Entity\UserService;

class UserController extends AbstractController
{
    /**
    * @Route("/register", name="api_register", methods={"POST"})
    */
    public function register(Request $request, UserService $userService)
{
   if($userService->create($request->request->all()))
   {
       return $this->json([
           'user' => $userService->getUser()
       ]);
   }
   return $this->json([
       'errors' => $userService->getErrors()
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
