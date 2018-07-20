<?php
namespace App\ETL;

use App\Logger\ElasticsearchLogger;
use Elasticsearch\ClientBuilder;

class Client
{
    /**
     * @var \Elasticsearch\Client
     */
    private $client;
    /**
     * @var ElasticsearchLogger
     */
    private $logger;
    /**
     * @var string
     */
    private $index;


    /**
     * Client constructor.
     * @param array $elasticsearch_config
     * @param ElasticsearchLogger $logger
     */
    public function __construct(array $elasticsearch_config, ElasticsearchLogger $logger)
    {
        $this->index = $elasticsearch_config['index'];
        $this->logger = $logger;
        $this->client = ClientBuilder::create()
            ->setHosts($elasticsearch_config['hosts'])
            ->setLogger($logger)
            ->build();
    }

    /**
     * @return string
     */
    public function getIndex(): string
    {
        return $this->index;
    }

    /**
     * @param $params
     * @return array
     */
    public function index(array $params): array
    {
        $data = $this->client->index($params);
        $this->logRequestInfo();

        return $data;
    }

    /**
     * @param $params
     * @return array
     */
    public function delete(array $params): array
    {
        $data = $this->client->delete($params);
        $this->logRequestInfo();

        return $data;
    }

    /**
     * @param $params
     * @return array
     */
    public function bulk(array $params): array
    {
        $data = $this->client->bulk($params);
        $this->logRequestInfo();

        return $data;
    }

    /**
     * @param array $params
     * @param string $type
     * @return array
     */
    public function bulkIndex(array $params, string $type): array
    {
        $paramsIndex = [];

        foreach ($params as $param) {
            $paramsIndex['body'][] = [
                'index' => [
                    '_index' => $this->index,
                    '_type' => $type,
                    '_id' => $param['id'],
                ]
            ];

            unset($param['id']);
            $paramsIndex['body'][] = $param;
        }

        $data = $this->bulk($paramsIndex);

        return $data;
    }


    /**
     * @param $params
     * @return array
     */
    public function search(array $params): array
    {
        $data = $this->client->search($params);
        $this->logRequestInfo();

        return $data;
    }
        

    /**
     * @param $params
     * @return array
     */
    public function suggest(array $params): array
    {
        $data = $this->client->suggest($params);
        $this->logRequestInfo();

        return $data;
    }

    private function logRequestInfo()
    {
        $info = $this->client->transport->getConnection()->getLastRequestInfo();

        $this->logger->logQuery(
            $info['request']['uri'],
            $info['request']['http_method'],
            $info['request']['body'],
            $info['response']['transfer_stats']['total_time'],
            [
                'method' => $info['request']['scheme'],
                'transport' => $info['request']['scheme'],
                'host' => explode(':', $this->client->transport->getConnection()->getHost())[0],
                'port' => '',
            ]
        );
    }

    /**
     * @param $params
     * @return array|bool
     */
    public function exists($params)
    {
        return $this->client->exists($params);
    }

    /**
     * @return \Elasticsearch\Namespaces\IndicesNamespace
     */
    public function indices()
    {
        return $this->client->indices();
    }
}
