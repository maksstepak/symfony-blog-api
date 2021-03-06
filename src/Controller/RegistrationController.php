<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @Route("/register", name="user_registration", methods={"POST"})
     */
    public function register(Request $request): Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->submit($request->toArray());
        if (!$form->isValid()) {
            return $this->json($form->getErrors(true), Response::HTTP_BAD_REQUEST);
        }

        $user = $form->getData();

        $this->userService->add($user);

        return new Response(null, Response::HTTP_CREATED);
    }
}
