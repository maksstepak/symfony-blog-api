<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\ArticleService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/users", name="users_")
 */
class UserController extends AbstractController
{
    private $userService;

    private $articleService;

    public function __construct(UserService $userService, ArticleService $articleService)
    {
        $this->userService = $userService;
        $this->articleService = $articleService;
    }

    /**
     * @Route(name="index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        $limit = $request->query->get('limit') ?? 10;
        $offset = $request->query->get('offset') ?? 0;

        $result = $this->userService->paginate($limit, $offset);

        return $this->json(
            $result,
            Response::HTTP_OK,
            [],
            ['groups' => ['list_user']]
        );
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->json(
            $user,
            Response::HTTP_OK,
            [],
            ['groups' => ['show_user']]
        );
    }

    /**
     * @Route("/{id}/articles", name="articles", methods={"GET"})
     */
    public function getArticles(User $user): Response
    {
        $articles = $this->articleService->findByAuthor($user);

        return $this->json(
            $articles,
            Response::HTTP_OK,
            [],
            ['groups' => ['list_article', 'list_user']]
        );
    }
}
