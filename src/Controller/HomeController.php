<?php

namespace App\Controller;

use App\Repository\VehiclesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(VehiclesRepository $vehiclesRepository): Response
    {
        return $this->redirectToRoute('app_vehicles_index');
    }
}
