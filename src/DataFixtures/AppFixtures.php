<?php

namespace App\DataFixtures;

use App\Entity\Answer;
use App\Entity\TestCase;
use App\Entity\TestSuite\TestSuite;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    /**
     * @var array<array<string,mixed>>
     */
    private const TEST_SUITES = [
        [
            'name' => 'testSuite',
            'testCases' => [
                [
                    'question' => '1 + 1 = ',
                    'answers' => [
                        [
                            'text' => '3',
                            'right' => false,
                        ],
                        [
                            'text' => '2',
                            'right' => true,
                        ],
                        [
                            'text' => '0',
                            'right' => false,
                        ],
                    ]
                ],
                [
                    'question' => '2 + 2 = ',
                    'answers' => [
                        [
                            'text' => '4',
                            'right' => true,
                        ],
                        [
                            'text' => '3 + 1',
                            'right' => true,
                        ],
                        [
                            'text' => '10',
                            'right' => false,
                        ],
                    ]
                ],
                [
                    'question' => '3 + 3 = ',
                    'answers' => [
                        [
                            'text' => '1 + 5',
                            'right' => true,
                        ],
                        [
                            'text' => '1',
                            'right' => false,
                        ],
                        [
                            'text' => '6',
                            'right' => true,
                        ],
                        [
                            'text' => '2 + 4',
                            'right' => true,
                        ],
                    ]
                ],
                [
                    'question' => '4 + 4 = ',
                    'answers' => [
                        [
                            'text' => '8',
                            'right' => true,
                        ],
                        [
                            'text' => '4',
                            'right' => false,
                        ],
                        [
                            'text' => '0',
                            'right' => false,
                        ],
                        [
                            'text' => '0 + 8',
                            'right' => true,
                        ],
                    ]
                ],
                [
                    'question' => '5 + 5 = ',
                    'answers' => [
                        [
                            'text' => '6',
                            'right' => false,
                        ],
                        [
                            'text' => '18',
                            'right' => false,
                        ],
                        [
                            'text' => '10',
                            'right' => true,
                        ],
                        [
                            'text' => '9',
                            'right' => false,
                        ],
                        [
                            'text' => '0',
                            'right' => false,
                        ],
                    ]
                ],
                [
                    'question' => '6 + 6 = ',
                    'answers' => [
                        [
                            'text' => '3',
                            'right' => false,
                        ],
                        [
                            'text' => '9',
                            'right' => false,
                        ],
                        [
                            'text' => '0',
                            'right' => false,
                        ],
                        [
                            'text' => '12',
                            'right' => true,
                        ],
                        [
                            'text' => '5 + 7',
                            'right' => true,
                        ],
                    ]
                ],
            ]
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::TEST_SUITES as $ts) {
            $testSuite = new TestSuite();
            $testSuite->setName($ts['name']);
            $testSuite->setTestCases(new ArrayCollection(
                (function (array $testCases) use ($manager, $testSuite): array {
                    $collection = [];

                    foreach ($testCases as $testCase) {
                        $tc = new TestCase();
                        $tc->setTestSuite($testSuite);
                        $tc->setQuestion($testCase['question']);
                        $tc->setAnswers($this->getAnswers($manager, $tc, $testCase['answers']));
                        $tc->setCreatedAt(new DateTimeImmutable());

                        $manager->persist($tc);

                        $collection[] = $tc;
                    }
                    return $collection;
                })($ts['testCases'])
            ));
            $testSuite->setCreatedAt(new DateTimeImmutable());

            $manager->persist($testSuite);
        }

        $manager->flush();
    }

    /**
     * @param \Doctrine\Persistence\ObjectManager $manager
     * @param \App\Entity\TestCase $testCase
     * @param array<array<string,mixed>> $answers
     * @return \Doctrine\Common\Collections\ArrayCollection<int,Answer>
     */
    private function getAnswers(ObjectManager $manager, TestCase $testCase, array $answers): ArrayCollection
    {
        $collection = new ArrayCollection();

        foreach ($answers as $answer) {
            $a = new Answer();
            $a->setTestCase($testCase);
            $a->setText($answer['text']);
            $a->setIsRight($answer['right']);
            $a->setCreatedAt(new DateTimeImmutable());

            $manager->persist($a);
            $collection->add($a);
        }

        return $collection;
    }
}
