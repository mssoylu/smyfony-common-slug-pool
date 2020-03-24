<?php

namespace App\Controller;

use App\Entity\Slug;
use Snc\RedisBundle\Client\Phpredis\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    public function index()
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    public function show($slug, $page, \Redis $client)
    {
        $slugArr = json_decode($client->get($slug));

        if (!empty($slugArr)) {
            return $this->forward('App\Controller\ShowController:' . strtolower($slugArr[0]), [
                'slug' => $slug,
                'page' => $page
            ]);
        }

        $em = $this->getDoctrine()->getManager();
        $slug = $em->getRepository(Slug::class)->findOneBy(['slug' => $slug]);

        if ($slug) {
            return $this->forward('App\Controller\ShowController:' . strtolower($slug->getType()), [
                'slug' => $slug->getSlug(),
                'page' => $page
            ]);
        }

        throw $this->createNotFoundException('Url not found...');
    }
}
