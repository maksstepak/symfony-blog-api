<?php

namespace App\Service;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\User;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;

class CommentService
{
    private $em;

    private $commentRepository;

    public function __construct(
        EntityManagerInterface $em,
        CommentRepository $commentRepository
    ) {
        $this->em = $em;
        $this->commentRepository = $commentRepository;
    }

    /**
     * @return Comment[]
     */
    public function findByArticle(Article $article)
    {
        return $this->commentRepository->findByArticle($article);
    }

    public function add(Comment $comment, Article $article, User $author): Comment
    {
        $comment->setArticle($article);
        $comment->setAuthor($author);

        $createdDateTime = new \DateTime();
        $comment->setCreatedAt($createdDateTime);
        $comment->setUpdatedAt($createdDateTime);

        $this->em->persist($comment);
        $this->em->flush();

        return $comment;
    }
}
