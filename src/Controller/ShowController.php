<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\News;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ShowController extends AbstractController
{
    public function news($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $news = $em->getRepository(News::class)->findOneBy(['slug' => $slug]);

        return $this->render('show/news.html.twig', [
            'news' => $news
        ]);
    }

    public function blog($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $blog = $em->getRepository(Blog::class)->findOneBy(['slug' => $slug]);

        return $this->render('show/blog.html.twig', [
            'blog' => $blog
        ]);
    }
}
