<?php

namespace App\Entity;

use App\Repository\VehiclesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VehiclesRepository::class)]
class Vehicles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $brand = null;

    #[ORM\Column(length: 255)]
    private ?string $model = null;

    /**
     * @var Collection<int, Disponibilities>
     */
    #[ORM\OneToMany(mappedBy: 'vehicle', targetEntity: Disponibilities::class, orphanRemoval: true)]
    private Collection $disponibilities;


    public function __construct()
    {
        $this->disponibilities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): static
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @return Collection<int, Disponibilities>
     */
    public function getDisponibilities(): Collection
    {
        return $this->disponibilities;
    }

    public function addDisponibility(Disponibilities $disponibility): static
    {
        if (!$this->disponibilities->contains($disponibility)) {
            $this->disponibilities->add($disponibility);
            $disponibility->setVehicle($this);
        }

        return $this;
    }

    public function removeDisponibility(Disponibilities $disponibility): static
    {
        if ($this->disponibilities->removeElement($disponibility)) {
            // set the owning side to null (unless already changed)
            if ($disponibility->getVehicle() === $this) {
                $disponibility->setVehicle(null);
            }
        }

        return $this;
    }
}
