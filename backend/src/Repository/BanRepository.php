<?php

namespace App\Repository;

use App\Entity\Ban;
use App\Entity\Server;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\DBALException;
use Doctrine\Persistence\ManagerRegistry;
use Monolog\Logger;

/**
 * @method Ban|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ban|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ban[]    findAll()
 * @method Ban[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BanRepository extends BaseRepository
{
    public function clearBanList(Server $server = null)
    {
        if (empty($server)) {
            return false;
        }
        $query = "
            DELETE FROM `{$this->getClassMetadata()->getTableName()}`
            WHERE `server_id` = {$server->getId()}
        ";
        try {
            $this->getEntityManager()
                ->getConnection()
                ->query($query);
            return true;
        }catch (DBALException $e) {
            $this->log($e->getMessage(), Logger::ALERT, 'repository');
            return false;
        }
    }
}
