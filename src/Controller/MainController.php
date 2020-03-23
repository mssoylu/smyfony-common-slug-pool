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

    public function show($slug, \Redis $client)
    {
        if ($client->get($slug)) {
            return $this->forward('App\Controller\ShowController:' . strtolower($client->get($slug)), [
                'slug' => $slug
            ]);
        }

        $em = $this->getDoctrine()->getManager();
        $slug = $em->getRepository(Slug::class)->findOneBy(['slug' => $slug]);

        if ($slug) {
            return $this->forward('App\Controller\ShowController:' . strtolower($slug->getType()), [
                'slug' => $slug->getSlug()
            ]);
        }

        throw $this->createNotFoundException('Url not found...');
    }
}
