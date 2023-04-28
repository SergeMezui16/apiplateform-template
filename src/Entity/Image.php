<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model\Operation;
use App\Controller\EmptyController;
use App\Interface\EntityLifecycleInterface;
use App\Repository\ImageRepository;
use App\Traits\LifecycleTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
#[ApiResource(
    operations: [
        new Post(formats: ['multipart']),
        new Post(
            name: 'update',
            formats: ['multipart'],
            uriTemplate: '/images/{id}',
            controller: EmptyController::class,
            openapi: new Operation(
                description: 'Updates the Image resource.',
                summary: 'Updates the Image resource.'
            )
        ),
        new Get(normalizationContext: ['groups' => ['Image:read', 'Lifecycle']]),
        new GetCollection(normalizationContext: ['groups' => ['Images:read']]),
        new Delete(deserialize: false),
    ],
    denormalizationContext: ['groups' => ['Image:write']]
)]
#[ORM\HasLifecycleCallbacks]
#[Vich\Uploadable]
#[ApiFilter(DateFilter::class, properties: ['createdAt' => DateFilter::INCLUDE_NULL_BEFORE_AND_AFTER, 'updatedAt' => DateFilter::INCLUDE_NULL_BEFORE_AND_AFTER]),]
class Image implements EntityLifecycleInterface
{
    use LifecycleTrait;

    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    #[Groups(['Image:read', 'Images:read'])]
    private ?int $id = null;

    #[Groups(['Image:write'])]
    #[Vich\UploadableField(mapping: 'images', fileNameProperty: 'name', size: 'size', mimeType: 'type')]
    #[Assert\Image()]
    private ?File $file = null;

    #[Groups(['Image:read', 'Images:read'])]
    #[ORM\Column(length: 255)]
    private ?string $path = null;

    #[ORM\Column(length: 255)]
    #[Groups(['Image:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['Image:read'])]
    private ?string $size = null;

    #[ORM\Column(length: 255)]
    #[Groups(['Image:read'])]
    private ?string $type = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file): self
    {
        $this->file = $file;

        if ($file) {
            $this->updatedAt = new \DateTimeImmutable();
        }

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(?string $size): self
    {
        $this->size = $size;

        return $this;
    }
}
