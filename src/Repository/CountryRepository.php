<?php

namespace App\Repository;

use App\Entity\Country;
use Symfony\Component\Intl\Countries;
use Symfony\Component\Intl\Exception\MissingResourceException;

class CountryRepository
{
    /**
     * Find all counties
     *
     * @return Country[]|null
     */
    public function findAll(): ?array
    {
        $countries = [];

        foreach (Countries::getCountryCodes() as $code) {
            $countries[] = new Country($code);
        }

        return $countries;
    }

    /**
     * Find one country by $code
     *
     * @param string $code
     * @return Country|null
     */
    public function find(string $code): ?Country
    {
        try {
            return new Country($code);
        } catch (MissingResourceException $e) {
            return null;
        }
    }
}
