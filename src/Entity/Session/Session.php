<?php

namespace App\Entity\Session;

use App\Entity\Result\Result;
use App\Entity\TestSuite\TestSuite;
use App\Entity\User\User;
use App\Repository\SessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity(repositoryClass: SessionRepository::class)]
class Session
{
    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    private ?Ulid $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'sessions')]
    private User $user;

    #[ORM\ManyToOne(targetEntity: TestSuite::class, inversedBy: 'sessions')]
    private TestSuite $testSuite;

    /**
     * @var \Doctrine\Common\Collections\Collection<int,Result>
     */
    #[ORM\OneToMany(targetEntity: Result::class, mappedBy: 'session')]
    private Collection $results;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->results = new ArrayCollection();
    }

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function getTestSuite(): TestSuite
    {
        return $this->testSuite;
    }

    public function setTestSuite(TestSuite $testSuite): static
    {
        $this->testSuite = $testSuite;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<int,Result>
     */
    public function getResults(): Collection
    {
        return $this->results;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
