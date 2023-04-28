<?php

namespace App\Serializer\Denormalizer;

use App\Entity\Image;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class ImageDenormalizer implements DenormalizerAwareInterface, DenormalizerInterface
{
    use DenormalizerAwareTrait;

    private const ALREADY_CALLED = 'ImageDenormalizerCalled';

    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): mixed
    {
        $context[self::ALREADY_CALLED] = true;
        return $this->denormalizer->denormalize($data, $type, 'jsonld', $context);
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = [])
    {
        return $type === Image::class && !isset($context[self::ALREADY_CALLED]);
    }
}
