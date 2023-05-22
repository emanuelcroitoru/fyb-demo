<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ProjectRepository::class)]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false, hardDelete: true)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['milestone'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    #[Groups(['project'])]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    #[Groups(['project', 'milestone'])]
    #[Assert\NotBlank]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['project', 'milestone'])]
    #[Assert\NotBlank]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'project', targetEntity: ProjectMilestones::class)]
    private Collection $projectMilestones;

    #[ORM\Column(name: 'deletedAt', type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['project'])]
    private $deletedAt;


    public function __construct()
    {
        $this->projectMilestones = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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

    public function getDeletedAt(): ?\DateTime
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTime $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }

    /**
     * @return Collection<int, ProjectMilestones>
     */
    public function getProjectMilestones(): Collection
    {
        return $this->projectMilestones;
    }

    public function addProjectMilestone(ProjectMilestones $projectMilestone): self
    {
        if (!$this->projectMilestones->contains($projectMilestone)) {
            $this->projectMilestones->add($projectMilestone);
            $projectMilestone->setProject($this);
        }

        return $this;
    }

    public function removeProjectMilestone(ProjectMilestones $projectMilestone): self
    {
        if ($this->projectMilestones->removeElement($projectMilestone)) {
            // set the owning side to null (unless already changed)
            if ($projectMilestone->getProject() === $this) {
                $projectMilestone->setProject(null);
            }
        }

        return $this;
    }
}
