<?php

namespace App\Entity;

use App\Repository\MontageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MontageRepository::class)]
class Montage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomlMontage = null;

    #[ORM\Column(length: 255)]
    private ?string $client = null;
/*
    #[ORM\Column]
    private ?\DateTimeImmutable $created_At = null;
*/
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: '0')]
    private ?string $cout = null;

    #[ORM\OneToMany(mappedBy: 'Montage', targetEntity: Piece::class)]
    private Collection $pieces;

    public function __construct()
    {
        $this->pieces = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomlMontage(): ?string
    {
        return $this->nomlMontage;
    }

    public function setNomlMontage(string $nomlMontage): self
    {
        $this->nomlMontage = $nomlMontage;

        return $this;
    }

    public function getClient(): ?string
    {
        return $this->client;
    }

    public function setClient(string $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_At;
    }

    public function setCreatedAt(\DateTimeImmutable $created_At): self
    {
        $this->created_At = $created_At;

        return $this;
    }

    public function getCout(): ?string
    {
        return $this->cout;
    }

    public function setCout(string $cout): self
    {
        $this->cout = $cout;

        return $this;
    }

    /**
     * @return Collection<int, Piece>
     */
    public function getPieces(): Collection
    {
        return $this->pieces;
    }

    public function addPiece(Piece $piece): self
    {
        if (!$this->pieces->contains($piece)) {
            $this->pieces->add($piece);
            $piece->setMontage($this);
        }

        return $this;
    }

    public function removePiece(Piece $piece): self
    {
        if ($this->pieces->removeElement($piece)) {
            // set the owning side to null (unless already changed)
            if ($piece->getMontage() === $this) {
                $piece->setMontage(null);
            }
        }

        return $this;
    }
}
