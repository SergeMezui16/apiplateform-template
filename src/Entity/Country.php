<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\OpenApi\Model\Operation;
use App\State\Provider\CountryProvider;
use Symfony\Component\Intl\Countries;

#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
    ],
    paginationEnabled: false,
    provider: CountryProvider::class,
    // openapi: new Operation(tags: ['Country'])
)]
class Country
{
    #[ApiProperty(
        identifier: true,
    )]
    private ?string $id;

    #[ApiProperty(
        description: 'Code of country',
    )]
    private string $code;

    #[ApiProperty(
        description: 'Name of country',
    )]
    private string $name;

    #[ApiProperty(
        description: 'Flag of country',
    )]
    private ?string $flag;


    public function __construct(string $id)
    {
        $this->init(strtolower($id));
    }

    private function init(string $id)
    {
        $code = strtoupper($id);

        $this->id = $id;
        $this->code = $code;
        $this->name = Countries::getName($code, 'Fr-fr');
        $this->flag = "/flags/$code.svg";
    }


    public function getFlag()
    {
        return $this->flag;
    }


    public function getName()
    {
        return $this->name;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getId()
    {
        return $this->id;
    }
}
