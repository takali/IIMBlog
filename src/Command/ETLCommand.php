<?php
namespace App\Command;

use App\Entity\Article;
use App\ETL\Client;
use App\ETL\Transform;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

// stop to use ContainerAwareCommand
class ETLCommand extends Command
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var Transform
     */
    protected $transform;


    /**
     * ETLCommand constructor.
     * @param Client $client
     */
    public function __construct(Client $client, EntityManagerInterface $entityManager, Transform $transform)
    {
        parent::__construct();

        $this->client = $client;
        $this->entityManager = $entityManager;
        $this->transform = $transform;
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
        //that equivalent to the Load layer, make a Class if it become more complex
        $articlesORM = $this->entityManager->getRepository(Article::class)->findAll();

        $articlesTransformed = $this->transform->transformArticles($articlesORM);

        $info = $this->client->bulkIndex($articlesTransformed, $input->getOption('type'));

        //debug ES with $info

        $output->writeln('<info>end of the ETL</info>');
    }
}
