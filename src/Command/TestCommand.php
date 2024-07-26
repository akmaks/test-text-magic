<?php

namespace App\Command;

use App\Entity\Answer;
use App\Entity\TestSuite\TestSuite;
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
            "Hello! Do you wanna play a game? [yes|no]\n",
            false,
            '/yes/'
        );

        if (!$helper->ask($input, $output, $question)) {
            return Command::SUCCESS;
        }

        while (true) {
            $question = new Question("What is your name? [only latin letters, please]\n");

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
            'Choose your destiny',
            $this->testSuiteService->getList()->toArray(),
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

        foreach ($testSuite->getTestCases() as $key => $testCase) {
            $question = new ChoiceQuestion(
                sprintf('#%d. %s?', $key + 1, $testCase->getQuestion()),
                $testCase->getAnswers()->toArray(),
            );
            $question->setMultiselect(true);

            $answers = $helper->ask($input, $output, $question);

            foreach ($answers as $answer) {
                if (!$answer instanceof Answer) {
                    return Command::FAILURE;
                }
                $this->resultService->create($session, $testCase, $answer);
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
}
