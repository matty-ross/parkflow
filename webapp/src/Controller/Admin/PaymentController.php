<?php

namespace App\Controller\Admin;

use App\Entity\Payment;
use App\Repository\PaymentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/{_locale}/payments', name: 'app_admin_payments')]
final class PaymentController extends AbstractController
{
    public function __construct(
        private PaymentRepository $paymentRepository,
    ) {}

    #[Route('', name: '_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        return $this->render('admin/payments/index.html.twig', [
            'payments' => $this->paymentRepository->findBy(criteria: [], orderBy: ['paidAt' => 'DESC']),
        ]);
    }

    #[Route('/{id<\d+>}', name: '_show', methods: ['GET'])]
    public function show(Request $request, Payment $payment): Response
    {
        return $this->render('admin/payments/show.html.twig', [
            'payment' => $payment,
        ]);
    }
}
