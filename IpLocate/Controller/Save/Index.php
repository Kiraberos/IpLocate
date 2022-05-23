<?php

namespace Perspective\IpLocate\Controller\Save;

use Exception;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Message\ManagerInterface;
use Perspective\IpLocate\Helper\Cookie;
use Perspective\IpLocate\Api\Data\CookieInterface;

class Index implements HttpPostActionInterface, HttpGetActionInterface
{

    /**
     * @var Cookie
     */
    private $cookie;
    /**
     * @var RequestInterface
     */
    private $request;
    /**
     * @var ManagerInterface
     */
    private $messageManager;
    /**
     * @var ResultFactory
     */
    private $resultFactory;

    /**
     * @param RequestInterface $request
     * @param ResultFactory $resultFactory
     * @param ManagerInterface $messageManager
     * @param Cookie $cookie
     */
    public function __construct(
        RequestInterface $request,
        ResultFactory    $resultFactory,
        ManagerInterface $messageManager,
        Cookie           $cookie
    ) {
        $this->cookie = $cookie;
        $this->request = $request;
        $this->messageManager = $messageManager;
        $this->resultFactory = $resultFactory;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\ResultInterface|ManagerInterface
     */
    public function execute()
    {
        $params = $this->request->getParams();
        try {
            $this->cookie->delete(CookieInterface::CITY_COOKIE_NAME);
            $this->cookie->setCookie(CookieInterface::CITY_COOKIE_NAME, $params['cityName']);
            $this->cookie->delete(CookieInterface::ZIP_COOKIE_NAME);
            $this->cookie->setCookie(CookieInterface::ZIP_COOKIE_NAME, $params['zipCode']);
        } catch (Exception $e) {
            return $this->messageManager->addExceptionMessage($e, __('Something went wrong.'));
        }
        $result= $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $result->setData($params);
        return $result;
    }
}
