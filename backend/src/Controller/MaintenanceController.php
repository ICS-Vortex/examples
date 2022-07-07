<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MaintenanceController extends AbstractController
{
    /**
     * @Route("/maintenance", name="maintenance.index")
     */
    public function index() {
        return $this->render('maintenance.html.twig', []);
    }
}
