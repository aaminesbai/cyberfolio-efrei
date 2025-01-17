<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Recommendations;
use Doctrine\ORM\EntityManagerInterface;

class AboutController extends AbstractController
{
   
    private $recommendationsRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->recommendationsRepository = $em->getRepository(Recommendations::class);
    }

    #[Route('/a-propos', name: 'app_about')]
    public function index(): Response
    {
        // Cherche les recommandations dans la base de données
        $recommendations = $this->recommendationsRepository->findAll();

        return $this->render('frontend/a-propos/index.html.twig', [
            'recommendations' => $recommendations,
        ]);
    }
}

