<?php

declare(strict_types=1);

namespace App\Department\Domain\Entity;

use App\Doctor\Domain\Entity\Doctor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity]
#[ORM\Table(name: 'department')]
class Department
{
    #[ORM\Id]
    #[ORM\Column(type: 'ulid', unique: true)]
    private Ulid $id;

    #[ORM\Column(length: 100)]
    private string $name;

    /** @var Collection<int, Doctor> */
    #[ORM\ManyToMany(targetEntity: Doctor::class, mappedBy: 'departments')]
    private Collection $doctors;

    public function __construct(string $name)
    {
        $this->id = new Ulid();
        $this->name = trim($name);
        $this->doctors = new ArrayCollection();
    }

    public function id(): Ulid
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    /** @return Collection<int, Doctor> */
    public function doctors(): Collection
    {
        return $this->doctors;
    }

    public function rename(string $newName): void
    {
        $this->name = trim($newName);
    }

    public function addDoctor(Doctor $doctor): void
    {
        if (!$this->doctors->contains($doctor)) {
            $this->doctors->add($doctor);
        }
    }

    public function removeDoctor(Doctor $doctor): void
    {
        $this->doctors->removeElement($doctor);
    }
}
