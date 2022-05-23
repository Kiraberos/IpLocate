<?php

namespace Perspective\IpLocate\Model\Source;

use Magento\Shipping\Model\Carrier\Source\GenericInterface;

class City implements GenericInterface
{
    /**
     * @var \Perspective\IpLocate\Model\ResourceModel\City\CollectionFactory
     */
    private $cityCollectionFactory;

    /**
     * @param \Perspective\IpLocate\Model\ResourceModel\City\CollectionFactory $cityCollectionFactory
     */
    public function __construct(
        \Perspective\IpLocate\Model\ResourceModel\City\CollectionFactory $cityCollectionFactory
    ) {
        $this->cityCollectionFactory = $cityCollectionFactory;
    }

    /**
     * Returns array to be used in multiselect on back-end
     *
     * @return array<mixed>
     */
    public function toOptionArray(): array
    {
        $cityCollection = $this->cityCollectionFactory->create();

        $options = [];

        foreach ($cityCollection as $city) {
            $options[] = [
                'value' => $city->getData('ref'),
                'label' => $city->getData('name_ru') . ', ' . $city->getData('type_ru'),
            ];
        }

        return $options;
    }
}
