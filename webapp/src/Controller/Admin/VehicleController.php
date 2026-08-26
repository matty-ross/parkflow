<?php

namespace App\Controller\Admin;

use App\Entity\Vehicle;
use App\Form\VehicleType;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/vehicle', name: 'app_admin_vehicles')]
final class VehicleController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private VehicleRepository $vehicleRepository,
    ) {}

    #[Route('', name: '_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        return $this->render('admin/vehicles/index.html.twig', [
            'vehicles' => $this->vehicleRepository->findAll(),
        ]);
    }

    #[Route('/{id<\d+>}', name: '_show', methods: ['GET'])]
    public function show(Request $request, Vehicle $vehicle): Response
    {
        return $this->render('admin/vehicles/show.html.twig', [
            'vehicle' => $vehicle,
        ]);
    }

    #[Route('/new', name: '_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $vehicle = new Vehicle();
        $form = $this->createForm(VehicleType::class, $vehicle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($vehicle);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_admin_vehicles_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/vehicles/new.html.twig', [
            'vehicle' => $vehicle,
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

            return $this->redirectToRoute('app_admin_vehicles_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/vehicles/edit.html.twig', [
            'vehicle' => $vehicle,
            'form' => $form,
        ]);
    }

    #[Route('/{id<\d+>}/delete', name: '_delete', methods: ['POST'])]
    public function delete(Request $request, Vehicle $vehicle, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('app_admin_vehicles_delete'.$vehicle->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($vehicle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_vehicles_index', [], Response::HTTP_SEE_OTHER);
    }
}
