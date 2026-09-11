<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/{_locale}/profile', name: 'app_profile')]
#[IsGranted(User::ROLE_USER)]
final class ProfileController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
    ) {}

    #[Route('', name: '_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/edit', name: '_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request): Response
    {
        /** @var User */
        $user = $this->getUser();

        $form = $this->createForm(UserType::class, $user, [
            'edit' => true,
            'admin' => false,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($password = $form['password']->getData()) {
                $user->setPassword($this->passwordHasher->hashPassword($user, $password));
            }

            $this->entityManager->flush();

            $this->addFlash('notice', 'result.profile_edited');

            return $this->redirectToRoute('app_profile_index', status: Response::HTTP_SEE_OTHER);
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form,
        ]);
    }
}
