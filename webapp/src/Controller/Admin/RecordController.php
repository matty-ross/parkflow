<?php

namespace App\Controller\Admin;

use App\Entity\Record;
use App\Repository\RecordRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/records', name: 'app_admin_records')]
final class RecordController extends AbstractController
{
    public function __construct(
        private RecordRepository $recordRepository,
    ) {}

    #[Route('', name: '_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        return $this->render('admin/records/index.html.twig', [
            'records' => $this->recordRepository->findBy(criteria: [], orderBy: ['enteredAt' => 'DESC']),
        ]);
    }

    #[Route('/{id<\d+>}', name: '_show', methods: ['GET'])]
    public function show(Request $request, Record $record): Response
    {
        return $this->render('admin/records/show.html.twig', [
            'record' => $record,
        ]);
    }
}
