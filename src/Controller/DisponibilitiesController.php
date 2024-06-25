<?php

namespace App\Controller;

use App\Entity\Disponibilities;
use App\Entity\Vehicles;
use App\Form\DisponibilitiesType;
use App\Form\FilterType;
use App\Repository\DisponibilitiesRepository;
use App\Repository\VehiclesRepository;
use App\Service\FilterService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/disponibilities')]
class DisponibilitiesController extends AbstractController
{
    #[Route('/', name: 'app_disponibilities_index', methods: ['GET', 'POST'])]
    public function index(Request $request, DisponibilitiesRepository $disponibilitiesRepository): Response
    {
        $filterForm = $this->createForm(FilterType::class);
        $filterForm->handleRequest($request);

        if ($filterForm->isSubmitted()) {
            $departureDate = $filterForm->get('departureDate')->getData();
            $returnDate = $filterForm->get('returnDate')->getData();

            if ($departureDate != null && $returnDate !== null) {
                if ($departureDate > $returnDate) {
                    $filterForm->get('returnDate')->addError(new FormError('La date de retour ne peut pas être inférieur à la date de départ.'));
                }
            }

            if ($filterForm->isValid()) {

                $filter_service = new FilterService($filterForm);
                $disponibilities = $filter_service->getFilterData($disponibilitiesRepository->findAll());

                $filterContent = $this->renderView('components/_filter_content.html.twig', [
                    'disponibilities' => $disponibilities,
                ]);

                return new JsonResponse($filterContent, 200);
            } else {

                $form =  $this->renderView('components/_filter_form.html.twig', [
                    'filterForm' => $filterForm
                ]);

                return new JsonResponse($form, 400);
            }
        }

        return $this->render('disponibilities/index.html.twig', [
            'disponibilities' => $disponibilitiesRepository->findBy(['status' => 1]),
            'filterForm' => $filterForm
        ]);
    }

    #[Route('/new', name: 'app_disponibilities_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, VehiclesRepository $vehiclesRepository, DisponibilitiesRepository $disponibilitiesRepository): Response
    {
        $disponibility = new Disponibilities();
        $form = $this->createForm(DisponibilitiesType::class, $disponibility);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $vehicleIsExist = $form->get('radio_vehicle')->getData();

            if ($disponibility->getDepartureDate() > $disponibility->getReturnDate()) {
                $form->get('returnDate')->addError(new FormError('La date de retour ne peut pas être inférieur à la date de départ.'));
            }

            if ($vehicleIsExist && $this->overlaps($disponibility, $disponibility->getDepartureDate(), $disponibility->getReturnDate(), $disponibilitiesRepository)) {
                $form->get('returnDate')->addError(new FormError('Les dates choisies se chevauchent avec d\'autres dates.'));
            }

            if ($form->isValid()) {
                if (!$vehicleIsExist) {
                    $this->createNewVehicle($form, $disponibility, $entityManager, $vehiclesRepository);
                }

                $entityManager->persist($disponibility);
                $entityManager->flush();

                return new JsonResponse(true);
            }
        }

        $formContent = $this->renderView('disponibilities/new.html.twig', [
            'disponibility' => $disponibility,
            'form' => $form,
        ]);

        return new JsonResponse($formContent);
    }

    #[Route('/{id}/edit', name: 'app_disponibilities_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Disponibilities $disponibility, EntityManagerInterface $entityManager,  VehiclesRepository $vehiclesRepository, DisponibilitiesRepository $disponibilitiesRepository): Response
    {
        $form = $this->createForm(DisponibilitiesType::class, $disponibility);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $vehicleIsExist = $form->get('radio_vehicle')->getData();

            if ($form->get('departureDate')->getData() > $form->get('returnDate')->getData()) {
                $form->get('returnDate')->addError(new FormError('La date de retour ne peut pas être inférieur à la date de départ.'));
            }
            if ($vehicleIsExist && $this->overlaps($disponibility, $disponibility->getDepartureDate(), $disponibility->getReturnDate(), $disponibilitiesRepository)) {
                $form->get('returnDate')->addError(new FormError('Les dates choisies se chevauchent avec d\'autres dates.'));
            }
            if ($form->isValid()) {
                if (!$vehicleIsExist) {
                    $this->createNewVehicle($form, $disponibility, $entityManager, $vehiclesRepository);
                }

                $entityManager->persist($disponibility);
                $entityManager->flush();

                return new JsonResponse(true);
            }
        }

        $formContent = $this->renderView('disponibilities/edit.html.twig', [
            'disponibility' => $disponibility,
            'form' => $form,
        ]);
        return new JsonResponse($formContent);
    }

    #[Route('/{id}/delete', name: 'app_disponibilities_deleteForm', methods: ['GET'])]
    public function deleteForm(Disponibilities $disponibility): Response
    {

        $formContent = $this->renderView('disponibilities/_delete_form.html.twig', [
            'disponibility' => $disponibility
        ]);
        return new JsonResponse($formContent);
    }

    #[Route('/{id}', name: 'app_disponibilities_delete', methods: ['POST'])]
    public function delete(Request $request, Disponibilities $disponibility, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $disponibility->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($disponibility);
            $entityManager->flush();
        }

        return new JsonResponse(true);
    }

    private function createNewVehicle($form, $disponibility, EntityManagerInterface $entityManager, VehiclesRepository $vehiclesRepository)
    {
        $vehicle = new Vehicles();

        $brand = $form->get('vehicle_brand')->getData();
        $model = $form->get('vehicle_model')->getData();
        $vehicle->setBrand($brand);
        $vehicle->setModel($model);

        $isVehicle = $vehiclesRepository->hasVehicle($vehicle);
        if (!$isVehicle) {
            $entityManager->persist($vehicle);
            $entityManager->flush();

            $disponibility->setVehicle($vehicle);
        }
    }

    private function overlaps($disponibility_current, $start, $end, DisponibilitiesRepository $disponibilitiesRepository)
    {
        $disponibilities = $disponibilitiesRepository->findByVehicle($disponibility_current->getVehicle());
        $isOverlaps = false;

        foreach ($disponibilities as $disponibility) {
            if ($disponibility_current->getId() != $disponibility->getId()) {
                if (($start >= $disponibility->getDepartureDate() && $start <= $disponibility->getReturnDate()) ||
                    ($end >= $disponibility->getDepartureDate() && $end <= $disponibility->getReturnDate())
                ) {
                    $isOverlaps = true;
                }
            }
        }
        return $isOverlaps;
    }
}
