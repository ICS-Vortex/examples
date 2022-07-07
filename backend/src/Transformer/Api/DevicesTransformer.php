<?php


namespace App\Transformer\Api;


use App\Entity\GameDevice;
use App\Entity\Plane;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class DevicesTransformer implements DataTransformerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function transform($value)
    {
        return null;
    }

    public function reverseTransform($ids)
    {
        if (!$ids) {
            return new ArrayCollection();
        }
        $idsArray = explode(',', $ids);
        $planes = new ArrayCollection();
        foreach ($idsArray as $id) {
            $plane = $this->manager->getRepository(GameDevice::class)->find(intval($id));
            if (!empty($plane)) {
                $planes->add($plane);
            }
        }
        return $planes;
    }
}