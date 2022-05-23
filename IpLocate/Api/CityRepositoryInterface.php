<?php

namespace Perspective\IpLocate\Api;

use Perspective\IpLocate\Api\Data\CityInterface;

interface CityRepositoryInterface
{
    /**
     * Save city.
     *
     * @param \Perspective\IpLocate\Api\Data\CityInterface $city
     * @return \Perspective\IpLocate\Api\Data\CityInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(CityInterface $city);

    /**
     * Retrieve city.
     *
     * @param int $cityId
     * @return \Perspective\IpLocate\Api\Data\CityInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($cityId);

    /**
     * Retrieve city.
     *
     * @param string $ref
     * @return \Perspective\IpLocate\Api\Data\CityInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getByRef($ref);

    /**
     * Retrieve cities matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Perspective\IpLocate\Api\Data\CitySearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Retrieve cities matching name.
     *
     * @param string|null $name | null
     * @return string | null
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getJsonByCityName(string $name = null);

    /**
     * Delete city.
     *
     * @param \Perspective\IpLocate\Api\Data\CityInterface $city
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(CityInterface $city);

    /**
     * Delete city by ID.
     *
     * @param int $cityId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($cityId);
}
