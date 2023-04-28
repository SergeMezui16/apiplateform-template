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
use App\Entity\Role;
use App\Interface\EntityLifecycleInterface;
use App\Repository\UserRepository;
use App\State\Processor\UserPasswordHasherProcessor;
use App\Traits\LifecycleTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Post(
            processor: UserPasswordHasherProcessor::class,
            validationContext: ['groups' => ['Default', 'User:write']]
        ),
        new Get(normalizationContext: ['groups' => ['User:read', 'Lifecycle']]),
        new Put(processor: UserPasswordHasherProcessor::class),
        new Patch(processor: UserPasswordHasherProcessor::class),
        new Delete(),
    ],
    normalizationContext: ['groups' => ['User:read']],
    denormalizationContext: ['groups' => ['User:write']],
)]
#[ORM\HasLifecycleCallbacks]
#[
    ApiFilter(DateFilter::class, properties: ['createdAt' => DateFilter::INCLUDE_NULL_BEFORE_AND_AFTER, 'updatedAt' => DateFilter::INCLUDE_NULL_BEFORE_AND_AFTER]),
    ApiFilter(OrderFilter::class, properties: ['id', 'email']),
    ApiFilter(SearchFilter::class, properties: ['username' => 'partial', 'userRole.name' => 'partial'])
]
#[UniqueEntity('username')]
class User implements UserInterface, PasswordAuthenticatedUserInterface, EntityLifecycleInterface
{

    use LifecycleTrait;

    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    #[Groups(['User:read'])]    
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['User:write', 'User:read'])]
    #[Assert\NotBlank()]
    private ?string $username = null;

    #[ORM\Column]
    #[Groups(['User:read'])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Groups(['User:write'])]
    #[Assert\NotBlank]
    private ?string $password = null;

    #[ORM\ManyToMany(targetEntity: Role::class, inversedBy: 'users')]
    #[Groups(['User:write'])]
    #[Assert\Valid]
    private Collection $userRoles;

    public function __construct()
    {
        $this->userRoles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->userRoles->map(fn ($role) => $role->getCode())->toArray();
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }


    /**
     * @return Collection<int, Role>
     */
    public function getUserRoles(): Collection
    {
        return $this->userRoles;
    }

    public function addUserRole(Role $userRole): self
    {
        if (!$this->userRoles->contains($userRole)) {
            $this->userRoles->add($userRole);
        }

        return $this;
    }

    public function removeUserRole(Role $userRole): self
    {
        $this->userRoles->removeElement($userRole);

        return $this;
    }
}
