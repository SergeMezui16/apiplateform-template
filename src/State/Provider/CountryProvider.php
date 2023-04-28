<?php

namespace App\State\Provider;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\CountryRepository;

class CountryProvider implements ProviderInterface
{

    public function __construct(private CountryRepository $repository)
    {
    }
    /**
     * {@inheritDoc}
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {

        if ($operation instanceof GetCollection) {
            return $this->repository->findAll();
        }

        if ($operation instanceof Get) {
            return $this->repository->find($uriVariables['id']);
        }

        return null;
    }
}
