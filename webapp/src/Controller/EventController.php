<?php

namespace App\Controller;

use App\DataTables\EventsDataTable;
use App\Entity\Event;
use App\Entity\User;
use App\Form\EventType;
use App\Service\SnapshotService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/{_locale}/events', name: 'app_events')]
#[IsGranted(User::ROLE_ADMIN)]
final class EventController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private SnapshotService $snapshotService,
    ) {}

    #[Route('', name: '_index', methods: ['GET'])]
    public function index(Request $request, EventsDataTable $table): Response
    {
        $table->handleRequest($request);

        if ($table->isRequestHandled()) {
            return $table->getResponse();
        }

        return $this->render('events/index.html.twig', [
            'table' => $table,
        ]);
    }

    #[Route('/{id<\d+>}', name: '_show', methods: ['GET'])]
    public function show(Request $request, Event $event): Response
    {
        return $this->render('events/show.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/{id<\d+>}/snapshot', name: '_snapshot', methods: ['GET'])]
    public function snapshot(Request $request, Event $event): Response
    {
        $uuid = $event->getSnapshotUuid();
        if (!$uuid) {
            throw $this->createNotFoundException();
        }

        $stream = $this->snapshotService->serve($uuid);
        if (!$stream) {
            throw $this->createNotFoundException();
        }

        return new StreamedResponse(
            callbackOrChunks: function () use ($stream): void {
                fpassthru($stream);
                fclose($stream);
            },
            headers: [
                'Content-Type' => SnapshotService::MIME_TYPE,
                'Content-Length' => $this->snapshotService->size($uuid) ?? 0,
            ],
        );
    }

    #[Route('/{id<\d+>}/edit', name: '_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Event $event): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('notice', 'result.event_edited');

            return $this->redirectToRoute('app_events_index', status: Response::HTTP_SEE_OTHER);
        }

        return $this->render('events/edit.html.twig', [
            'form' => $form,
        ]);
    }
}
