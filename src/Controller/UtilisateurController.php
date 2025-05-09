<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Utilisateur;
use App\Form\UtilisateurModifType;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use App\Repository\VolRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/utilisateur')]
class UtilisateurController extends AbstractController
{
    #[Route('/', name: 'app_utilisateur_index', methods: ['GET'])]
    public function index(UtilisateurRepository $utilisateurRepository): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_home');
        }
        return $this->render('utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_utilisateur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        if (!$this->isGranted('ROLE_ADMIN')) {
            $form->remove("refModele");
            $form->remove("roles");
        }
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('password')->getData();
            $hashedPassword = $passwordHasher->hashPassword($utilisateur, $password);
            $utilisateur->setPassword($hashedPassword);

            $entityManager->persist($utilisateur);
            $entityManager->flush();

            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('utilisateur/new.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,

        ]);
    }
    #[Route('/profil', name: 'app_utilisateur_profil', methods: ['GET', 'POST'])]
    public function profil(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('app_login');
        }
        $utilisateur = $this->getUser();
        $reservations = $this->getUser()->getReservations();
        $conges = $this->getUser()->getConges();

        $form = $this->createForm(UtilisateurModifType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_utilisateur_profil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('utilisateur/profil.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
            'reservations' => $reservations,
            'conges' => $conges
        ]);
    }

    #[Route('/{id}', name: 'app_utilisateur_show',requirements: ['id' => '\d+']  ,methods: ['GET'] ) ]
    public function show(Utilisateur $utilisateur): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_home');
        }
        return $this->render('utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_utilisateur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('utilisateur/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_utilisateur_delete', methods: ['POST'])]
    public function delete(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$utilisateur->getId(), $request->request->get('_token'))) {
            $entityManager->remove($utilisateur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
    }


}
