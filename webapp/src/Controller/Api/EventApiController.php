<?php

namespace App\Controller\Api;

use App\Entity\Event;
use App\Model\EventDto;
use App\Repository\VehicleRepository;
use App\Service\SnapshotService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/events', name: 'app_api_events', format: 'json')]
// #[IsGranted(User::ROLE_ADMIN)]
final class EventApiController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private SnapshotService $snapshotService,
        private VehicleRepository $vehicleRepository,
    ) {}

    #[Route('', name: '_create', methods: ['POST'])]
    public function create(Request $request, #[MapRequestPayload] EventDto $eventDto): JsonResponse
    {
        $vehicle = $this->vehicleRepository->findOneBy(['licensePlate' => $eventDto->licensePlate]);

        $snapshotUuid = null;
        if ($eventDto->snapshot) {
            $stream = fopen($eventDto->snapshot->getPathname(), 'rb');
            $snapshotUuid = $this->snapshotService->save($stream);
            fclose($stream);
        }

        $event = new Event()
            ->setAction($eventDto->action)
            ->setLicensePlate($eventDto->licensePlate)
            ->setRecognizedUser($vehicle?->getOwner())
            ->setSnapshotUuid($snapshotUuid)
        ;

        $this->entityManager->persist($event);
        $this->entityManager->flush();

        return $this->json([
            'id' => $event->getId(),
        ]);
    }
}
