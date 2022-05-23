<?php

namespace Perspective\IpLocate\Service;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Store\Model\ScopeInterface;
use Perspective\IpLocate\Helper\Cookie;
use Perspective\IpLocate\Helper\Data;
use Perspective\IpLocate\Model\ResourceModel\City\CollectionFactory;
use Perspective\IpLocate\Api\Data\CookieInterface;

class City
{

    /**
     * @var SerializerInterface
     */
    private $serialize;
    /**
     * @var ArrayManager
     */
    private $arrayManager;
    /**
     * @var Curl
     */
    private $curl;
    /**
     * @var Data
     */
    private $storage;
    /**
     * @var RemoteAddress
     */
    private $remoteAddress;
    /**
     * @var Cookie
     */
    private $cookie;
    /**
     * @var CollectionFactory
     */
    private $cityCollection;
    /**
     * @var sting
     */
    private $lang;

    /**
     * @param CollectionFactory $cityCollection
     * @param SerializerInterface $serialize
     * @param ArrayManager $arrayManager
     * @param RemoteAddress $remoteAddress
     * @param ScopeConfigInterface $scopeConfig
     * @param Curl $curl
     * @param Data $storage
     * @param Cookie $cookie
     */
    public function __construct(
        CollectionFactory $cityCollection,
        SerializerInterface $serialize,
        ArrayManager $arrayManager,
        RemoteAddress $remoteAddress,
        ScopeConfigInterface $scopeConfig,
        Curl $curl,
        Data $storage,
        Cookie $cookie
    ) {
        $this->serialize = $serialize;
        $this->arrayManager = $arrayManager;
        $this->curl = $curl;
        $this->storage = $storage;
        $this->remoteAddress = $remoteAddress;
        $this->cookie = $cookie;
        $this->cityCollection = $cityCollection;
        $this->lang = $scopeConfig->getValue(
            'detect_position/general/lang',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return array<mixed>|string|void
     */
    public function getCity()
    {
        $getCity = $this->cookie->getCookie(CookieInterface::CITY_COOKIE_NAME);
        $getZipCode = $this->cookie->getCookie(CookieInterface::ZIP_COOKIE_NAME);

        if ($getCity) {
            return [
                "city" => $getCity,
                "zip" => $getZipCode
            ];
        }

        $response = $this->getResponse();
        if ($this->arrayManager->exists('city', (array)$response) && $response['city']) {
            $collectionData = $this->getCollectionData($response['city']);
            foreach ($collectionData as $collectionDatum) {
                if (!is_null($collectionDatum)) {
                    $zipCodeFromDatum = $collectionDatum->getZipCode();
                    if ($zipCodeFromDatum != $response['zip']) {
                        return[
                            "city" => $collectionDatum->getData('name_' . $this->lang),
                            "zip" => $zipCodeFromDatum
                        ];
                    }
                }
            }
            $getCity = $this->arrayManager->get('city', (array)$response);
            $getZipCode = $this->arrayManager->get('zip', (array)$response);
            return [
                "city" => $getCity,
                "zip" => $getZipCode
            ];
        }
    }

    /**
     * @return array<mixed>
     */
    private function getResponse(): array
    {
        //$visitorIp =  $this->remoteAddress->getRemoteAddress();
        $visitorIp = '176.108.236.254';
        $getAccessKey = $this->storage->getAccessKey();
        $url = "http://api.ipstack.com/" . $visitorIp . "?access_key=$getAccessKey" . "&language=$this->lang";
        $this->curl->get($url);
        $response = $this->serialize->unserialize($this->curl->getBody());
        return $response;
    }

    /**
     * @param string $city
     * @return \Magento\Framework\DataObject[]
     */
    private function getCollectionData(string $city): array
    {
        $collection = $this->cityCollection->create()
            ->addFieldToSelect(['name_' . $this->lang, 'zip_code'])
            ->addFieldToFilter('name_' . $this->lang, $city);
        $collectionData = $collection->getItems();
        return $collectionData;
    }
}
