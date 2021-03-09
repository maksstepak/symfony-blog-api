<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Service\ArticleService;
use App\Service\CommentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/articles", name="articles_")
 */
class ArticleController extends AbstractController
{
    private $articleService;

    private $commentService;

    public function __construct(
        ArticleService $articleService,
        CommentService $commentService
    ) {
        $this->articleService = $articleService;
        $this->commentService = $commentService;
    }

    /**
     * @Route(name="index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        $limit = $request->query->get('limit') ?? 10;
        $offset = $request->query->get('offset') ?? 0;

        $result = $this->articleService->paginate($limit, $offset);

        return $this->json(
            $result,
            Response::HTTP_OK,
            [],
            ['groups' => ['list_article', 'list_user']]
        );
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Article $article): Response
    {
        return $this->json(
            $article,
            Response::HTTP_OK,
            [],
            ['groups' => ['show_article', 'list_user']]
        );
    }

    /**
     * @Route(name="add", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function add(Request $request): Response
    {
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);

        $form->submit($request->toArray());
        if (!$form->isValid()) {
            return $this->json($form->getErrors(true), Response::HTTP_BAD_REQUEST);
        }

        $article = $form->getData();

        $user = $this->getUser();
        $this->articleService->add($article, $user);

        return new Response(null, Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", name="update", methods={"PUT"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function update(Request $request, Article $article): Response
    {
        $form = $this->createForm(ArticleType::class, $article);

        $form->submit($request->toArray());
        if (!$form->isValid()) {
            return $this->json($form->getErrors(true), Response::HTTP_BAD_REQUEST);
        }

        $article = $form->getData();

        $this->articleService->update($article);

        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Article $article): Response
    {
        $this->articleService->delete($article);

        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/{id}/comments", name="comments", methods={"GET"})
     */
    public function getComments(Article $article): Response
    {
        $comments = $this->commentService->findByArticle($article);

        return $this->json(
            $comments,
            Response::HTTP_OK,
            [],
            ['groups' => ['list_comment', 'list_user']]
        );
    }

    /**
     * @Route("/{id}/comments", name="comments_add", methods={"POST"})
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function addComment(Request $request, Article $article): Response
    {
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->submit($request->toArray());
        if (!$form->isValid()) {
            return $this->json($form->getErrors(true), Response::HTTP_BAD_REQUEST);
        }

        $comment = $form->getData();
        $user = $this->getUser();

        $this->commentService->add($comment, $article, $user);

        return new Response(null, Response::HTTP_CREATED);
    }
}
