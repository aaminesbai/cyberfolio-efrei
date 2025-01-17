<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use App\Entity\Portfolio;
use App\Form\PortfolioFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[IsGranted('ROLE_USER')]
class PortfolioController extends AbstractController
{
    private $em;
    private $portfolioRepository;
    private $messagesRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->portfolioRepository = $em->getRepository(Portfolio::class);
        $this->messagesRepository = $em->getRepository(Contact::class);
    }
    
    #[Route('/admin/portfolio', name: 'app_admin_portfolio')]
    public function index(): Response
    {
        // Récupérer les blogs de la base de données
        $portfolios = $this->portfolioRepository->findAll();

        // Récupérer les messages de la base de données
        $messages = $this->messagesRepository->findAll();

        return $this->render('backend/portfolio/index.html.twig', [
            'portfolios' => $portfolios,
            'mails' => $messages
        ]);
    }

    #[Route('/admin/portfolio/create', name: 'create_portfolio')]
    public function create(Request $request): Response
    {
        $portfolio = new Portfolio;

        $form = $this->createForm(PortfolioFormType::class, $portfolio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPortfolio = $form->getData();

            // Obtenir la valeur de la catégorie sélectionnée dans le formulaire
            $selectedCategory = $form->get('category')->getData();

            // Effectuer les vérifications et définir les catégories en conséquence
            if ($selectedCategory === 'React') {
                $newPortfolio->setCategory('React');
            } elseif ($selectedCategory === 'SQL') {
                $newPortfolio->setCategory('SQL');
            } elseif ($selectedCategory === 'Symfony') {
                $newPortfolio->setCategory('Symfony');
            } elseif ($selectedCategory === 'APIs') {
                $newPortfolio->setCategory('APIs');
            }

            // Télécharger la "première ou deuxième" image (choix intentionnel d'utiliser cette méthode pour référence future)
            $this->uploadImage($form, 'image', $newPortfolio);

            $this->em->persist($newPortfolio);
            $this->em->flush();

            // Ajouter un message flash pour la réussite de la création
            $this->addFlash('success', 'Le portfolio a été créé avec succès !');

            return $this->redirectToRoute('app_admin_portfolio');
        }

        // Récupérer les messages de la base de données
        $messages = $this->messagesRepository->findAll();
        
        return $this->render('backend/portfolio/create.html.twig', [
            'portfolioForm' => $form->createView(),
            'mails' => $messages
        ]);
    }

    private function uploadImage(FormInterface $form, string $fieldName, Portfolio $portfolio): void
    {
        $image = $form->get($fieldName)->getData();

        if ($image) {
            $newFileName = uniqid() . '.' . $image->guessExtension();
            try {
                $image->move(
                    $this->getParameter('kernel.project_dir') . '/public/uploads/portfolio',
                    $newFileName
                );
            } catch (FileException $e) {
                throw new \Exception($e->getMessage());
            }
            // Construire dynamiquement le nom de la méthode setter
            $setterMethod = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $fieldName)));
            $portfolio->$setterMethod('/uploads/portfolio/' . $newFileName);
        }
    }

    #[Route('/admin/portfolio/edit/{id}', name: 'edit_portfolio')]
    public function edit($id, Request $request): Response
    {
        // Trouver le portfolio par son ID
        $portfolio = $this->portfolioRepository->find($id);

        if (!$portfolio){
            throw $this->createNotFoundException('Portfolio non trouvé');
        }

        $form = $this->createForm(PortfolioFormType::class, $portfolio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPortfolio = $form->getData();
            
            // Obtenir la valeur de la catégorie sélectionnée dans le formulaire
            $selectedCategory = $form->get('category')->getData();

            // Effectuer les vérifications et définir les catégories en conséquence
            if ($selectedCategory === 'React') {
                $newPortfolio->setCategory('React');
            } elseif ($selectedCategory === 'SQL') {
                $newPortfolio->setCategory('SQL');
            } elseif ($selectedCategory === 'Symfony') {
                $newPortfolio->setCategory('Symfony');
            } elseif ($selectedCategory === 'APIs') {
                $newPortfolio->setCategory('APIs');
            }

            // Télécharger la première image
            $this->uploadImage($form, 'image', $newPortfolio);

            $this->em->persist($newPortfolio);
            $this->em->flush();

            // Ajouter un message flash pour la réussite de la mise à jour
            $this->addFlash('success', 'Le portfolio a été mis à jour avec succès !');

            return $this->redirectToRoute('app_admin_portfolio');
        }

        // Récupérer les messages de la base de données
        $messages = $this->messagesRepository->findAll();
        
        return $this->render('backend/portfolio/edit.html.twig', [
            'portfolioForm' => $form->createView(),
            'portfolio' => $portfolio,
            'mails' => $messages
        ]);
    }

    #[Route('/admin/portfolio/delete/{id}', methods:['GET', 'DELETE'], name: 'delete_portfolio')]
    public function delete($id): Response
    {
        $portfolio = $this->portfolioRepository->find($id);

        $this->em->remove($portfolio);
        $this->em->flush();

        // Ajouter un message flash pour la réussite de la suppression
        $this->addFlash('success', 'Le portfolio a été supprimé avec succès !');

        return $this->redirectToRoute('app_admin_portfolio');
    }
}
