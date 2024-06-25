<?php

namespace App\Controller;

use App\Entity\Vehicles;
use App\Form\VehiclesType;
use App\Repository\VehiclesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/vehicules')]
class VehiclesController extends AbstractController
{
    #[Route('/', name: 'app_vehicles_index', methods: ['GET'])]
    public function index(VehiclesRepository $vehiclesRepository): Response
    {
        return $this->render('vehicles/index.html.twig', [
            'vehicles' => $vehiclesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_vehicles_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, VehiclesRepository $vehiclesRepository): Response
    {
        $vehicle = new Vehicles();
        $form = $this->createForm(VehiclesType::class, $vehicle);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            if ($vehiclesRepository->hasVehicle($vehicle)) {
                $form->get('model')->addError(new FormError('Ce model a déjà été ajouter.'));
            }

            if ($form->isValid()) {
                $entityManager->persist($vehicle);
                $entityManager->flush();

                return new JsonResponse(true);
            }
        }

        $formContent = $this->renderView('vehicles/new.html.twig', [
            'vehicle' => $vehicle,
            'form' => $form,
        ]);

        return new JsonResponse($formContent);
    }

    #[Route('/{id}/edit', name: 'app_vehicles_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Vehicles $vehicle, EntityManagerInterface $entityManager, VehiclesRepository $vehiclesRepository): Response
    {
        $form = $this->createForm(VehiclesType::class, $vehicle);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($vehiclesRepository->hasVehicle($vehicle)) {
                $form->get('model')->addError(new FormError('Ce model a déjà été ajouter.'));
            }

            if ($form->isValid()) {
                $entityManager->flush();

                return new JsonResponse(true);
            }
        }

        $formContent = $this->renderView('vehicles/edit.html.twig', [
            'vehicle' => $vehicle,
            'form' => $form,
        ]);

        return new JsonResponse($formContent);
    }

    #[Route('/{id}/delete', name: 'app_vehicles_deleteForm', methods: ['GET'])]
    public function deleteForm(Request $request, Vehicles $vehicle, EntityManagerInterface $entityManager): Response
    {

        $formContent = $this->renderView('vehicles/_delete_form.html.twig', [
            'vehicle' => $vehicle,
        ]);
        return new JsonResponse($formContent);
    }


    #[Route('/{id}', name: 'app_vehicles_delete', methods: ['POST'])]
    public function delete(Request $request, Vehicles $vehicle, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $vehicle->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($vehicle);
            $entityManager->flush();
        }

        return new JsonResponse(true);
    }

    #[Route('/{id}', name: 'app_vehicles_show', methods: ['GET'])]
    public function show(Vehicles $vehicle): Response
    {
        $disponibilities = $vehicle->getDisponibilities();

        return $this->render('vehicles/show.html.twig', [
            'vehicle' => $vehicle,
            'disponibilities' => $disponibilities,
        ]);
    }
}
