<?php

namespace Perspective\IpLocate\Model;

use Perspective\IpLocate\Service\Curl;

class PageImporterManager
{
    const NUM_PER_BATCH = 150;
    /**
     * @var Curl
     */
    private $curl;

    /**
     * @param Curl $curl
     */
    public function __construct(
        Curl $curl
    ) {
        $this->curl = $curl;
    }

    /**
     * @param array<mixed> $params
     * @return array<mixed>
     */
    public function getDataImport(array $params): array
    {
        $pageSize = self::NUM_PER_BATCH;
        $dataChunks = [];
        $countOfQueries = 1;
        for ($i = 1; $i <= $countOfQueries; $i++) {
            $params['methodProperties']['Page'] = $i;
            $data = $this->curl->getDataImport($params);
            $dataChunks = array_merge($data->getData('data'), $dataChunks);
            $totalCount = $data->getData('info')['totalCount'];
            $countOfQueries = ceil($totalCount / $pageSize);
        }

        return $dataChunks;
    }
}
