<?php

namespace Perspective\IpLocate\Block\Checkout;

use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Perspective\IpLocate\Api\CityRepositoryInterface;
use Perspective\IpLocate\Helper\Cookie;
use Perspective\IpLocate\Api\Data\CookieInterface;

/**
 * Класс добавляет список городов в jsLayout
 */
class LayoutProcessor implements LayoutProcessorInterface
{

    /**
     * @var CityRepositoryInterface
     */
    private $cityRepository;

    /**
     * @var Session
     */
    private $checkoutSession;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var sting
     */
    private $lang;
    /**
     * @var Cookie
     */
    private $cookie;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param Session $checkoutSession
     * @param CityRepositoryInterface $cityRepository
     * @param Cookie $cookie
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Session $checkoutSession,
        CityRepositoryInterface $cityRepository,
        Cookie $cookie
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->cityRepository = $cityRepository;
        $this->checkoutSession = $checkoutSession;
        $this->lang = $this->scopeConfig->getValue(
            'carriers/novaposhta/lang',
            ScopeInterface::SCOPE_STORE
        );
        $this->cookie = $cookie;
    }

    /**
     * Process js Layout of block
     *
     * @param array<mixed> $jsLayout
     * @return array<mixed>
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function process($jsLayout)
    {
        $cities = [];

        $getCityFromCookie = $this->cookie->getCookie(CookieInterface::CITY_COOKIE_NAME);
        $getZipCodeFromCookie = $this->cookie->getCookie(CookieInterface::ZIP_COOKIE_NAME);
        if ($this->checkoutSession->getQuote()->getShippingAddress()->getCityNovaposhtaRef()) {
            $ref = $this->checkoutSession->getQuote()->getShippingAddress()->getCityNovaposhtaRef();
            $city = $this->cityRepository->getByRef($ref);

            if (!empty($city->getRef())) {
                $cities[] = [
                    'value' => $city->getRef(),
                    'label' => $city->getData('name_' . $this->lang),
                ];
            }
        } else {
            $cities[] = [
                'value' => $getZipCodeFromCookie,
                'label' => $getCityFromCookie
            ];
        }

        if (!isset($jsLayout['components']['checkoutProvider']['dictionaries']['city'])) {
            $jsLayout['components']['checkoutProvider']['dictionaries']['city'] = $cities;
        }
        return $jsLayout;
    }
}
