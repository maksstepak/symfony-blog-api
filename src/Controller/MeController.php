<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class MeController extends AbstractController
{
    /**
     * @Route("/me", name="me", methods={"GET"})
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function index(): Response
    {
        $user = $this->getUser();

        return $this->json($user, Response::HTTP_OK, [], ['groups' => 'show_user']);
    }
}
