<?php

namespace App\Command;

use App\Entity\Answer\Answer;
use App\Entity\TestCase\TestCase;
use App\Entity\TestSuite\TestSuite;
use App\Service\Answer\AnswerServiceInterface;
use App\Service\Result\ResultServiceInterface;
use App\Service\Session\SessionServiceInterface;
use App\Service\TestSuite\TestSuiteServiceInterface;
use App\Service\User\UserServiceInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

#[AsCommand(
    name: 'app:test',
    description: 'A little game with one testSuite',
)]
class TestCommand extends Command
{
    public function __construct(
        private readonly UserServiceInterface $userService,
        private readonly TestSuiteServiceInterface $testSuiteService,
        private readonly SessionServiceInterface $sessionService,
        private readonly ResultServiceInterface $resultService,
        private readonly AnswerServiceInterface $answerService,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /**
         * @var \Symfony\Component\Console\Helper\QuestionHelper $helper
         */
        $helper = $this->getHelper('question');

        $question = new ConfirmationQuestion(
            "Hello! Do you wanna play a game? [y]\n",
            true,
            '/y/'
        );

        if (!$helper->ask($input, $output, $question)) {
            return Command::SUCCESS;
        }

        while (true) {
            $question = new Question("What is your name? Only latin letters, please [user]\n", 'user');

            $name = $helper->ask($input, $output, $question);

            if (!$this->isValidName($name)) {
                $output->writeln("Very bad, try more");
                continue;
            }

            $user = $this->userService->getOrCreateUser($name);

            break;
        }

        $output->writeln(sprintf('Well, %s, let\'s start the game', $user->getName()));

        $question = new ChoiceQuestion(
            'Choose your destiny [0]',
            $this->testSuiteService->getList()->toArray(),
            0
        );

        $testSuite = $helper->ask($input, $output, $question);

        if (!$testSuite instanceof TestSuite) {
            return Command::SUCCESS;
        }

        $output->writeln(
            sprintf(
                'let\'s start the %s test [You could select multiple options separated by comma]',
                $testSuite->getName()
            ),
        );

        $session = $this->sessionService->create($user, $testSuite);

        $successResults = [];
        $failedResults = [];

        foreach ($testSuite->getTestCases() as $key => $testCase) {
            $isSuccess = true;

            $answers = $testCase->getAnswers()->toArray();
            shuffle($answers);

            $question = new ChoiceQuestion(
                sprintf('#%d. %s?', $key + 1, $testCase->getQuestion()),
                $answers,
            );
            $question->setMultiselect(true);

            $selectedAnswers = $helper->ask($input, $output, $question);

            foreach ($selectedAnswers as $answer) {
                if ($answer instanceof Answer) {
                    $isSuccess = $isSuccess && $answer->isRight();
                    $this->resultService->create($session, $answer);
                }
            }

            if ($isSuccess) {
                $successResults[] = [$testCase, $selectedAnswers, $question->getChoices()];
            } else {
                $failedResults[] = [$testCase, $selectedAnswers, $question->getChoices()];
            }
        }

        $output->writeln(
            sprintf(
                'Congratulations, %s! You\'ve completed this game. Your Results:',
                $user->getName()
            ),
        );

        $output->writeln("<============ Right questions ==============>");
        foreach ($successResults as $successResult) {
            $testCase = $successResult[0];
            $selectedAnswers = $successResult[1];
            $allAnswers = $successResult[2];

            if ($testCase instanceof TestCase) {
                $this->printResultList($output, $testCase, $selectedAnswers, $allAnswers);
            }
        }

        $output->writeln("<============= Wrong questions =============>");
        foreach ($failedResults as $failedResult) {
            $testCase = $failedResult[0];
            $selectedAnswers = $failedResult[1];
            $allAnswers = $failedResult[2];

            if ($testCase instanceof TestCase) {
                $this->printResultList($output, $testCase, $selectedAnswers, $allAnswers);
            }
        }

        return Command::SUCCESS;
    }

    protected function isValidName(?string $name): bool
    {
        if ($name === null) {
            return false;
        }

        return (bool)preg_match('/^[\d\sA-Za-z]*$/', $name);
    }

    /**
     * @param array<array<int>> $answerCombinations
     * @return string
     */
    protected function getAnswersAsString(array $answerCombinations): string
    {
        $results = [];

        foreach ($answerCombinations as $combination) {
            $combinationStr = implode(' AND ', $combination);

            if (count($combination) > 1) {
                $combinationStr = '(' . $combinationStr . ')';
            }

            $results[] = $combinationStr;
        }

        return implode(' OR ', $results);
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param \App\Entity\TestCase\TestCase $testCase
     * @param array<Answer> $selectedAnswers
     * @param array<Answer> $allAnswers
     * @return void
     */
    protected function printResultList(
        OutputInterface $output,
        TestCase $testCase,
        array $selectedAnswers,
        array $allAnswers,
    ): void {
        $output->writeln(
            sprintf(
                "%s\nChosen answers: %s\nRightAnswers: %s",
                $testCase->getQuestion(),
                implode(
                    ' AND ',
                    $this->answerService->getSelectedAnswerKeys($selectedAnswers, $allAnswers),
                ),
                $this->getAnswersAsString($this->answerService->getRightAnswerKeys($allAnswers)),
            ),
        );
    }
}
