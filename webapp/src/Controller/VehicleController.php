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
#[IsGranted(User::ROLE_USER)]
final class VehicleController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private VehicleRepository $vehicleRepository,
    ) {}

    #[Route('', name: '_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('vehicles/index.html.twig', [
            'vehicles' => $this->vehicleRepository->findBy(['owner' => $this->getUser()]),
        ]);
    }

    #[Route('/{id<\d+>}', name: '_show', methods: ['GET'])]
    public function show(Vehicle $vehicle): Response
    {
        if ($vehicle->getOwner() !== $this->getUser()) {
            throw $this->createNotFoundException();
        }

        return $this->render('vehicles/show.html.twig', [
            'vehicle' => $vehicle,
        ]);
    }

    #[Route('/create', name: '_create', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $vehicle = new Vehicle();
        $form = $this->createForm(VehicleType::class, $vehicle, [
            'admin' => false,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vehicle->setOwner($this->getUser());

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
        if ($vehicle->getOwner() !== $this->getUser()) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(VehicleType::class, $vehicle, [
            'admin' => false,
        ]);
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
    public function delete(Request $request, Vehicle $vehicle): Response
    {
        if ($vehicle->getOwner() !== $this->getUser()) {
            throw $this->createNotFoundException();
        }

        if ($this->isCsrfTokenValid('app_vehicles_delete'.$vehicle->getId(), $request->getPayload()->getString('_token'))) {
            $this->entityManager->remove($vehicle);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_vehicles_index', status: Response::HTTP_SEE_OTHER);
    }
}
