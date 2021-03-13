<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $admins = [];
        for ($i = 1; $i <= 3; $i++) {
            $userAdmin = new User();

            $password = $this->encoder->encodePassword($userAdmin, 'admin123');

            $userAdmin
                ->setEmail('admin'.$i.'@test.com')
                ->setPassword($password)
                ->setRoles(['ROLE_ADMIN'])
            ;

            $manager->persist($userAdmin);
            $admins[] = $userAdmin;
        }

        $articles = [];
        for ($i = 1; $i <= 20; $i++) {
            $article = new Article();

            $createdDateTime = new \DateTime();

            $article
                ->setTitle('title '.$i)
                ->setContent('content')
                ->setAuthor($admins[array_rand($admins)])
                ->setCreatedAt($createdDateTime)
                ->setUpdatedAt($createdDateTime)
            ;

            $manager->persist($article);
            $articles[] = $article;
        }

        $users = [];
        for ($i = 1; $i <= 10; $i++) {
            $user = new User();

            $password = $this->encoder->encodePassword($user, 'pass123');

            $user
                ->setEmail('user'.$i.'@test.com')
                ->setPassword($password)
            ;

            $manager->persist($user);
            $users[] = $user;
        }

        $comments = [];
        for ($i = 1; $i <= 30; $i++) {
            $comment = new Comment();

            $createdDateTime = new \DateTime();

            $comment
                ->setContent('comment '.$i)
                ->setAuthor($users[array_rand($users)])
                ->setArticle($articles[array_rand($articles)])
                ->setCreatedAt($createdDateTime)
                ->setUpdatedAt($createdDateTime)
            ;

            $manager->persist($comment);
            $comments[] = $comment;
        }

        $manager->flush();
    }
}
