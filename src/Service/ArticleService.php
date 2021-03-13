<?php

namespace App\Service;

use App\Entity\Article;
use App\Entity\User;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;

class ArticleService
{
    private $em;

    private $articleRepository;

    public function __construct(
        EntityManagerInterface $em,
        ArticleRepository $articleRepository
    ) {
        $this->em = $em;
        $this->articleRepository = $articleRepository;
    }

    public function paginate(int $limit, int $offset)
    {
        $total = $this->articleRepository->count([]);

        $articles = $this->articleRepository->findBy(
            [],
            ['createdAt' => 'DESC'],
            $limit,
            $offset
        );

        return [
            'total' => $total,
            'data' => $articles
        ];
    }

    public function add(Article $article, User $author): Article
    {
        $article->setAuthor($author);

        $createdDateTime = new \DateTime();
        $article->setCreatedAt($createdDateTime);
        $article->setUpdatedAt($createdDateTime);

        $this->em->persist($article);
        $this->em->flush();

        return $article;
    }

    public function update(Article $article): Article
    {
        $article->setUpdatedAt(new \DateTime());

        $this->em->flush();

        return $article;
    }

    public function delete(Article $article): void
    {
        $this->em->remove($article);
        $this->em->flush();
    }
}
