<?php

declare(strict_types=1);

namespace App\Service\Answer;

use App\Entity\Answer\Answer;

interface AnswerServiceInterface
{
    /**
     * @param array<Answer> $selectedAnswers
     * @param array<Answer> $allAnswers
     *
     * @return array<int>
     */
    public function getSelectedAnswerKeys(array $selectedAnswers, array $allAnswers): array;

    /**
     * @param array<Answer> $answers
     *
     * @return array<array<int>>
     */
    public function getRightAnswerKeys(array $answers): array;
}
