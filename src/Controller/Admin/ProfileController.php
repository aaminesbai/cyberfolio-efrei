<?php

namespace App\Controller\Admin;

use App\Entity\Utilisateurs;
use App\Entity\Contact;
use App\Form\ProfileFormType;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[IsGranted('ROLE_USER')]
class ProfileController extends AbstractController
{
    private $em;
    private $profileRepository;
    private $passwordEncoder;
    private $messagesRepository;

    public function __construct(EntityManagerInterface $em, UserPasswordHasherInterface $passwordEncoder)
    {
        $this->em = $em;
        $this->profileRepository = $em->getRepository(Utilisateurs::class);
        $this->messagesRepository = $em->getRepository(Contact::class);
        $this->passwordEncoder = $passwordEncoder;
    }

    #[Route('/admin/profil/{id}', name: 'app_profile')]
    public function update($id, Request $request): Response
    {
        // Trouver le profil par son ID
        $profile = $this->profileRepository->find($id);

        if (!$profile) {
            throw $this->createNotFoundException('Profil non trouvé');
        }

        $form = $this->createForm(ProfileFormType::class, $profile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Mettre à jour le mot de passe uniquement si un nouveau mot de passe est fourni
            if ($newPassword = $form->get('password')->getData()) {
                $encodedPassword = $this->passwordEncoder->hashPassword($profile, $newPassword);
                $profile->setPassword($encodedPassword);
            }

            $this->em->persist($profile);
            $this->em->flush();

            // Ajouter un message flash pour indiquer le succès de la mise à jour
            $this->addFlash('success', 'Le profil a été mis à jour avec succès !');

            return $this->redirectToRoute('app_profile', ['id' => 1]);

        }

        // Récupérer les messages de la base de données
        $messages = $this->messagesRepository->findAll();

        return $this->render('backend/profile/index.html.twig', [
            'profileForm' => $form->createView(),
            'mails' => $messages
        ]);
    }
}