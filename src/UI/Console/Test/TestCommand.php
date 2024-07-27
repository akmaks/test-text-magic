<?php

namespace App\UI\Console\Test;

use App\Entity\Answer\Answer;
use App\Entity\TestCase\TestCase;
use App\Entity\TestSuite\TestSuite;
use App\Entity\User\User;
use App\Service\Answer\AnswerServiceInterface;
use App\Service\Result\ResultServiceInterface;
use App\Service\Session\SessionServiceInterface;
use App\Service\TestSuite\TestSuiteServiceInterface;
use App\Service\User\UserServiceInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\HelperInterface;
use Symfony\Component\Console\Helper\QuestionHelper;
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
        private readonly QuestionHelper $helper,
    ) {
        parent::__construct();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $question = new ConfirmationQuestion(
            "Hello! Do you wanna play a game? [y]\n",
            true,
            '/y/'
        );

        if (!$this->helper->ask($input, $output, $question)) {
            $output->writeln("Goodbye");

            return Command::SUCCESS;
        }

        $user = $this->registerName($input, $output);

        while (true) {
            $this->runTest($user, $input, $output);

            $question = new ConfirmationQuestion(
                "\nDo you wanna play again? [y]\n",
                true,
                '/y/'
            );

            if (!$this->helper->ask($input, $output, $question)) {
                $output->writeln(sprintf("Goodbye, %s", $user->getName()));

                return Command::SUCCESS;
            }
        }
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return User
     */
    protected function registerName(InputInterface $input, OutputInterface $output): User
    {
        while (true) {
            $question = new Question("What is your name? Only latin letters, please [user]\n", 'user');

            $name = $this->helper->ask($input, $output, $question);

            if (!$this->isValidName($name)) {
                $output->writeln("Very bad, try more");
                continue;
            }

            $user = $this->userService->getOrCreateUser($name);

            break;
        }

        return $user;
    }

    /**
     * @param User $user
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function runTest(User $user, InputInterface $input, OutputInterface $output): void
    {
        $output->writeln(sprintf('Well, %s, let\'s start the game', $user->getName()));

        $question = new ChoiceQuestion(
            'Choose your destiny [0]',
            $this->testSuiteService->getList()->toArray(),
            0
        );

        /**
         * @var TestSuite $testSuite
         */
        $testSuite = $this->helper->ask($input, $output, $question);

        $output->writeln(
            sprintf(
                'let\'s start the %s test [You could select multiple options separated by comma]',
                $testSuite->getName()
            ),
        );

        $session = $this->sessionService->create($user, $testSuite);

        $successResults = [];
        $failedResults = [];

        $testCases = $testSuite->getTestCases()->toArray();
        shuffle($testCases);

        foreach ($testCases as $key => $testCase) {
            $isSuccess = true;

            $answers = $testCase->getAnswers()->toArray();
            shuffle($answers);

            $question = new ChoiceQuestion(
                sprintf('#%d. %s?', $key + 1, $testCase->getQuestion()),
                $answers,
            );
            $question->setMultiselect(true);

            $selectedAnswers = $this->helper->ask($input, $output, $question);

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
            $this->printResultList($output, $successResult[0], $successResult[1], $successResult[2]);
        }

        $output->writeln("<============= Wrong questions =============>");
        foreach ($failedResults as $failedResult) {
            $this->printResultList($output, $failedResult[0], $failedResult[1], $failedResult[2]);
        }
    }

    /**
     * @param string|null $name
     *
     * @return bool
     */
    protected function isValidName(?string $name): bool
    {
        if ($name === null) {
            return false;
        }

        return (bool)preg_match('/^[\d\sA-Za-z]*$/', $name);
    }

    /**
     * @param array<array<int>> $answerCombinations
     *
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
     * @param OutputInterface $output
     * @param TestCase $testCase
     * @param array<Answer> $selectedAnswers
     * @param array<Answer> $allAnswers
     *
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
