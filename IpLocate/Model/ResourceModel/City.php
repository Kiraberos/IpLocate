<?php

namespace Perspective\IpLocate\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class City extends AbstractDb
{
    /**
     * @inheritdoc
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _construct()
    {
        $this->_init('novaposhta_city', 'city_id');
    }

    /**
     *
     * @param string $name
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getRefByName($name)
    {
        $select = $this->getConnection()->select()
                ->from(['city' => $this->getMainTable()], 'ref')
                ->where('city.name_ru=? OR city.name_ua=?', $name)
                ->limit(1);
        $row = $this->getConnection()->fetchRow($select);
        if (empty($row) || empty($row['ref'])) {
            return '';
        }
        return $row['ref'];
    }
}
