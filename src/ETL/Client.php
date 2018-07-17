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
    public function index($params): array
    {
        $data = $this->client->index($params);
        $this->logRequestInfo();
        return $data;
    }

    /**
     * @param $params
     * @return array
     */
    public function delete($params): array
    {
        $data = $this->client->delete($params);
        $this->logRequestInfo();
        return $data;
    }

    /**
     * @param $params
     * @return array
     */
    public function bulk($params): array
    {
        $data = $this->client->bulk($params);
        $this->logRequestInfo();
        return $data;
    }

    /**
     * @param $params
     * @return array
     */
    public function search($params)
    {
        $data = $this->client->search($params);
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
}