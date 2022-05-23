<?php

namespace Perspective\IpLocate\Test\Unit\Service;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DataObject;
use Magento\Framework\DataObjectFactory;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Store\Model\ScopeInterface;
use Perspective\IpLocate\Service\Curl;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CurlTest extends TestCase
{
    /**
     * @var Curl
     */
    private $object;
    /**
     * @var ScopeConfigInterface|MockObject
     */
    private $scopeConfig;
    /**
     * @var SerializerInterface|MockObject
     */
    private $serialize;
    /**
     * @var DataObjectFactory|MockObject
     */
    private $dataObject;
    /**
     * @var \Magento\Framework\HTTP\Client\Curl|MockObject
     */
    private $curl;
    /**
     * @var object
     */
    private $serializer;
    /**
     * @var object
     */
    private $getDataObj;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->objMan = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->scopeConfig = $this->getMockForAbstractClass(ScopeConfigInterface::class);
        $this->serializer = $this->objMan->getObject(Json::class);
        $this->serialize = $this->getMockForAbstractClass(SerializerInterface::class);
        $this->getDataObj = $this->getMockForAbstractClass(DataObject::class);
        $this->dataObject = $this->getMockBuilder(DataObjectFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $this->dataObject->method('create')->willReturn($this->getDataObj);
        $this->curl = $this->getMockBuilder(\Magento\Framework\HTTP\Client\Curl::class)
            ->disableOriginalConstructor()
            ->setMethods(['setHeaders', 'post', 'getBody'])
            ->getMock();
        $this->object = new Curl($this->scopeConfig, $this->serialize, $this->dataObject, $this->curl);
    }

    public function testGetDataImport()
    {
        $data = '{
                "success": true,
                "data": [
                    {
                      "Ref": "e718a680-4b33-11e4-ab6d-005056801329",
                      "SettlementType": "563ced10-f210-11e3-8c4a-0050568002cf",
                      "Latitude": "50.450418000000000",
                      "Longitude": "30.523541000000000",
                      "Description": "Київ",
                      "DescriptionRu": "Киев",
                      "SettlementTypeDescription": "місто",
                      "SettlementTypeDescriptionRu": "город",
                      "Region": "",
                      "RegionsDescription": "",
                      "RegionsDescriptionRu": "",
                      "Area": "dcaadb64-4b33-11e4-ab6d-005056801329",
                      "AreaDescription": "Київська область",
                      "AreaDescriptionRu": "Киевская область",
                      "Index1": "01001",
                      "Index2": "04655",
                      "IndexCOATSU1": "3200000000",
                      "Delivery1": "1",
                      "Delivery2": "1",
                      "Delivery3": "1",
                      "Delivery4": "1",
                      "Delivery5": "1",
                      "Delivery6": "1",
                      "Delivery7": "0",
                      "Warehouse": "1",
                      "Conglomerates": [
                        "d4771ed0-4fb7-11e4-91b8-2f592fe1dcac",
                        "f86b75e9-42f4-11e4-91b8-2f592fe1dcac"
                      ]
                    }
                ],
                  "errors": [],
                  "warnings": [],
                  "info": {
                    "totalCount": 1
                  },
                  "messageCodes": [],
                  "errorCodes": [],
                  "warningCodes": [],
                  "infoCodes": []
                }';
        $getRawData = $this->testGetRawData($data);
        $this->serialize->expects($this->any())
            ->method('unserialize')
            ->with($getRawData)
            ->willReturn(json_decode($data, true));
        $this->scopeConfig->expects($this->any())
            ->method('getValue')
            ->willReturn('строка с админки');
        $prepareDataForApi = $this->testPrepareDataFromApi($data);
        $prepareDataForApi->hasData('success');
        $realData = $this->object->getDataImport([])->getData();
        $this->assertJsonStringEqualsJsonString($data, json_encode($realData));
    }

    /**
     * @param $params
     * @return string
     */
    public function testGetRawData($params)
    {
        $this->assertNotEmpty($params);
        $this->curl->method('getBody')->willReturn($params);
        return $this->object->getRawData([]);
    }

    /**
     * @param $dataForApi
     * @return object
     */
    public function testPrepareDataFromApi($dataForApi): object
    {
        $dataObject = $this->testGetDefaultObject();
        $dataObject->addData([]);
        return $this->object->prepareDataFromApi([]);
    }

    /**
     * @return DataObject
     */
    public function testGetDefaultObject(): DataObject
    {
         $this->dataObject->create();
         return $this->object->getDefaultObject();
    }
}
