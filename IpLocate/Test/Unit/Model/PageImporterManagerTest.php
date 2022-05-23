<?php

namespace Perspective\IpLocate\Test\Unit\Model;

use Magento\Framework\DataObject;
use Magento\Framework\DataObjectFactory;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Perspective\IpLocate\Model\PageImporterManager;
use Perspective\IpLocate\Service\Curl;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PageImporterManagerTest extends TestCase
{
    /**
     * @var PageImporterManager
     */
    private $object;
    /**
     * @var Curl|MockObject
     */
    private $curlMock;
    /**
     * @var object
     */
    private $serializer;
    /**
     * @var DataObject|MockObject
     */
    private $getDataObj;
    /**
     * @var DataObjectFactory|MockObject
     */
    private $dataObject;

    /**
     * @return void
     * @throws \ReflectionException
     */
    public function setUp()
    {
        $objMan = new ObjectManager($this);
        $this->serializer = $objMan->getObject(Json::class);
        $this->getDataObj = $this->getMockForAbstractClass(DataObject::class);
        $this->dataObject = $this->getMockBuilder(DataObjectFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $this->dataObject->method('create')->willReturn($this->getDataObj);
        $this->curlMock = $this->getMockBuilder(Curl::class)
            ->disableOriginalConstructor()
            ->setMethods(['getDataImport'])
            ->getMock();
        $this->object = new PageImporterManager($this->curlMock);
    }

    /**
     * @return void
     */
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
        $params = $this->serializer->unserialize($data);
        $dataObject = $this->getDefaultObject()->addData($params);
        $this->curlMock->expects($this->any())
            ->method('getDataImport')
            ->willReturn($dataObject);
        $realData = $this->object->getDataImport([]);
        $this->assertNotEmpty($realData);
        $this->assertJsonStringEqualsJsonString(json_encode($params['data']), json_encode($realData));
    }

    /**
     * @return DataObject
     */
    public function getDefaultObject(): DataObject
    {
        return $this->dataObject->create();
    }
}
