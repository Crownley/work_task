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
    private $advertRepository;
    
    public function __construct(CommandService $advertRepository)
    {
        $this->advertRepository = $advertRepository;
        parent::__construct();
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $adsArray = ($this->advertRepository->topAds());

        
        $io->title('TOP adverts ');
        $io->listing($adsArray);
        return 0;
    }
}
