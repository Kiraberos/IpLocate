<?php

namespace Perspective\IpLocate\Model\Import;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Perspective\IpLocate\Api\Data\CityInterface;
use Perspective\IpLocate\Model\CityFactory;
use Perspective\IpLocate\Model\PageImporterManager;
use Perspective\IpLocate\Model\ResourceModel\City\CollectionFactory;
use Perspective\IpLocate\Api\Data\CityInterfaceFactory;
use Perspective\IpLocate\Model\ResourceModel\City;
use Perspective\IpLocate\Service\Curl;

/**
 * Import cities from novaposhta.ua
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 */
class CityImport
{

    /**
     * @var Curl
     */
    private $curl;
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var CityFactory
     */
    private $cityFactory;

    /**
     * @var City
     */
    private $cityResource;

    /**
     * @var CollectionFactory
     */
    private $cityCollectionFactory;

    /**
     * @var CityInterfaceFactory
     */
    private $cityInterfaceFactory;
    /**
     * @var PageImporterManager
     */
    private $pageImporterManager;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param Curl $curl
     * @param PageImporterManager $pageImporterManager
     * @param CityFactory $cityFactory
     * @param City $cityResource
     * @param CollectionFactory $cityCollectionFactory
     * @param CityInterfaceFactory $cityInterfaceFactory
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Curl $curl,
        PageImporterManager $pageImporterManager,
        CityFactory $cityFactory,
        City $cityResource,
        CollectionFactory $cityCollectionFactory,
        CityInterfaceFactory $cityInterfaceFactory
    ) {
        $this->curl = $curl;
        $this->scopeConfig = $scopeConfig;
        $this->cityFactory = $cityFactory;
        $this->cityResource = $cityResource;
        $this->cityCollectionFactory = $cityCollectionFactory;
        $this->cityInterfaceFactory = $cityInterfaceFactory;
        $this->pageImporterManager = $pageImporterManager;
    }

    /**
     * @SuppressWarnings(PHPMD.ShortVariable)
     * @param \Closure $cl
     * @return void
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function execute(\Closure $cl = null)
    {
        $citiesFromNovaPoshta = $this->importCities();
        if ($citiesFromNovaPoshta == null) {
            if (is_callable($cl)) {
                $cl('Ошибка импорта городов. Проверьте ключ API.');
                return ;
            }
        }

        $cities = $this->getCitiesFromDb();

        foreach ($citiesFromNovaPoshta as $cityFromNovaPoshta) {
            $key = array_search($cityFromNovaPoshta['ref'], array_column($cities, 'ref'), true);

            if ($key === false || empty($key)) {
                $this->saveCity($cityFromNovaPoshta);
            } elseif (isset($cities[$key]['city_id'])) {

                if (($cities[$key]['ref'] !== $cityFromNovaPoshta['ref']) ||
                        ($cities[$key]['name_ua'] !== $cityFromNovaPoshta['name_ua']) ||
                        ($cities[$key]['name_ru'] !== $cityFromNovaPoshta['name_ru']) ||
                        ($cities[$key]['area'] !== $cityFromNovaPoshta['area']) ||
                        ($cities[$key]['type_ua'] !== $cityFromNovaPoshta['type_ua']) ||
                        ($cities[$key]['type_ru'] !== $cityFromNovaPoshta['type_ru']) ||
                        ($cities[$key]['area_description_ua'] !== $cityFromNovaPoshta['area_description_ua']) ||
                        ($cities[$key]['area_description_ru'] !== $cityFromNovaPoshta['area_description_ru']) ||
                        ($cities[$key]['zip_code'] !== $cityFromNovaPoshta['zip_code'])
                ) {
                    $cityId = $cities[$key]['city_id'];
                    $this->saveCity($cityFromNovaPoshta, $cityId);
                }
            }

            if (is_callable($cl)) {
                $cl($cityFromNovaPoshta['ref'] . ' ' . $cityFromNovaPoshta['name_ru']);
            }
        }
    }

    /**
     * @param array<mixed> $data
     * @param int|null $cityId
     * @return void
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    private function saveCity(array $data, int $cityId = null)
    {
        $cityModel = $this->cityFactory->create();
        $cityModel->setCityId($cityId);
        $cityModel->setRef($data['ref']);
        $data['name_ua'] ? $cityModel->setNameUa($data['name_ua']) : $cityModel->setNameUa($data['name_ru']);
        $data['name_ru'] ? $cityModel->setNameRu($data['name_ru']) : $cityModel->setNameUa($data['name_ua']);
//        $cityModel->setNameUa(($data['name_ua'] ? $data['name_ru'] : $data['']));
//        $cityModel->setNameRu(($data['name_ru'] ? $data['name_ua'] : $data['']));
        $cityModel->setArea($data['area']);
        $cityModel->setTypeUa($data['type_ua']);
        $cityModel->setTypeRu($data['type_ru']);
        $cityModel->setAreaDescriptionUa($data['area_description_ua']);
        $cityModel->setAreaDescriptionRu($data['area_description_ru']);
        $cityModel->setZipCode($data['zip_code']);
        $this->cityResource->save($cityModel);
    }

    /**
     * Return cities array
     *
     * @return array<mixed>
     */
    private function getCitiesFromDb(): array
    {
        $cityCollection = $this->cityCollectionFactory->create();

        $data = $cityCollection->load()->toArray();
        return $data['items'];
    }

    /**
     * @return string[]
     */
    private function prepareRequestParams($page): array
    {
        return [
            'modelName' => 'AddressGeneral',
            'calledMethod' => 'getSettlements',
            "methodProperties" => [
                "Page" => $page
            ]
        ];
    }

    /**
     * @return array<mixed>
     */
    private function importCities(): array
    {
        $cityData = [];
        $page = 1;
        $params = $this->prepareRequestParams($page);
        //$data = $this->curl->getDataImport($params)->getData('data');
        $data = $this->pageImporterManager->getDataImport($params);


        if ($data) {
            foreach ($data as $datum) {
                $cityData[] = [
                    'ref'     => $datum['Ref'],
                    'name_ua' => $datum['Description'],
                    'name_ru' => $datum['DescriptionRu'],
                    'area'    => $datum['Area'] ?? '',
                    'type_ua' => $datum['SettlementTypeDescription'] ?? '',
                    'type_ru' => $datum['SettlementTypeDescriptionRu'] ?? '',
                    'area_description_ua' => $datum['AreaDescription'] ?? '',
                    'area_description_ru' => $datum['AreaDescriptionRu'] ?? '',
                    'zip_code' => $datum['Index1'] ?? ''
                ];
            }
            return $cityData;
        } else {
            return $cityData;
        }
    }
}
