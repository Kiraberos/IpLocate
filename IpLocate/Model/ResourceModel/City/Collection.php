<?php

namespace Perspective\IpLocate\Model\ResourceModel\City;

use Magento\Framework\Api\Search\AggregationInterface;
use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    /**
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _construct()
    {
        $this->_init(
            \Perspective\IpLocate\Model\City::class,
            \Perspective\IpLocate\Model\ResourceModel\City::class
        );
    }
}
