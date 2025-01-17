<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/contact', name: 'app_contact')]
    public function createContact(Request $request): Response
    {
        // Créer un nouveau message de contact
        $contact = new Contact();
        
        // Créer le formulaire et gérer la requête
        $form = $this->createForm(ContactFormType::class, $contact);
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide, utilisateur = persiste dans la base de données
        if ($form->isSubmitted() && $form->isValid()) {

            // Persister l'utilisateur dans la base de données
            $this->em->persist($contact);
            $this->em->flush();

            // Ajouter un message flash et rediriger vers la page principale
            $this->addFlash('success', 'Votre message a été envoyé avec succès !');
            return $this->redirectToRoute('app_contact');
        } 

        return $this->render('frontend/contact/index.html.twig', [
            'contactForm' => $form->createView()
        ]);
    }   
}
