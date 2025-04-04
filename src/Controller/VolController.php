<?php

namespace App\Controller;

use App\Entity\Vol;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VolController extends AbstractController
{
    #[Route('/vol', name: 'app_vol')]
    public function index(): Response
    {
        return $this->render('vol/index.html.twig', [
            'controller_name' => 'VolController',
        ]);
    }

    #[Route('/vol/{id}', name: 'vol_detail')]
    public function detail(int $id): Response
    {
        return new Response("Détails du vol numéro : $id");
    }
    #[Route('/vol/ajout', name: 'ajouter_vol')]
    public function ajouterVol(EntityManagerInterface $entityManager): Response
    {
        $vol = new Vol();
        $vol->setVilleDepart('Paris');
        $vol->setVilleArrive('Malaga');
        $vol->setDateDepart(new \DateTime('now'));
        $vol->setHeureDepart(new \DateTime('now'));
        $vol->setPrixBilletInitial(150.00);
        $entityManager->persist($vol);
        $entityManager->flush();
        return new Response('Vol ajouté avec succès !');
    }
}
