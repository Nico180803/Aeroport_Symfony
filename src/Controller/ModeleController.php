<?php

namespace App\Controller;

use App\Entity\Modele;
use App\Form\ModeleType;
use App\Repository\ModeleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ModeleController extends AbstractController
{

    #[Route('/modele', name: 'app_modele')]
    public function index(ModeleRepository $modeleRepository): Response
    {
        $modeles = $modeleRepository->findAll();
        return $this->render('modele/tableau.html.twig', [
            'modeles' => $modeles,
        ]);
    }

    #[Route('/modele/new', name: 'app_modele_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $modele = new Modele();
        $formulaire = $this->createForm(ModeleType::class, $modele);
        $formulaire->handleRequest($request);
        if ($formulaire->isSubmitted() && $formulaire->isValid()) {
            $entityManager->persist($modele);
            $entityManager->flush();
            return $this->redirectToRoute('app_modele');
        }

        return $this->render('modele/index.html.twig', [
            'controller_name' => 'ModeleController',
            'form' => $formulaire->createView(),
        ]);
    }

    #[Route('/modele/{id}/edit', name: 'app_modele_edit')]
    public function edit($id, Request $request, EntityManagerInterface $entityManager, ModeleRepository $modeleRepository): Response
    {
        $modele = $modeleRepository->find($id);
        $formulaire = $this->createForm(ModeleType::class, $modele);
        $formulaire->handleRequest($request);
        if ($formulaire->isSubmitted() && $formulaire->isValid()) {
            $entityManager->persist($modele);
            $entityManager->flush();
            return $this->redirectToRoute('app_modele');
        }

        return $this->render('modele/index.html.twig', [
            'controller_name' => 'ModeleController',
            'form' => $formulaire->createView(),
        ]);
    }

    #[Route('/modele/{id}/delete', name: 'app_modele_supp')]
    public function delete($id, EntityManagerInterface $entityManager): Response
    {
        $modele = $entityManager->find(Modele::class, $id);
        $entityManager->remove($modele);
        $entityManager->flush();
        return $this->redirectToRoute('app_modele');
    }
}
