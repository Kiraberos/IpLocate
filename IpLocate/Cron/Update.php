<?php

namespace Perspective\IpLocate\Cron;

use Perspective\IpLocate\Model\Import\CityImport;
use Perspective\IpLocate\Service\Curl;

/**
 * Синхронизация (обновление) городов и отделений сохраненных в базе с IpLocate.ua
 * Рекомендуется выполнять синхронизацию 1 раз в сутки
 */
class Update
{

    /**
     * @var CityImport
     */
    private $cityImport;
    /**
     * @var Curl
     */
    private $testCurl;

    /**
     * @param Curl $testCurl
     * @param CityImport $cityImport
     */
    public function __construct(
        Curl $testCurl,
        CityImport $cityImport
    ) {
        $this->cityImport = $cityImport;
        $this->testCurl = $testCurl;
    }

    public function execute()
    {
       // $this->testCurl->getDataImport($params);
        $this->cityImport->execute();
    }
}
