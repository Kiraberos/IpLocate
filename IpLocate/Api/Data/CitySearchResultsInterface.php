<?php

namespace Perspective\IpLocate\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface CitySearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get cities list.
     *
     * @return \Perspective\IpLocate\Api\Data\CityInterface[]
     */
    public function getItems();

    /**
     * Set cities list.
     *
     * @param \Perspective\IpLocate\Api\Data\CityInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
