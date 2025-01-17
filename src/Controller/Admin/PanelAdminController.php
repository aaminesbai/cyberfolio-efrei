<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
class PanelAdminController extends AbstractController
{
    private $em;
    private $messagesRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->messagesRepository = $em->getRepository(Contact::class);
    }
    
    #[Route('/admin', name: 'app_dashboard')]
    public function index(): Response
    {
        // Récupérer les messages de la base de données
        $messages = $this->messagesRepository->findAll();

        return $this->render('backend/dashboard/index.html.twig', [
            'mails' => $messages,
        ]);
    }
}
