<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Entity\User;
use App\Interface\EntityLifecycleInterface;
use App\Repository\RoleRepository;
use App\Traits\LifecycleTrait;
use App\Validator\Attribute as AcmeAssert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RoleRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Patch(),
        new Post(),
        new Put(),
        new Get(normalizationContext: ['groups' => ['Role:read', 'Lifecycle']]),
        new Delete()
    ],
    normalizationContext: ['groups' => ['Role:read']],
    denormalizationContext: ['groups' => ['Role:write']]
)]
#[ORM\HasLifecycleCallbacks] 
#[
    ApiFilter(DateFilter::class, properties: ['createdAt' => DateFilter::INCLUDE_NULL_BEFORE_AND_AFTER, 'updatedAt' => DateFilter::INCLUDE_NULL_BEFORE_AND_AFTER]),
    ApiFilter(OrderFilter::class, properties: ['id', 'name']),
    ApiFilter(SearchFilter::class, properties: ['name' => 'partial'])
]
#[UniqueEntity('code')]
class Role implements EntityLifecycleInterface
{
    use LifecycleTrait;

    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    #[Groups(['Role:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[AcmeAssert\RoleCode(), Assert\NotBlank()]
    #[Groups(['Role:read', 'Role:write'])]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 3), Assert\NotBlank()]
    #[Groups(['Role:read', 'Role:write'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(min: 3)]
    #[Groups(['Role:read', 'Role:write'])]
    private ?string $description = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'userRoles')]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addUserRole($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeUserRole($this);
        }

        return $this;
    }
}
