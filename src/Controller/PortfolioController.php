<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\Portfolio;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PortfolioController extends AbstractController
{
    private $portfolioRepository;
    private $blogRepository;
    
    public function __construct(EntityManagerInterface $em)
    {
        $this->portfolioRepository = $em->getRepository(Portfolio::class);
        $this->blogRepository = $em->getRepository(Blog::class);
    }

    #[Route('/portfolio', name: 'app_portfolio')]
    public function index(): Response
    {
        // Récupérer les blogs de la base de données
        $portfolios = $this->portfolioRepository->findAll();

        // Récupérer les portfolios par noms de catégories
        $react = $this->portfolioRepository->findBy(['category' => 'React']);
        $sql = $this->portfolioRepository->findBy(['category' => 'SQL']);
        $symfony = $this->portfolioRepository->findBy(['category' => 'Symfony']);
        $apis = $this->portfolioRepository->findBy(['category' => 'APIs']);

        return $this->render('frontend/portfolio/index.html.twig', [
            'portfolios' => $portfolios,
            'react' => $react,
            'sql' => $sql,
            'symfony' => $symfony,
            'apis' => $apis
        ]);
    }

    #[Route('/portfolio-details/{id}', name: 'app_portfolio_details')]
    public function portfolioDetail($id, Request $request): Response
    {
        // Trouver le portfolio par son ID
        $portfolio = $this->portfolioRepository->find($id);

        // Récupérer les blogs de la base de données
        $blogs = $this->blogRepository->findAll();

        return $this->render('frontend/portfolio-details/index.html.twig', [
            'portfolio' => $portfolio,
            'blogs' => $blogs
        ]);
    }
}
