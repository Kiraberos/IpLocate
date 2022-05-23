<?php

namespace Perspective\IpLocate\Api\Data;

interface CityInterface
{

    /**
     * Getter for CityId.
     *
     * @return int
     */
    public function getCityId();

    /**
     * Getter for Ref - City Identifier.
     *
     * @return string|null
     */
    public function getRef();

    /**
     * Getter for City Name UA.
     *
     * @return string|null
     */
    public function getNameUa();

    /**
     * Getter for City Name RU.
     *
     * @return string|null
     */
    public function getNameRu();

    /**
     * Getter for Area.
     * @return string|null
     */
    public function getArea();

    /**
     * Getter for City Type UA
     *
     * @return string|null
     */
    public function getTypeUa();

    /**
     * Getter for City Type RU.
     *
     * @return string|null
     */
    public function getTypeRu();

    /**
     * Getter for City Zip Code.
     *
     * @return string|null
     */
    public function getZipCode();

    /**
     * Setter for CityId.
     *
     * @param int|null $cityId
     * @return CityInterface
     */
    public function setCityId($cityId);

    /**
     * Setter for Ref - City Identifier.
     *
     * @param string $ref
     * @return CityInterface
     */
    public function setRef($ref);

    /**
     * Setter for City Name UA.
     *
     * @param string $nameUa
     * @return CityInterface
     */
    public function setNameUa($nameUa);

    /**
     * Setter for City Name RU.
     *
     * @param string $nameRu
     * @return CityInterface
     */
    public function setNameRu($nameRu);

    /**
     * Setter for area.
     *
     * @param string $area
     * @return CityInterface
     */
    public function setArea($area);

    /**
     * Setter for City Type UA.
     *
     * @param string $typeUa
     * @return CityInterface
     */
    public function setTypeUa($typeUa);

    /**
     * Setter for City Type RU.
     *
     * @param string $typeRu
     * @return CityInterface
     */
    public function setTypeRu($typeRu);

    /**
     * @param string $areaDescriptionUa
     * @return CityInterface
     */
    public function setAreaDescriptionUa($areaDescriptionUa);

    /**
     * @param string $areaDescriptionRu
     * @return CityInterface
     */
    public function setAreaDescriptionRu($areaDescriptionRu);

    /**
     * @param string $setZipCode
     * @return CityInterface
     */
    public function setZipCode($setZipCode);
}
