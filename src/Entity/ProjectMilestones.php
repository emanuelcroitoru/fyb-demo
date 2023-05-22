<?php

namespace App\Entity;

use App\Repository\ProjectMilestonesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProjectMilestonesRepository::class)]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false, hardDelete: true)]
class ProjectMilestones
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['milestone'])]
    #[ORM\ManyToOne(inversedBy: 'projectMilestones')]
    private ?Project $project = null;

    #[ORM\Column(length: 255)]
    #[Groups(['milestone'])]
    #[Assert\NotBlank]
    private ?string $title = null;

    #[ORM\Column(length: 1000)]
    #[Groups(['milestone'])]
    #[Assert\NotBlank]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['milestone'])]
    #[Assert\DateTimeInterface]

    private ?\DateTimeInterface $milestoneDeadline = null;

    #[ORM\Column(name: 'deletedAt', type: Types::DATETIME_MUTABLE, nullable: true)]
    private $deletedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getMilestoneDeadline(): ?\DateTimeInterface
    {
        return $this->milestoneDeadline;
    }

    public function setMilestoneDeadline(\DateTimeInterface $milestoneDeadline): self
    {
        $this->milestoneDeadline = $milestoneDeadline;

        return $this;
    }

    public function getDeletedAt(): ?\DateTime
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTime $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }
}
