<?php

namespace Perspective\IpLocate\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Perspective\IpLocate\Api\CityRepositoryInterface;
use Perspective\IpLocate\Api\Data\CityInterface;
use Perspective\IpLocate\Api\Data\CitySearchResultsInterfaceFactory;
use Perspective\IpLocate\Model\ResourceModel\City\CollectionFactory;
use Perspective\IpLocate\Model\ResourceModel\City as ResourceCity;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class CityRepository implements CityRepositoryInterface
{

    /**
     * @var CityFactory
     */
    private $cityFactory;

    /**
     * @var ResourceCity
     */
    private $cityResourceModel;

    /**
     * @var CollectionFactory
     */
    private $cityCollectionFactory;

    /**
     * @var CitySearchResultsInterfaceFactory
     */
    private $citySearchResultFactory;

    /**
     * @var SearchCriteriaBuilderFactory
     */
    private $searchCriteriaBuilder;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var sting
     */
    private $lang;
    /**
     * @var SerializerInterface
     */
    private $serialize;

    /**
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param SerializerInterface $serialize
     * @param CollectionProcessorInterface $collectionProcessor
     * @param ScopeConfigInterface $scopeConfig
     * @param CityFactory $cityFactory
     * @param ResourceCity $cityResourceModel
     * @param CollectionFactory $cityCollectionFactory
     * @param CitySearchResultsInterfaceFactory $citySearchResultFactory
     */
    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SerializerInterface $serialize,
        CollectionProcessorInterface $collectionProcessor,
        ScopeConfigInterface $scopeConfig,
        CityFactory $cityFactory,
        ResourceCity $cityResourceModel,
        CollectionFactory   $cityCollectionFactory,
        CitySearchResultsInterfaceFactory   $citySearchResultFactory
    ) {

        $this->cityFactory = $cityFactory;
        $this->cityResourceModel = $cityResourceModel;
        $this->cityCollectionFactory = $cityCollectionFactory;
        $this->citySearchResultFactory = $citySearchResultFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->serialize = $serialize;
        $this->collectionProcessor = $collectionProcessor;
        $this->scopeConfig = $scopeConfig;
        $this->lang = $scopeConfig->getValue(
            'detect_position/general/lang',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getById($cityId)
    {
        $city = $this->cityFactory->create();
        $this->cityResourceModel->load($city, $cityId);
        return $city;
    }

    /**
     * {@inheritdoc}
     */
    public function getByRef($ref)
    {
        $city = $this->cityFactory->create();
        $this->cityResourceModel->load($city, $ref, 'ref');
        return $city;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->cityCollectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResult = $this->citySearchResultFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);

        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());
        return $searchResult;
    }

    /**
     * {@inheritdoc}
     */
    public function getJsonByCityName(string $name = null)
    {
        $data = [];

        if (!empty($name) && mb_strlen($name) > 1) {
            $collection = $this->cityCollectionFactory->create();
            $collection->addFieldToFilter(
                ['name_ru', 'name_ua'],
                [
                        ['like' => $name . '%'],
                        ['like' => $name . '%']
                    ]
            );
            foreach ($collection->getItems() as $item) {
                $data[] = [
                    'id' => $item->getZipCode(),
                    'text' => $item->getData('name_' . $this->lang),
                ];
            }
        }

        return $this->serialize->serialize($data);
    }

    /**
     * {@inheritdoc}
     */
    public function save(CityInterface $city)
    {
        try {
            $this->cityResourceModel->save($city);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the city: %1',
                $exception->getMessage()
            ));
        }
        return $city;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(CityInterface $city)
    {
        try {
            $this->cityResourceModel->delete($city);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Street: %1',
                $exception->getMessage()
            ));
        }
        return $city;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($cityId)
    {
        $city = $this->getById($cityId);
        if (!empty($city->getId())) {
            return $this->delete($city);
        }
        return false;
    }
}
