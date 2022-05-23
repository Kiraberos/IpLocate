<?php

namespace Perspective\IpLocate\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Locale\Resolver;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{

    /**
     * @var Resolver
     */
    private $store;

    /**
     * @param Context $context
     * @param Resolver $store
     */
    public function __construct(
        Context $context,
        Resolver $store
    ) {
        parent::__construct($context);
        $this->store = $store;
    }

    const XML_PATH_CUSTOM_KEY = 'detect_position/general/access_key';
    const XML_PATH_ENABLE_MODULE = 'detect_position/general/enable';

    /**
     * @param string $field
     * @param null|int|string $storeId
     * @return mixed
     */
    public function getConfigValue(string $field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            $field,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @return mixed
     */
    public function getEnableConfig()
    {
        return $this->getConfigValue(self::XML_PATH_ENABLE_MODULE);
    }

    /**
     * @return mixed
     */
    public function getAccessKey()
    {
        return $this->getConfigValue(self::XML_PATH_CUSTOM_KEY);
    }

    /**
     * @return false|string
     */
    public function getLanguageCode()
    {
        $currentLocaleCode = $this->store->getLocale();
        return strstr($currentLocaleCode, '_', true);
    }
}
