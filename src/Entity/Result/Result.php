<?php

namespace App\Entity\Result;

use App\Entity\Answer;
use App\Entity\Session\Session;
use App\Entity\TestCase;
use App\Repository\ResultRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity(repositoryClass: ResultRepository::class)]
class Result
{
    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    private ?Ulid $id = null;

    #[ORM\ManyToOne(targetEntity: Session::class, inversedBy: 'results')]
    private Session $session;

    #[ORM\ManyToOne(targetEntity: TestCase::class, inversedBy: 'results')]
    private TestCase $testCase;

    #[ORM\ManyToOne(targetEntity: TestCase::class, inversedBy: 'answer')]
    private Answer $answer;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function getSession(): Session
    {
        return $this->session;
    }

    public function setSession(Session $session): static
    {
        $this->session = $session;

        return $this;
    }

    public function getTestCase(): TestCase
    {
        return $this->testCase;
    }

    public function setTestCase(TestCase $testCase): static
    {
        $this->testCase = $testCase;

        return $this;
    }

    public function getAnswer(): Answer
    {
        return $this->answer;
    }

    public function setAnswer(Answer $answer): static
    {
        $this->answer = $answer;

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
}
