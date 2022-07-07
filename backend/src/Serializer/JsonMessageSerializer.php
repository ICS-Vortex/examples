<?php

namespace App\Serializer;

use App\Entity\JsonMessage;
use App\Message\DcsJsonMessage;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class JsonMessageSerializer implements SerializerInterface
{
    private ObjectNormalizer $normalizer;
    private Serializer $serializer;

    public function __construct()
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $this->normalizer = new ObjectNormalizer($classMetadataFactory);
        $this->serializer = new Serializer([$this->normalizer]);
    }

    public function decode(array $encodedEnvelope): Envelope
    {
        $json = $encodedEnvelope['body'];
        $message = new DcsJsonMessage($json);
        $stamps = [];
        if (isset($headers['stamps'])) {
            $stamps = unserialize($headers['stamps']);
        }
        return new Envelope($message, $stamps);
    }

    public function encode(Envelope $envelope): array
    {
        if (empty($envelope->getMessage())) {
            return [];
        }
        /** @var DcsJsonMessage $jsonMessage */
        $jsonMessage = $envelope->getMessage()->getJson();
        $allStamps = [];
        foreach ($envelope->all() as $stamps) {
            $allStamps = array_merge($allStamps, $stamps);
        }

        return [
            'body' => $jsonMessage,
            'headers' => [
                'stamps' => serialize($allStamps)
            ],
        ];
    }
}
