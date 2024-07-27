<?php

namespace App\Entity\Answer;

use App\Entity\Result\Result;
use App\Entity\TestCase\TestCase;
use App\Repository\AnswerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity(repositoryClass: AnswerRepository::class)]
class Answer
{
    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    private ?Ulid $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private ?bool $isRight = null;

    #[ORM\ManyToOne(targetEntity: TestCase::class, inversedBy: 'answers')]
    private TestCase $testCase;

    /**
     * @var \Doctrine\Common\Collections\Collection<int,Result>
     */
    #[ORM\OneToMany(targetEntity: Result::class, mappedBy: 'answer')]
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

    public function getText(): ?string
    {
        return $this->text;
    }

    public function isRight(): ?bool
    {
        return $this->isRight;
    }

    public function setIsRight(bool $isRight): static
    {
        $this->isRight = $isRight;

        return $this;
    }

    public function getTestCase(): ?TestCase
    {
        return $this->testCase;
    }

    public function setTestCase(TestCase $testCase): static
    {
        $this->testCase = $testCase;

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<int,Result>
     */
    public function getResults(): Collection
    {
        return $this->results;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
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

    public function __toString(): string
    {
        return (string)$this->text;
    }
}
