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

    protected function getMapping(string $index, string $type) :array
    {
        // if you are multi language use : https://www.elastic.co/guide/en/elasticsearch/guide/current/mixed-lang-fields.html

        return [
            'index' => $index,
            'type' => $type,
            'body' => [
                $type => [
                    '_source' => [
                        'enabled' => true
                    ],
                    'properties' => [
                        'location' => [
                            'type' => 'geo_point'
                        ],
                        'title' => [
                            'type' => 'text',
                            'analyzer' => 'french'
                        ],
                        'content' => [
                            'type' => 'text',
                            'analyzer' => 'french'
                        ],
                    ]
                ]
            ]
        ];
    }

    protected function invertAliase(string $index, string $aliase)
    {
        $this->client->indices()->updateAliases([
            'body'=> [
                'actions' => [
                    [
                        'remove' => [
                            'index' => '*',
                            'alias' => $aliase
                        ]
                    ],
                    [
                        'add' => [
                            'index' => $index,
                            'alias' => $aliase
                        ]
                    ]
                ]
            ]
        ]);
    }

    protected function deleteUnusedIndices(string $index, string $aliase)
    {
        $response = $this->client->indices()->getMapping();
        $indices = array_keys($response);

        foreach ($indices as $key => $existingIndex) {
            //only if it's not the current index and not a 3rd party index
            if ($existingIndex !== $index && 0 === strpos($existingIndex, $aliase)) {
                $this->client->indices()->delete([
                    'index' => $existingIndex
                ]);
            }
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $type = $input->getOption('type');
        $aliase = $this->client->getIndex();
        $index = $aliase.'_'.(new \DateTime())->format('U');

        //create Index
        $this->client->indices()->create([
            'index' => $index
        ]);

        //update mapping
        $this->client->indices()->putMapping($this->getMapping($index, $type));

        //Extract : make a Class Model if it become more complex
        $articlesORM = $this->entityManager->getRepository(Article::class)->findAll();

        //Transform
        $articlesTransformed = $this->transform->transformArticles($articlesORM);

        //Load
        $this->client->bulk($articlesTransformed, $index, $type);

        //invert aliase
        $this->invertAliase($index, $aliase);

        //delete unused indices
        $this->deleteUnusedIndices($index, $aliase);

        $output->writeln('<info>end of the ETL</info>');
    }
}
