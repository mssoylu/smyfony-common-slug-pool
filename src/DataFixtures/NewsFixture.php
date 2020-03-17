<?php

namespace App\DataFixtures;

use App\Entity\News;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class NewsFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $news = new News();
        $news->setTitle('hello');
        $news->setContent('hello');
        $news->setSlug('hello-news');
        $manager->persist($news);

        $manager->flush();
    }
}
