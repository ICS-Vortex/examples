<?php

namespace App\Repository;

use App\Constant\Parameter;
use App\Entity\Server;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Entity\Model\Server as ServerModel;

/**
 * JsonMessagesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class JsonMessageRepository extends EntityRepository
{
    public function findByPage($page = 1, $max = 10)
    {
        $dql = $this->createQueryBuilder('message');
        $dql->orderBy('message.id', 'DESC');

        $firstResult = ($page - 1) * $max;

        $query = $dql->getQuery();
        $query->setFirstResult($firstResult);
        $query->setMaxResults($max);

        $paginator = new Paginator($query);

        if(($paginator->count() <=  $firstResult) && $page != 1) {
            throw new NotFoundHttpException('Page not found');
        }

        return $paginator;
    }

    public static function getInitiator($event, $array) {
        switch ($event) {
            case Parameter::EVENT_SERVER_Online:
                break;
            case Parameter::EVENT_SERVER_Offline:
                break;
//            case Parameter::EVENT_E:
//                return [
//                    'name' => $array['dcsEvent']['pilot']['callsign'],
//                    'ucid' => $array['dcsEvent']['pilot']['ucid'],
//                    'ipAddress' => $array['dcsEvent']['pilot']['ipAddress'],
//                ];
            case Parameter::EVENT_PILOT_Enter:
                return [
                    'name' => $array['pilot']['callsign'],
                    'ucid' => $array['pilot']['ucid'],
                    'ipAddress' => $array['pilot']['ipAddress'],
                ];
            case Parameter::EVENT_PILOT_Left:
                return [
                    'name' => $array['init']['nick'],
                    'ucid' => $array['init']['ucid'],
                    'ipAddress' => $array['init']['ip'],
                ];
            case Parameter::EVENT_PILOT_Join:
                return [
                    'name' => $array['init']['nick'],
                    'ucid' => $array['init']['ucid'],
                    'ipAddress' => $array['init']['ip'],
                ];
            case Parameter::EVENT_PILOT_Takeoff:
                return [
                    'name' => $array['takeoff']['pilot']['callsign'],
                    'ucid' => $array['takeoff']['pilot']['ucid'],
                    'ipAddress' => $array['takeoff']['pilot']['ipAddress'],
                ];
            case Parameter::EVENT_PILOT_Landed:
                return [
                    'name' => $array['landing']['pilot']['callsign'],
                    'ucid' => $array['landing']['pilot']['ucid'],
                    'ipAddress' => $array['landing']['pilot']['ipAddress'],
                ];
            case Parameter::EVENT_PILOT_Kill:
                return [
                    'name' => $array['airkill']['pilot']['callsign'],
                    'ucid' => $array['airkill']['pilot']['ucid'],
                    'ipAddress' => $array['airkill']['pilot']['ipAddress'],
                ];
        }
        return null;
    }

    public static function getVictim($event, $array) {
        switch ($event) {
            case Parameter::EVENT_SERVER_Online:
                break;
            case Parameter::EVENT_SERVER_Offline:
                break;
            case Parameter::EVENT_ADD_DcsEvent:
                break;
            case Parameter::EVENT_PILOT_Enter:
                break;
            case Parameter::EVENT_PILOT_Left:
                break;
            case Parameter::EVENT_PILOT_JoinBlue:
                break;
            case Parameter::EVENT_PILOT_JoinRed:
                break;
            case Parameter::EVENT_PILOT_JoinSpectators:
                break;
            case Parameter::EVENT_PILOT_Takeoff:
                break;
            case Parameter::EVENT_PILOT_Landed:
                break;
            case Parameter::EVENT_PILOT_KilledAir:
                return [
                    'name' => $array['airkill']['victim']['callsign'],
                    'ucid' => $array['airkill']['victim']['ucid'],
                    'ipAddress' => $array['airkill']['victim']['ipAddress'],
                ];
            case Parameter::EVENT_PILOT_KilledGround:
                return [
                    'name' => $array['groundkill']['unit']['title'],
                    'ucid' => null,
                    'ipAddress' => null,
                ];
        }
        return null;
    }

    public static function getSide($event, $array) {
        switch ($event) {
            case Parameter::EVENT_SERVER_Online:
                break;
            case Parameter::EVENT_SERVER_Offline:
                break;
            case Parameter::EVENT_ADD_DcsEvent:
                break;
            case Parameter::EVENT_PILOT_Enter:
                break;
            case Parameter::EVENT_PILOT_Left:
                break;
            case Parameter::EVENT_PILOT_JoinBlue:
                return 'BLUE';
            case Parameter::EVENT_PILOT_JoinRed:
                return 'RED';
            case Parameter::EVENT_PILOT_JoinSpectators:
                break;
            case Parameter::EVENT_PILOT_Takeoff:
                return $array['takeoff']['side'];
            case Parameter::EVENT_PILOT_Landed:
                return $array['landing']['side'];
            case Parameter::EVENT_PILOT_KilledAir:
                return $array['airkill']['side'];
            case Parameter::EVENT_PILOT_KilledGround:
                return $array['groundkill']['side'];
        }
        return null;
    }

    public static function getPlane($event, $array) {
        switch ($event) {
            case Parameter::EVENT_SERVER_Online:
                break;
            case Parameter::EVENT_SERVER_Offline:
                break;
            case Parameter::EVENT_ADD_DcsEvent:
                return $array['dcsEvent']['plane']['title'];
            case Parameter::EVENT_PILOT_Enter:
                break;
            case Parameter::EVENT_PILOT_Left:
                break;
            case Parameter::EVENT_PILOT_JoinBlue:
                return $array['blue']['plane']['title'];
            case Parameter::EVENT_PILOT_JoinRed:
                return $array['red']['plane']['title'];
            case Parameter::EVENT_PILOT_JoinSpectators:
                break;
            case Parameter::EVENT_PILOT_Takeoff:
                return $array['takeoff']['plane']['title'];
            case Parameter::EVENT_PILOT_Landed:
                return $array['landing']['plane']['title'];
            case Parameter::EVENT_PILOT_KilledAir:
                return $array['airkill']['plane']['title'];
            case Parameter::EVENT_PILOT_KilledGround:
                return $array['groundkill']['plane']['title'];
        }
        return null;
    }
}