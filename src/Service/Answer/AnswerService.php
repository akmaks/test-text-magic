<?php

declare(strict_types=1);

namespace App\Service\Answer;

use App\Entity\Answer\Answer;

class AnswerService implements AnswerServiceInterface
{
    /**
     * @param array<Answer> $selectedAnswers
     * @param array<Answer> $allAnswers
     *
     * @return array<int>
     */
    public function getSelectedAnswerKeys(array $selectedAnswers, array $allAnswers): array
    {
        $result = [];

        foreach ($selectedAnswers as $selectedAnswer) {
            foreach ($allAnswers as $key => $answer) {
                if ($selectedAnswer->getId() === $answer->getId()) {
                    $result[] = $key;
                }
            }
        }

        return $result;
    }

    /**
     * @param array<Answer> $answers
     *
     * @return array<array<int>>
     */
    public function getRightAnswerKeys(array $answers): array
    {
        $rightAnswers = array_keys(
            array_filter(
                $answers,
                fn(Answer $answer) => $answer->isRight(),
            ),
        );

        $rightAnswersCount = count($rightAnswers);

        $results = [];

        $totalCombinations = pow(2, $rightAnswersCount);

        for ($i = 1; $i < $totalCombinations; $i++) {
            $combination = [];

            for ($j = 0; $j < $rightAnswersCount; $j++) {
                if (($i >> $j) & 1) {
                    $combination[] = $rightAnswers[$j];
                }
            }

            $results[] = $combination;
        }

        return $results;
    }
}
