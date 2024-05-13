<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\TranscriptionRepository;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: TranscriptionRepository::class)]
#[ApiResource(
      // operations: [
      //     new Get(normalizationContext: ['groups' => 'conference:item']),
      //     new GetCollection(normalizationContext: ['groups' => 'conference:list'])
      // ],
      order: ['updated_at' => 'DESC'],
      paginationEnabled: false,
  )]
class Transcription
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 80)]
    private ?string $name = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'transcriptions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the value of name.
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set the value of name.
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of description.
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Set the value of description.
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of user.
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Set the value of user.
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
