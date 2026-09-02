<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/users', name: 'app_admin_users')]
final class UserController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private UserRepository $userRepository,
    ) {}

    #[Route('', name: '_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        return $this->render('admin/users/index.html.twig', [
            'users' => $this->userRepository->findBy(criteria: [], orderBy: ['createdAt' => 'DESC']),
        ]);
    }

    #[Route('/{id<\d+>}', name: '_show', methods: ['GET'])]
    public function show(Request $request, User $user): Response
    {
        return $this->render('admin/users/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/create', name: '_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user, [
            'edit' => false,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form['password']->getData();
            $user->setPassword($this->passwordHasher->hashPassword($user, $password));

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash('notice', 'result.user_created');

            return $this->redirectToRoute('app_admin_users_index', status: Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/users/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id<\d+>}/edit', name: '_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user, [
            'edit' => true,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($password = $form['password']->getData()) {
                $user->setPassword($this->passwordHasher->hashPassword($user, $password));
            }

            $this->entityManager->flush();

            $this->addFlash('notice', 'result.user_edited');

            return $this->redirectToRoute('app_admin_users_index', status: Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/users/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id<\d+>}/delete', name: '_delete', methods: ['POST'])]
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('app_admin_users_delete'.$user->getId(), $request->getPayload()->getString('_token'))) {
            $this->entityManager->remove($user);
            $this->entityManager->flush();

            $this->addFlash('notice', 'result.user_deleted');
        }

        return $this->redirectToRoute('app_admin_users_index', status: Response::HTTP_SEE_OTHER);
    }
}
