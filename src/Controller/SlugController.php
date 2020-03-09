<?php

namespace App\Controller;

use App\Entity\Slug;
use App\Form\SlugType;
use App\Repository\SlugRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/slug")
 */
class SlugController extends AbstractController
{
    /**
     * @Route("/", name="slug_index", methods={"GET"})
     */
    public function index(SlugRepository $slugRepository): Response
    {
        return $this->render('slug/index.html.twig', [
            'slugs' => $slugRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="slug_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $slug = new Slug();
        $form = $this->createForm(SlugType::class, $slug);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($slug);
            $entityManager->flush();

            return $this->redirectToRoute('slug_index');
        }

        return $this->render('slug/new.html.twig', [
            'slug' => $slug,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="slug_show", methods={"GET"})
     */
    public function show(Slug $slug): Response
    {
        return $this->render('slug/show.html.twig', [
            'slug' => $slug,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="slug_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Slug $slug): Response
    {
        $form = $this->createForm(SlugType::class, $slug);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('slug_index');
        }

        return $this->render('slug/edit.html.twig', [
            'slug' => $slug,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="slug_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Slug $slug): Response
    {
        if ($this->isCsrfTokenValid('delete'.$slug->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($slug);
            $entityManager->flush();
        }

        return $this->redirectToRoute('slug_index');
    }
}
