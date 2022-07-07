<?php


namespace App\Transformer;


use App\Entity\Plane;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class DateTransformer implements DataTransformerInterface
{
    public function transform($date) : ?string
    {
        if ($date instanceof \DateTime) {
            return $date->format('d.m.Y');
        }
        return null;
    }

    public function reverseTransform($dateString)
    {
        try {
            return new \DateTime($dateString);
        }catch (\Exception $exception) {
            return null;
        }
    }
}