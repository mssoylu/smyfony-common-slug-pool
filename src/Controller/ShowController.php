<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\News;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ShowController extends AbstractController
{
    /**
     * @param $slug
     * @param $page
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function news($slug, $page, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $news = $em->getRepository(News::class)->findOneBy(['slug' => $slug]);

        return $this->render('show/news.html.twig', [
            'news' => $news
        ]);
    }

    /**
     * @param $slug
     * @param $page
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function blog($slug, $page, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $blog = $em->getRepository(Blog::class)->findOneBy(['slug' => $slug]);

        return $this->render('show/blog.html.twig', [
            'blog' => $blog
        ]);
    }
}
