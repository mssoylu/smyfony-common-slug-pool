<?php

namespace App\DataFixtures;

use App\Entity\Blog;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class BlogFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $blog = new Blog();
        $blog->setTitle('hello blog');
        $blog->setContent('hello blog');
        $blog->setSlug('hello-blog');
        $manager->persist($blog);

        $manager->flush();
    }
}
