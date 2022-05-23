<?php

namespace Perspective\IpLocate\Model;

use Magento\Framework\Model\AbstractModel;
use Perspective\IpLocate\Api\Data\CityInterface;

class City extends AbstractModel implements CityInterface
{

    /**
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _construct()
    {
        $this->_init(\Perspective\IpLocate\Model\ResourceModel\City::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getArea()
    {
        return $this->getData('area');
    }

    /**
     * {@inheritdoc}
     */
    public function getCityId()
    {
        return $this->getData('city_id');
    }

    /**
     * {@inheritdoc}
     */
    public function getNameRu()
    {
        return $this->getData('name_ru');
    }

    /**
     * {@inheritdoc}
     */
    public function getNameUa()
    {
        return $this->getData('name_ua');
    }

    /**
     * {@inheritdoc}
     */
    public function getRef()
    {
        return $this->getData('ref');
    }

    /**
     * {@inheritdoc}
     */
    public function getTypeRu()
    {
        return $this->getData('type_ru');
    }

    /**
     * {@inheritdoc}
     */
    public function getTypeUa()
    {
        return $this->getData('type_ua');
    }

    /**
     * {@inheritdoc}
     */
    public function getZipCode()
    {
        return $this->getData('zip_code');
    }

    /**
     * {@inheritdoc}
     */
    public function setArea($area)
    {
        return $this->setData('area', $area);
    }

    /**
     * {@inheritdoc}
     */
    public function setCityId($cityId)
    {
        return $this->setData('city_id', $cityId);
    }

    /**
     * {@inheritdoc}
     */
    public function setNameRu($nameRu)
    {
        return $this->setData('name_ru', $nameRu);
    }

    /**
     * {@inheritdoc}
     */
    public function setNameUa($nameUa)
    {
        return $this->setData('name_ua', $nameUa);
    }

    /**
     * {@inheritdoc}
     */
    public function setRef($ref)
    {
        return $this->setData('ref', $ref);
    }

    /**
     * {@inheritdoc}
     */
    public function setTypeRu($typeRu)
    {
        return $this->setData('type_ru', $typeRu);
    }

    /**
     * {@inheritdoc}
     */
    public function setTypeUa($typeUa)
    {
        return $this->setData('type_ua', $typeUa);
    }

    /**
     * {@inheritdoc}
     */
    public function setAreaDescriptionUa($areaDescriptionUa)
    {
        return $this->setData('area_description_ua', $areaDescriptionUa);
    }

    /**
     * {@inheritdoc}
     */
    public function setAreaDescriptionRu($areaDescriptionRu)
    {
        return $this->setData('area_description_ru', $areaDescriptionRu);
    }

    /**
     * {@inheritdoc}
     */
    public function setZipCode($zipCode)
    {
        return $this->setData('zip_code', $zipCode);
    }
}
