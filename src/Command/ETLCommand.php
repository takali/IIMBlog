<?php
namespace App\Command;

use App\ETL\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ETLCommand extends Command
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * ETLCommand constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        parent::__construct();

        $this->client = $client;
    }


    protected function configure()
    {
        $this
            ->setName('app:etl')
            ->setDescription('ETL for populate Elasticsearch from SQL')

        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        // write the ETL here

        //$output->writeln();
    }
}