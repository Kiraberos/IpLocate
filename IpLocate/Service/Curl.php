<?php

namespace Perspective\IpLocate\Service;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DataObject;
use Magento\Framework\DataObjectFactory;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Сервисный класс для синхронизации данных с novaposhta.ua
 */
class Curl
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var \Magento\Framework\HTTP\Client\Curl
     */
    private $curl;
    /**
     * @var SerializerInterface
     */
    private $serialize;
    /**
     * @var DataObjectFactory
     */
    private $objectFactory;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param SerializerInterface $serialize
     * @param \Magento\Framework\HTTP\Client\Curl $curl
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        SerializerInterface $serialize,
        DataObjectFactory $objectFactory,
        \Magento\Framework\HTTP\Client\Curl $curl
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->curl = $curl;
        $this->serialize = $serialize;
        $this->objectFactory = $objectFactory;
    }

    /**
     * @param array<mixed> $params
     * @return DataObject
     */
    public function getDataImport(array $params)
    {
        $apiKey = $this->scopeConfig->getValue(
            'detect_position/general/api_key',
            ScopeInterface::SCOPE_STORE
        );

        $params['apiKey'] = $apiKey;

        $citiesApi = $this->getRawData($params);
        $citiesApisArray = $this->serialize->unserialize($citiesApi);
        $citiesApisObject = $this->prepareDataFromApi($citiesApisArray);
        if ($citiesApisObject->hasData('success') && $citiesApisObject->getData('success') === true) {
            return $citiesApisObject;
        } else {
            return $this->getDefaultObject();
        }
    }

    /**
     * @param array<mixed> $params
     * @return string
     */
    public function getRawData(array $params): string
    {
        $this->curl->setHeaders(["content-type: application/json"]);
        $this->curl->post("https://api.novaposhta.ua/v2.0/json/", (string)$this->serialize->serialize($params));
        $json = $this->curl->getBody();
        return $json;
    }

    /**
     * @param array<mixed> $citiesApisArray
     * @return DataObject
     */
    public function prepareDataFromApi(array $citiesApisArray): DataObject
    {
        $citiesApisObject = $this->getDefaultObject();
        $citiesApisObject->addData($citiesApisArray);
        return $citiesApisObject;
    }

    /**
     * @return DataObject
     */
    public function getDefaultObject(): DataObject
    {
        return $this->objectFactory->create();
    }
}
