<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
class MessagesController extends AbstractController
{
    private $em;
    private $messagesRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->messagesRepository = $em->getRepository(Contact::class);
    }

    #[Route('/admin/messages', name: 'app_messages')]
    public function index(): Response
    {
        // Récupérer les mails de la base de données
        $messages = $this->messagesRepository->findAll();

        return $this->render('backend/messages/index.html.twig', [
            'mails' => $messages
        ]);
    }

    #[Route('/admin/messages/delete/{id}', methods:['GET', 'DELETE'], name: 'delete_mail')]
    public function delete($id): Response
    {
        $message = $this->messagesRepository->find($id);

        $this->em->remove($message);
        $this->em->flush();

        // Ajouter un message flash pour la réussite de la suppression
        $this->addFlash('success', 'Le message a été supprimé avec succès !');

        return $this->redirectToRoute('app_messages');
    }
}
