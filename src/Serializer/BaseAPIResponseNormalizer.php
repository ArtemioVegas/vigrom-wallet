<?php

declare(strict_types=1);

namespace App\Serializer;

use App\API\BaseAPIResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class BaseAPIResponseNormalizer implements NormalizerInterface
{
    /**
     * @param BaseAPIResponse $object
     */
    public function normalize($object, $format = null, array $context = array()): array
    {
        $data = [
            'success' => $object->isSuccess(),
            'error' => $object->getError(),
            'data' => $object->getData(),
        ];
        return $data;
    }

    public function supportsNormalization(mixed $data, string $format = null)
    {
        return $data instanceof BaseAPIResponse;
    }
}
