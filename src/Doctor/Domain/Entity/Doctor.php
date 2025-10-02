<?php

declare(strict_types=1);

namespace App\Doctor\Domain\Entity;

use App\Department\Domain\Entity\Department;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity]
#[ORM\Table(name: 'doctor')]
class Doctor
{
    #[ORM\Id]
    #[ORM\Column(type: 'ulid', unique: true)]
    private Ulid $id;

    #[ORM\Column(length: 255)]
    private string $name;

    /** @var Collection<int, Department> */
    #[ORM\ManyToMany(targetEntity: Department::class, inversedBy: 'doctors')]
    #[ORM\JoinTable(name: 'doctor_department')]
    private Collection $departments;

    public function __construct(string $name)
    {
        $this->id = new Ulid();
        $this->name = trim($name);
        $this->departments = new ArrayCollection();
    }

    public function id(): Ulid
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    /** @return Collection<int, Department> */
    public function departments(): Collection
    {
        return $this->departments;
    }

    public function rename(string $newName): void
    {
        $this->name = trim($newName);
    }

    public function assignTo(Department $department): void
    {
        if (!$this->departments->contains($department)) {
            $this->departments->add($department);
            $department->addDoctor($this); // sync inverse side
        }
    }

    public function removeFrom(Department $department): void
    {
        if ($this->departments->removeElement($department)) {
            $department->removeDoctor($this);
        }
    }

    public function worksAt(Department $department): bool
    {
        return $this->departments->contains($department);
    }
}
