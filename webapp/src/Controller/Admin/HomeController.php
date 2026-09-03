<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin', name: 'app_admin_home')]
final class HomeController extends AbstractController
{
    #[Route('', name: '_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        return $this->render('admin/home/index.html.twig');
    }

    #[Route('/locale/{_locale}', name: '_locale', methods: ['GET'])]
    public function locale(Request $request): Response
    {
        return $this->redirectToRoute('app_admin_home_index', status: Response::HTTP_SEE_OTHER);
    }
}
