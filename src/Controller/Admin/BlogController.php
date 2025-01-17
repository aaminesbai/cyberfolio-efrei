<?php

namespace App\Controller\Admin;

use App\Entity\Blog;
use App\Entity\Contact;
use App\Form\BlogFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[IsGranted('ROLE_USER')]
class BlogController extends AbstractController
{
    private $em;
    private $blogRepository;
    private $messagesRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->blogRepository = $em->getRepository(Blog::class);
        $this->messagesRepository = $em->getRepository(Contact::class);
    }
    
    #[Route('/admin/blog', name: 'app_admin_blog')]
    public function index(): Response
    {
        // Récupérer les blogs de la base de données
        $blogs = $this->blogRepository->findAll();

        // Récupérer les messages de la base de données
        $messages = $this->messagesRepository->findAll();

        return $this->render('backend/blog/index.html.twig', [
            'blogs' => $blogs,
            'mails' => $messages
        ]);
    }

    #[Route('/admin/blog/create', name: 'create_blog')]
    public function create(Request $request): Response
    {
        $blog = new Blog();

        $form = $this->createForm(BlogFormType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newBlog = $form->getData();
            $image = $form->get('image')->getData();

            if ($image) {
                $newFileName = uniqid() . '.' . $image->guessExtension();
                try {
                    $image->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads/blog',
                        $newFileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }
                $newBlog->setImage('/uploads/blog/' . $newFileName);
            }

            $this->em->persist($newBlog);
            $this->em->flush();

            // Ajouter un message flash pour la réussite de la création
            $this->addFlash('success', 'Le blog a été créé avec succès !');

            return $this->redirectToRoute('app_admin_blog');
        }

        // Récupérer les messages de la base de données
        $messages = $this->messagesRepository->findAll();
        
        return $this->render('backend/blog/create.html.twig', [
            'blogForm' => $form->createView(),
            'mails' => $messages
        ]);
    }

    #[Route('/admin/blog/edit/{id}', name: 'edit_blog')]
    public function edit($id, Request $request): Response
    {
        // Trouver le blog par son ID
        $blog = $this->blogRepository->find($id);

        if (!$blog){
            throw $this->createNotFoundException('Blog non trouvé');
        }

        $form = $this->createForm(BlogFormType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $newBlog = $form->getData();

            $image = $form->get('image')->getData();
            if ($image) {
                $newFileName = uniqid() . '.' . $image->guessExtension();
                try {
                    $image->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads/blog',
                        $newFileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }
                $newBlog->setImage('/uploads/blog/' . $newFileName);
            }

            $this->em->persist($newBlog);
            $this->em->flush();

            // Ajouter un message flash pour la réussite de la mise à jour
            $this->addFlash('success', 'Le blog a été mis à jour avec succès !');

            return $this->redirectToRoute('app_admin_blog');
        }
        
        // Récupérer les messages de la base de données
        $messages = $this->messagesRepository->findAll();

        return $this->render('backend/blog/edit.html.twig', [
            'blogForm' => $form->createView(),
            'mails' => $messages,
            'blog' => $blog
        ]);
    }

    #[Route('/admin/blog/delete/{id}', methods:['GET', 'DELETE'], name: 'delete_blog')]
    public function delete($id): Response
    {
        
        $blog = $this->blogRepository->find($id);

        $this->em->remove($blog);
        $this->em->flush();

        // Ajouter un message flash pour la réussite de la suppression
        $this->addFlash('success', 'Le blog a été supprimé avec succès !');

        return $this->redirectToRoute('app_admin_blog');
    }
}
