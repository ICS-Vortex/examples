<?php
namespace App\Doctrine;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshTokenRepository;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenInterface;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManager as BaseRefreshTokenManager;

class RefreshTokenManager extends BaseRefreshTokenManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $objectManager;

    /**
     * @var string
     */
    protected $class;

    /**
     * @var RefreshTokenRepository
     */
    protected $repository;

    /**
     * Constructor.
     *
     * @param ObjectManager $om
     * @param string $class
     */
    public function __construct(ObjectManager $om, $class)
    {
        $this->objectManager = $om;
        $this->repository = $om->getRepository($class);
        $metadata = $om->getClassMetadata($class);
        $this->class = $metadata->getName();
    }

    /**
     * @param string $refreshToken
     *
     * @return RefreshTokenInterface
     */
    public function get($refreshToken)
    {
        return $this->repository->findOneBy(array('refreshToken' => $refreshToken));
    }

    /**
     * @param string $username
     *
     * @return RefreshTokenInterface
     */
    public function getLastFromUsername($username): RefreshTokenInterface
    {
        return $this->repository->findOneBy(array('username' => $username), array('valid' => 'DESC'));
    }

    /**
     * @param RefreshTokenInterface $refreshToken
     * @param bool|true $andFlush
     */
    public function save(RefreshTokenInterface $refreshToken, bool $andFlush = true)
    {
        $this->objectManager->persist($refreshToken);

        if ($andFlush) {
            $this->objectManager->flush();
        }
    }

    /**
     * @param RefreshTokenInterface $refreshToken
     * @param bool $andFlush
     */
    public function delete(RefreshTokenInterface $refreshToken, bool $andFlush = true)
    {
        $this->objectManager->remove($refreshToken);

        if ($andFlush) {
            $this->objectManager->flush();
        }
    }

    /**
     * @param DateTime|null $datetime
     * @param bool $andFlush
     *
     * @return RefreshTokenInterface[]
     */
    public function revokeAllInvalid(DateTime $datetime = null, bool $andFlush = true): array
    {
        $invalidTokens = $this->repository->findInvalid($datetime);

        foreach ($invalidTokens as $invalidToken) {
            $this->objectManager->remove($invalidToken);
        }

        if ($andFlush) {
            $this->objectManager->flush();
        }

        return $invalidTokens;
    }

    /**
     * Returns the RefreshToken fully qualified class name.
     *
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }
}
