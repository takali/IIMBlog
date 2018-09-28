<?php
namespace App\Model\ETL;

use App\Entity\Article;
use App\ETL\Client;
use App\ETL\Transform;
use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractETL
{
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
}
