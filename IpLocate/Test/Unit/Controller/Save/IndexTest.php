<?php

namespace Perspective\IpLocate\Test\Unit\Controller\Save;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Perspective\IpLocate\Controller\Save\Index;
use Perspective\IpLocate\Helper\Cookie;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionException;

class IndexTest extends TestCase
{
    /**
     * @var Index
     */
    private $testRequest;
    /**
     * @var RequestInterface|MockObject
     */
    private $request;
    /**
     * @var Json|MockObject
     */
    private $getJson;
    /**
     * @var ManagerInterface|MockObject
     */
    private $messageManager;

    /**
     * @return void
     * @throws ReflectionException
     */
    public function setUp()
    {
        $params = [
            'zipCode' => '12345',
            'cityName' => 'Name'
        ];
        $cookiName = 'text1';
        $cookiValue = 'text2';
        $this->request = $this->createMock(RequestInterface::class);
        $this->request->method('getParams')->willReturn($params);
        $cookieData = $this->getMockBuilder(Cookie::class)
            ->disableOriginalConstructor()
            ->setMethods(['delete', 'setCookie'])
            ->getMock();
        $cookieData->method('delete')->willReturn($cookiName, $cookiValue);
        $cookieData->method('setCookie')->willReturn($cookiName, $cookiValue);
        $this->getJson = $this->getMockBuilder(Json::class)
            ->disableOriginalConstructor()
            ->setMethods(['setData'])
            ->getMock();
        $getResultFactory = $this->getMockBuilder(ResultFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $getResultFactory->method('create')->willReturn($this->getJson);
        $this->getJson->method('setData')->willReturn($params);
        $this->messageManager = $this->createMock(ManagerInterface::class);
        $objectManager = new ObjectManager($this);
        $this->testRequest = $objectManager->getObject(Index::class, [
            'request' =>  $this->request,
            'cookie' => $cookieData,
            'resultFactory' => $getResultFactory,
            'messageManager' => $this->messageManager
        ]);
    }

    /**
     * @return void
     */
    public function testExecute()
    {
        $this->testRequest->execute();
        $this->assertNotEmpty($this->request->getParams());
        $this->getJson->expects($this->any())
            ->method('setData')
            ->willReturn(Json::class);
    }
}
