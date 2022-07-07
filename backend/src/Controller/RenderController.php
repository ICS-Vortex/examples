<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RenderController
 * @package App\Controller
 * @Route("/render")
 */
class RenderController extends AbstractController
{
    /**
     * @Route("/email", name="render.account_email")
     */
    public function email()
    {
        return $this->render('emails/base.html.twig');
    }
}