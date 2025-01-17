<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use App\Entity\Recommendations;
use App\Form\RecommendationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[IsGranted('ROLE_USER')]
class RecommendationsController extends AbstractController
{
    private $em;
    private $recommendationsRepository;
    private $messagesRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->recommendationsRepository = $em->getRepository(Recommendations::class);
        $this->messagesRepository = $em->getRepository(Contact::class);
    }
    
    #[Route('/admin/recommendations', name: 'app_recommendations')]
    public function index(): Response
    {
        // Récupérer les recommendations de la base de données
        $recommendations = $this->recommendationsRepository->findAll();

        // Récupérer les messages de la base de données
        $messages = $this->messagesRepository->findAll();

        return $this->render('backend/recommendations/index.html.twig', [
            'testimonials' => $recommendations,
            'mails' => $messages
        ]);
    }

    #[Route('/admin/recommendations/create', name: 'create_recommendation')]
    public function create(Request $request): Response
    {
        $recommendations = new Recommendations();

        $form = $this->createForm(RecommendationFormType::class, $recommendations);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newRecommendation = $form->getData();
            $image = $form->get('image')->getData();

            if ($image) {
                $newFileName = uniqid() . '.' . $image->guessExtension();
                try {
                    $image->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads/recommendations',
                        $newFileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }
                $newRecommendation->setImage('/uploads/recommendations/' . $newFileName);
            }

            $this->em->persist($newRecommendation);
            $this->em->flush();

            // Ajouter un message flash pour la réussite de la création
            $this->addFlash('success', 'La recommendation a été créé avec succès!');

            return $this->redirectToRoute('app_recommendations');
        }
        
        // Récupérer les messages de la base de données
        $messages = $this->messagesRepository->findAll();

        return $this->render('backend/recommendations/create.html.twig', [
            'testimonialForm' => $form->createView(),
            'mails' => $messages
        ]);
    }


    #[Route('/admin/messages/edit/{id}', name: 'edit_recommendation')]
    public function edit($id, Request $request): Response
    {
        // Trouver la recommendation par son ID
        $recommendation = $this->recommendationsRepository->find($id);

        if (!$recommendation){
            throw $this->createNotFoundException('Recommendation non trouvé');
        }

        $form = $this->createForm(RecommendationFormType::class, $recommendation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $newRecommendation = $form->getData();
            $image = $form->get('image')->getData();

            if ($image) {
                $newFileName = uniqid() . '.' . $image->guessExtension();
                try {
                    $image->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads/recommendations',
                        $newFileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }
                $newRecommendation->setImage('/uploads/recommendations/' . $newFileName);
            }

            $this->em->persist($newRecommendation);
            $this->em->flush();

            // Ajouter un message flash pour la réussite de la mise à jour
            $this->addFlash('success', 'La recommendation a été mis à jour avec succès!');

            return $this->redirectToRoute('app_recommendations');
        }

        // Récupérer les messages de la base de données
        $messages = $this->messagesRepository->findAll();
        
        return $this->render('backend/recommendations/edit.html.twig', [
            'testimonialForm' => $form->createView(),
            'mails' => $messages,
            'testimonial' => $recommendations
        ]);
    }


    #[Route('/admin/recommendations/delete/{id}', methods:['GET', 'DELETE'], name: 'delete_recommendation')]
    public function delete($id): Response
    {
        
        $recommendation = $this->recommendationsRepository->find($id);

        $this->em->remove($recommendation);
        $this->em->flush();

        // Ajouter un message flash pour la réussite de la suppression
        $this->addFlash('success', 'La recommendation a été supprimé avec succès!');

        return $this->redirectToRoute('app_recommendations');
    }
}
