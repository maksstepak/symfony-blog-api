<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Exception\AccessDeniedException;
use App\Form\CommentType;
use App\Service\CommentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @Route("/comments", name="comments_")
 */
class CommentController extends AbstractController
{
    private $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Comment $comment): Response
    {
        return $this->json(
            $comment,
            Response::HTTP_OK,
            [],
            ['groups' => ['show_comment', 'list_user']]
        );
    }

    /**
     * @Route("/{id}", name="update", methods={"PUT"})
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function update(Request $request, Comment $comment): Response
    {
        $form = $this->createForm(CommentType::class, $comment);

        $form->submit($request->toArray());
        if (!$form->isValid()) {
            return $this->json($form->getErrors(true), Response::HTTP_BAD_REQUEST);
        }

        $comment = $form->getData();
        $user = $this->getUser();

        try {
            $this->commentService->update($comment, $user);
        } catch (AccessDeniedException $e) {
            throw new HttpException(Response::HTTP_FORBIDDEN, $e->getMessage());
        }

        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function delete(Comment $comment): Response
    {
        $user = $this->getUser();

        try {
            $this->commentService->delete($comment, $user);
        } catch (AccessDeniedException $e) {
            throw new HttpException(Response::HTTP_FORBIDDEN, $e->getMessage());
        }

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
