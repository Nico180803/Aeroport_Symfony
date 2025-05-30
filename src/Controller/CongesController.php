<?php

namespace App\Controller;

use App\Entity\Conges;
use App\Form\CongesType;
use App\Repository\CongesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/conges')]
class CongesController extends AbstractController
{
    #[Route('/', name: 'app_conges_index', methods: ['GET'])]
    public function index(CongesRepository $congesRepository): Response
    {
        return $this->render('conges/index.html.twig', [
            'conges' => $congesRepository->findAll(),
        ]);
    }

    #[Route('/new/', name: 'app_conges_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $conge = new Conges();
        $form = $this->createForm(CongesType::class, $conge);
        if (!$this->isGranted('ROLE_ADMIN')) {
            $form->remove('estValide');
            $form->remove('ref_utilisateur');
            $conge->setEstValide(false);
            $conge->setRefUtilisateur($this->getUser());
        }
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($conge);
            $entityManager->flush();

            if (!$this->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('app_utilisateur_profil', [], Response::HTTP_SEE_OTHER);
            }else {
                return $this->redirectToRoute('app_conges_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->renderForm('conges/new.html.twig', [
            'conge' => $conge,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_conges_show', methods: ['GET'])]
    public function show(Conges $conge): Response
    {
        return $this->render('conges/show.html.twig', [
            'conge' => $conge,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_conges_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Conges $conge, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CongesType::class, $conge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_conges_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('conges/edit.html.twig', [
            'conge' => $conge,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_conges_delete', methods: ['POST'])]
    public function delete(Request $request, Conges $conge, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$conge->getId(), $request->request->get('_token'))) {
            $entityManager->remove($conge);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_conges_index', [], Response::HTTP_SEE_OTHER);
    }
}
