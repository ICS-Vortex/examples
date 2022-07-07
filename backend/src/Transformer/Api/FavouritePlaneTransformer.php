<?php


namespace App\Transformer\Api;


use App\Entity\Plane;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class FavouritePlaneTransformer implements DataTransformerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function transform($favouritePlane) : ?int
    {
        if ($favouritePlane instanceof Plane) {
            return $favouritePlane->getId();
        }
        return null;
    }

    public function reverseTransform($id)
    {
        if (!$id) {
            return;
        }

        $plane = $this->manager
            ->getRepository(Plane::class)
            // query for the issue with this id
            ->find(intval($id))
        ;

        if (null === $plane) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'An issue with number "%s" does not exist!',
                $id
            ));
        }

        return $plane;
    }
}