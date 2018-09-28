<?php
namespace App\Command;

use App\Model\ETL\ETLArticle;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ETLCommand extends Command
{

    /**
     * @var ETLArticle
     */
    protected $etl_article;


    /**
     * ETLCommand constructor.
     * @param ETLArticle $etl_article
     */
    public function __construct(ETLArticle $etl_article)
    {
        parent::__construct();

        $this->etl_article = $etl_article;
    }


    protected function configure()
    {
        $this
            ->setName('app:etl')
            ->setDescription('ETL for populate Elasticsearch from SQL')
            ->addOption('type', null, InputOption::VALUE_REQUIRED, 'type to populate');
        ;
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->etl_article->indexAll($input->getOption('type'));

        $output->writeln('<info>end of ETL</info>');
    }
}
