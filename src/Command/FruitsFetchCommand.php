<?php

namespace App\Command;

use App\Message\EmailNotification;
use App\Service\FruityViceApiService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

class FruitsFetchCommand extends Command
{
    protected static $defaultName = 'fruits:fetch';

    /**
     * @var FruityViceApiService
     */
    private FruityViceApiService $fruityViceApiService;

    /**
     * @var MessageBusInterface
     */
    private MessageBusInterface $messageBus;

    /**
     * @param FruityViceApiService $fruityViceApiService;
     */
    public function __construct(
        FruityViceApiService $fruityViceApiService,
        MessageBusInterface  $messageBus
    ) {
        parent::__construct();
        $this->fruityViceApiService = $fruityViceApiService;
        $this->messageBus           = $messageBus;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Fill fruits table with data.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ioStream = new SymfonyStyle($input, $output);
        $ioStream->title("FILLING FRUITS TABLE");
        try {

            $this->fruityViceApiService->populateFruitsTable();

            $this->messageBus->dispatch(new EmailNotification(
                'fylyp458@gmail.com',
                'Table import done!',
                'Fruits table has been populated'
            ));

        } catch (\Throwable $e) {
            $ioStream->error('Error occured: ' . $e->getMessage());
            return 1;
        }
        $ioStream->success("Execution completed, email has been sent.");
        return 0;
    }
}