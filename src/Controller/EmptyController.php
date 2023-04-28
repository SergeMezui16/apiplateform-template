<?php
namespace App\Controller;

use App\Entity\Image;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class EmptyController
{
    public function __invoke(Image $data)
    {
        return $data;
    }
}