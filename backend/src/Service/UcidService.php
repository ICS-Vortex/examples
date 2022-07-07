<?php

namespace App\Service;

use App\Constant\Parameter;
use App\Entity\Instance;
use App\Entity\UcidToken;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class UcidService
{
    private EntityManagerInterface $manager;
    private RequestStack $requestStack;
    /**
     * @var UcidToken|mixed|object|null
     */
    private UcidToken $token;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        $this->requestStack = $requestStack;
    }

    public function tokenIsValid(): bool
    {
        $request = $this->requestStack->getCurrentRequest();
        if ($request === null) {
            return false;
        }

        $token = $request->headers->get(Parameter::DCS_PILOT_HEADER, null);
        if ($token === null) {
            return false;
        }

        /** @var Instance $instance */
        $token = $this->manager->getRepository(UcidToken::class)->findOneBy([
            'token' => $token,
        ]);

        if (empty($token)) {
            return false;
        }
        $this->token = $token;
        if (time() > $token->getExpires()) {
            return false;
        }

        return true;
    }

    /**
     * @return UcidToken|null
     */
    public function refreshToken(): ?UcidToken
    {
        if (empty($this->token)) {
            return null;
        }
        $this->token->setExpires((new DateTime())->add(new DateInterval("PT12H"))->getTimestamp());
        $this->manager->persist($this->token);
        $this->manager->flush();
        return $this->token;
    }

    public function getToken()
    {
        return $this->token;
    }
}
