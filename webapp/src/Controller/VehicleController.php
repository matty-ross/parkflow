<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Vehicle;
use App\Form\VehicleType;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/{_locale}/vehicles', name: 'app_vehicles')]
#[IsGranted(User::ROLE_ADMIN)]
final class VehicleController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private VehicleRepository $vehicleRepository,
    ) {}

    #[Route('', name: '_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        return $this->render('vehicles/index.html.twig', [
            'vehicles' => $this->vehicleRepository->findBy(criteria: [], orderBy: ['createdAt' => 'DESC']),
        ]);
    }

    #[Route('/{id<\d+>}', name: '_show', methods: ['GET'])]
    public function show(Request $request, Vehicle $vehicle): Response
    {
        return $this->render('vehicles/show.html.twig', [
            'vehicle' => $vehicle,
        ]);
    }

    #[Route('/create', name: '_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $vehicle = new Vehicle();
        $form = $this->createForm(VehicleType::class, $vehicle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($vehicle);
            $this->entityManager->flush();

            $this->addFlash('notice', 'result.vehicle_created');

            return $this->redirectToRoute('app_vehicles_index', status: Response::HTTP_SEE_OTHER);
        }

        return $this->render('vehicles/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id<\d+>}/edit', name: '_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Vehicle $vehicle): Response
    {
        $form = $this->createForm(VehicleType::class, $vehicle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('notice', 'result.vehicle_edited');

            return $this->redirectToRoute('app_vehicles_index', status: Response::HTTP_SEE_OTHER);
        }

        return $this->render('vehicles/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id<\d+>}/delete', name: '_delete', methods: ['POST'])]
    public function delete(Request $request, Vehicle $vehicle, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('app_vehicles_delete'.$vehicle->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($vehicle);
            $entityManager->flush();

            $this->addFlash('notice', 'result.vehicle_deleted');
        }

        return $this->redirectToRoute('app_vehicles_index', status: Response::HTTP_SEE_OTHER);
    }
}
