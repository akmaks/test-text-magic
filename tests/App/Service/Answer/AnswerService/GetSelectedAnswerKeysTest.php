<?php

declare(strict_types=1);

namespace App\Tests\App\Service\Answer\AnswerService;

use App\Entity\Answer\Answer;
use App\Service\Answer\AnswerService;
use ReflectionClass;
use Symfony\Component\Uid\Ulid;

class GetSelectedAnswerKeysTest extends AbstractAnswerServiceTest
{
    /**
     * @param array<Answer> $selectedAnswers
     * @param array<Answer> $allAnswers
     * @param array<int> $expected
     * @dataProvider successProvider
     * @return void
     */
    public function testSuccess(array $selectedAnswers, array $allAnswers, array $expected): void
    {
        $service = new AnswerService();

        $this->assertEquals($expected, $service->getSelectedAnswerKeys($selectedAnswers, $allAnswers));
    }

    /**
     * @return array<array<string,array<int,Answer>>>
     */
    public function successProvider(): array
    {
        $answer1 = $this->getAnswer(Ulid::fromString('0190f557-4413-5b88-892b-f70f5b7604cb'));
        $answer2 = $this->getAnswer(Ulid::fromString('0190f557-4413-5b88-892b-f70f5b7604cc'));
        $answer3 = $this->getAnswer(Ulid::fromString('0190f557-4413-5b88-892b-f70f5b7604cd'));

        return [
            [
                'selectedAnswers' => [
                    $answer1,
                    $answer3,
                ],
                'allAnswers' => [
                    3 => $answer1,
                    5 => $answer2,
                    7 => $answer3,
                ],
                'expected' => [3, 7],
            ],
            [
                'selectedAnswers' => [],
                'allAnswers' => [],
                'expected' => [],
            ],
            [
                'selectedAnswers' => [],
                'allAnswers' => [
                    3 => $answer1,
                    5 => $answer2,
                    7 => $answer3,
                ],
                'expected' => [],
            ],
            [
                'selectedAnswers' => [
                    $answer1,
                    $answer3,
                ],
                'allAnswers' => [],
                'expected' => [],
            ],
            [
                'selectedAnswers' => [
                    $answer1,
                    $answer3,
                ],
                'allAnswers' => [
                    3 => $answer1,
                ],
                'expected' => [3],
            ],
        ];
    }

    /**
     * @param Ulid $id
     * @return Answer
     */
    protected function getAnswer(Ulid $id): Answer
    {
        $answer = new Answer();

        $class = new ReflectionClass($answer);
        $property = $class->getProperty('id');

        $property->setAccessible(true);
        $property->setValue($answer, $id);

        return $answer;
    }
}
