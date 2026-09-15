<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/{_locale?%kernel.default_locale%}', name: 'app_home')]
#[IsGranted(User::ROLE_ADMIN)]
final class HomeController extends AbstractController
{
    #[Route('', name: '_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        return $this->render('home/index.html.twig');
    }
}
