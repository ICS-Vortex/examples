<?php

namespace App\Listener;

use App\Entity\Setting;
use App\Repository\SettingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\Routing\RouterInterface;

class RequestListener
{
    const ROUTE_MAINTENANCE = 'maintenance_index';

    /** @var ParameterBagInterface $parameterBag */
    protected ParameterBagInterface $parameterBag;

    /** @var EntityManagerInterface $em */
    protected EntityManagerInterface $em;

    /** @var RouterInterface $router */
    protected RouterInterface $router;

    public function __construct(ParameterBagInterface $parameterBag, EntityManagerInterface $entityManager,RouterInterface $router)
    {
        $this->parameterBag = $parameterBag;
        $this->em = $entityManager;
        $this->router = $router;
    }

    public function onKernelRequest(RequestEvent $event)
    {

        $request = $event->getRequest();
        $em = $this->em;
        $router = $this->router;
//        if (preg_match('/\/admin/i', $request->getRelativeUriForPath())) {
//            return; //TODO change check of the path (Route)
//        }
        if ($request->get('_route') === self::ROUTE_MAINTENANCE) {
            return;
        }

        /** @var Setting $mmOption */
        $mmOption = $em->getRepository(Setting::class)->findOneBy(['keyword' => SettingRepository::SETTING_APPLICATION_TITLE]);
        if (empty($mmOption)) {
            return;
        }

        if ($mmOption->isEnabled()) {
            $url = $router->generate('maintenance.index');
            $response = new RedirectResponse($url);
            $event->setResponse($response);
        }
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        $response  = $event->getResponse();
        $request   = $event->getRequest();
        $kernel    = $event->getKernel();
    }
}
