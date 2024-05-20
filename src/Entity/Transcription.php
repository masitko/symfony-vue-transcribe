<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model;
use App\State\SaveTranscription;
use App\Repository\TranscriptionRepository;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: TranscriptionRepository::class)]
#[Vich\Uploadable]
#[ApiResource(
      normalizationContext: ['groups' => ['get']],
      operations: [
          new Get(),
          new GetCollection(
            outputFormats: ['json' => ['application/json']],
          ),
          new Post(
            inputFormats: ['multipart' => ['multipart/form-data']],
            outputFormats: ['json' => ['application/json']],
            processor: SaveTranscription::class,
            validationContext: ['groups' => ['Default', 'transcription_create']], 
            normalizationContext: ['groups' => ['post']],
          )          
      ],
      // order: ['updatedAt' => 'DESC'],
      paginationEnabled: false,
  )]
class Transcription
{
    // use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get'])]
    private ?int $id = null;

    #[Groups(['get', 'post'])]
    #[ORM\Column(type: 'string', length: 80)]
    private ?string $name = null;

    #[Groups(['get', 'post'])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $description = null;

    #[Groups(['get'])]
    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private ?string $status = null;

    #[Groups(['get'])]
    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private ?string $providerId = null;

    #[Vich\UploadableField(mapping: 'transcription', fileNameProperty: 'filePath', size: 'fileSize', mimeType: 'mimeType')]
    #[Assert\NotNull(groups: ['transcription_create'])]
    private ?File $file = null;

    #[ORM\Column(nullable: true)] 
    private ?string $filePath = null; 

    #[ORM\Column(nullable: true)]
    private ?int $fileSize = null;

    #[ORM\Column(nullable: true)] 
    private ?string $mimeType = null; 
    
    #[ApiProperty(types: ['https://schema.org/contentUrl'])]
    #[Groups(['transcription:read'])]
    private ?string $contentUrl = null;
    

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $body = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $storage_info = null;

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

    /**
     * Get the value of mimeType
     */
    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    /**
     * Set the value of mimeType
     */
    public function setMimeType(?string $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * Get the value of fileSize
     */
    public function getFileSize(): ?int
    {
        return $this->fileSize;
    }

    /**
     * Set the value of fileSize
     */
    public function setFileSize(?int $fileSize): self
    {
        $this->fileSize = $fileSize;

        return $this;
    }

    /**
     * Get the value of body
     */
    public function getBody(): ?string
    {
        return $this->body;
    }

    /**
     * Set the value of body
     */
    public function setBody(?string $body): self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get the value of storage_info
     */
    public function getStorageInfo(): ?array
    {
        return $this->storage_info;
    }

    /**
     * Set the value of storage_info
     */
    public function setStorageInfo(?array $storage_info): self
    {
        $this->storage_info = $storage_info;

        return $this;
    }

    /**
     * Get the value of file
     */
    public function getFile(): ?File
    {
        return $this->file;
    }

    /**
     * Set the value of file
     */
    public function setFile(?File $file): self
    {
        $this->file = $file;
        if (null !== $file) {
          // It is required that at least one field changes if you are using doctrine
          // otherwise the event listeners won't be called and the file is lost
          // $this->updatedAt = new \DateTimeImmutable();
      }        

        return $this;
    }

    /**
     * Get the value of filePath
     */
    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    /**
     * Set the value of filePath
     */
    public function setFilePath(?string $filePath): self
    {
        $this->filePath = $filePath;

        return $this;
    }

    /**
     * Get the value of status
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * Set the value of status
     */
    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the value of providerId
     */
    public function getProviderId(): ?string
    {
        return $this->providerId;
    }

    /**
     * Set the value of providerId
     */
    public function setProviderId(?string $providerId): self
    {
        $this->providerId = $providerId;

        return $this;
    }
}
