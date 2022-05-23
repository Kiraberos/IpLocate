<?php

namespace Perspective\IpLocate\Controller\Adminhtml\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;

class Index extends Action
{
    /**
     * @var RemoteAddress
     */
    protected $remoteAddress;
    /**
     * @var Curl
     */
    private $_curl;

    public function __construct(
        Context $context,
        RemoteAddress $remoteAddress,
        Curl $curl
    ) {
        $this->remoteAddress = $remoteAddress;
        $this->_curl = $curl;
        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
    }
}
