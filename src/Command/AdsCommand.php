<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Service\Command\CommandService;

class AdsCommand extends Command
{
    protected static $defaultName = 'ads-status';
    private $commandService;
    
    public function __construct(CommandService $commandService)
    {
        $this->commandService = $commandService;
        parent::__construct();
    }

    // Ads status command function
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $adsArray = ($this->commandService->topAds());

        
        $io->title('TOP adverts ');
        $io->listing($adsArray);
        return 0;
    }
}
