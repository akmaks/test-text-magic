<?php

namespace App\Entity;

use App\Repository\TestCaseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity(repositoryClass: TestCaseRepository::class)]
class TestCase
{
    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    private ?Ulid $id = null;

    #[ORM\ManyToOne(targetEntity: TestSuite::class, inversedBy: 'testCases')]
    private TestSuite $testSuite;

    /**
     * @var \Doctrine\Common\Collections\Collection<int,Result>
     */
    #[ORM\OneToMany(targetEntity: Result::class, mappedBy: 'testCase')]
    private Collection $results;

    /**
     * @var \Doctrine\Common\Collections\Collection<int,Answer>
     */
    #[ORM\JoinTable(name: 'test_cases_answers')]
    #[ORM\JoinColumn(name: 'test_case_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'answer_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: Answer::class)]
    private Collection $answers;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $question = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->results = new ArrayCollection();
        $this->answers = new ArrayCollection();
    }

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<int,Answer>
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<int,Result>
     */
    public function getResults(): Collection
    {
        return $this->results;
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

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): static
    {
        $this->question = $question;

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
