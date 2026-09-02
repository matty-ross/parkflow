<?php

namespace App\Controller\Admin;

use App\Entity\Record;
use App\Form\RecordType;
use App\Repository\RecordRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/records', name: 'app_admin_records')]
final class RecordController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
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

    #[Route('/{id<\d+>}/edit', name: '_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Record $record): Response
    {
        $form = $this->createForm(RecordType::class, $record);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('notice', 'result.record_edited');

            return $this->redirectToRoute('app_admin_records_index', status: Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/records/edit.html.twig', [
            'record' => $record,
            'form' => $form,
        ]);
    }
}
