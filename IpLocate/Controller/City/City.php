<?php

namespace Perspective\IpLocate\Controller\City;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Perspective\IpLocate\Service\City as CityService;

class City implements HttpPostActionInterface, HttpGetActionInterface
{
    /**
     * @var CityService
     */
    private $cityService;

    /**
     * @var ResultFactory
     */
    private $resultFactory;

    /**
     * @param CityService $cityService
     * @param ResultFactory $resultFactory
     */
    public function __construct(
        CityService $cityService,
        ResultFactory $resultFactory
    ) {
        $this->cityService = $cityService;
        $this->resultFactory = $resultFactory;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $result= $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $result->setData($this->cityService->getCity());
        return $result;
    }
}
