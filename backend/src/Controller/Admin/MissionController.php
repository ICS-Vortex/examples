<?php

namespace App\Controller\Admin;

use App\Entity\MissionRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class MissionController
 * @package App\Controller\Admin
 * @Route("/admin/missions")
 */
class MissionController extends AbstractController
{
    /**
     * @param MissionRegistry $registry
     * @Route("/{registry}/statistics.html", name="admin.missions.statistics")
     * @return Response
     */
    public function statistics(MissionRegistry $registry)
    {
        if (!$this->getUser()->hasAccessToMission($registry)) {
            throw new AccessDeniedException('Unable to access this page!');
        }
        $aaPVP = $this->getDoctrine()->getRepository(MissionRegistry::class)->getA2APVP($registry);
        $aaPVE = $this->getDoctrine()->getRepository(MissionRegistry::class)->getA2APVE($registry);
        $agPVE = $this->getDoctrine()->getRepository(MissionRegistry::class)->getA2GPVE($registry);

        return $this->render('admin/missions/statistics.html.twig', [
            'registry' => $registry,
            'aaPVP' => $aaPVP,
            'aaPVE' => $aaPVE,
            'agPVE' => $agPVE,
        ]);
    }
}
