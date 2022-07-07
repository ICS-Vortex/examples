<?php

namespace App\Repository;

use App\Entity\Pilot;
use Doctrine\ORM\NonUniqueResultException;
use Monolog\Logger;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class BaseUserRepository extends BaseRepository implements UserLoaderInterface
{
    public const ROLE_PILOT = 'ROLE_PILOT';
    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLE_ROOT = 'ROLE_ROOT';

    public static array $roles = [
        self::ROLE_PILOT => self::ROLE_PILOT,
        self::ROLE_USER => self::ROLE_USER,
        self::ROLE_ADMIN => self::ROLE_ADMIN,
        self::ROLE_ROOT => self::ROLE_ROOT,
    ];


    /**
     * @param $email
     * @return bool
     */
    public function isValidEmail($email) : bool
    {
        $search = $this->findOneBy(['email' => $email]);
        if (!empty($search)) {
            return false;
        }

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }

    /**
     * @param string $username
     * @return ?Pilot
     */
    public function loadUserByUsername(string $username) : ?Pilot
    {
        try {
            return $this->createQueryBuilder('u')
                ->where('u.username = :username OR u.email = :username or u.ucid = :username')
                ->setParameter('username', $username)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            $this->log($e->getMessage() . " | " . $e->getTraceAsString(), Logger::CRITICAL);
            throw new CustomUserMessageAuthenticationException('User not found');
        }
    }

    public function loadUserByIdentifier(string $username): Pilot|UserInterface|null
    {
        return $this->loadUserByUsername($username);
    }

    /**
     * @param string $role
     * @return array
     */
    public function getUsersByRole($role = self::ROLE_USER): array
    {
        $em = $this->getEntityManager();

        return $em->getRepository(Pilot::class)->findBy(array(
            'roles' => $role,
            'deleted' => false,
            'checked' => true,
            'isActive' => true
        ));
    }
}
