<?php

namespace Perspective\IpLocate\Console\Command;

use Perspective\IpLocate\Model\Import\CityImport;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommand extends Command
{

    /**
     * @var CityImport
     */
    private $cityImport;

    /**
     * @param CityImport $cityImport
     * @param string|null $name
     */
    public function __construct(
        CityImport $cityImport,
        string $name = null
    ) {
        parent::__construct($name);
        $this->cityImport = $cityImport;
    }

    /**
     * Initialization of the command
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('novaposhta:data:import');
        $this->setDescription('Import cities from Nova Poshta to database');
        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Import cities</info>');
        $this->cityImport->execute(function ($message) use ($output) {
            $output->writeln('<info>' . $message . '</info>');
        });
    }
}
