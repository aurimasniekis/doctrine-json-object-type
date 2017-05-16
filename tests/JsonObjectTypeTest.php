<?php

namespace AurimasNiekis\DoctrineJsonObjectType\Test;

use AurimasNiekis\DoctrineJsonObjectType\Exception\MissingClassParameterException;
use AurimasNiekis\DoctrineJsonObjectType\JsonObject;
use AurimasNiekis\DoctrineJsonObjectType\JsonObjectType;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use PHPUnit\Framework\TestCase;

/**
 * Class JsonObjectTypeTest
 *
 * @package AurimasNiekis\DoctrineJsonObjectType\Test
 * @author  Aurimas Niekis <aurimas@niekis.lt>
 */
class JsonObjectTypeTest extends TestCase
{
    /**
     * @var AbstractPlatform
     */
    protected $platform;

    /**
     * @var JsonObjectType
     */
    protected $type;

    /**
     * @inheritDoc
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        if (false === Type::hasType('json_object')) {
            Type::addType('json_object', JsonObjectType::class);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->platform = $this->prophesize(AbstractPlatform::class);
        $this->type     = Type::getType('json_object');
    }

    /**
     * @expectedException AurimasNiekis\DoctrineJsonObjectType\Exception\InvalidValueTypeException
     * @expectedExceptionMessage Value object should implement "AurimasNiekis\DoctrineJsonObjectType\JsonObject" interface
     */
    public function testInvalidValue()
    {
        $this->type->convertToDatabaseValue(new \stdClass(), $this->platform->reveal());
    }

    public function testCorrectValue()
    {
        $data = new class implements JsonObject {
            public static function fromJson(array $data)
            {
                return new self();
            }

            public function jsonSerialize()
            {
                return [];
            }
        };

        $output = $this->type->convertToDatabaseValue($data, $this->platform->reveal());

        $expected = [
            '__class' => get_class($data)
        ];

        $this->assertEquals($expected, json_decode($output, true));
    }

    public function testDeserialize()
    {
        $data = new class implements JsonObject {
            public static function fromJson(array $data)
            {
                return new self();
            }

            public function jsonSerialize()
            {
                return [];
            }
        };

        $output = $this->type->convertToDatabaseValue($data, $this->platform->reveal());
        $result = $this->type->convertToPHPValue($output, $this->platform->reveal());

        $this->assertEquals($data, $result);
    }

    /**
     * @expectedException AurimasNiekis\DoctrineJsonObjectType\Exception\MissingClassParameterException
     * @expectedExceptionMessage Missing "__class" key value for "json_object" type field
     */
    public function testInvalidDeserialize()
    {
        $this->type->convertToPHPValue('{}', $this->platform->reveal());
    }

    public function testDeserializeNUll()
    {
        $this->assertNull($this->type->convertToPHPValue('', $this->platform->reveal()));
    }

    public function testCommonMethods()
    {
        $this->assertEquals(JsonObjectType::JSON_OBJECT, $this->type->getName());
        $this->assertTrue($this->type->requiresSQLCommentHint($this->platform->reveal()));
        $this->assertNull($this->type->getSQLDeclaration([], $this->platform->reveal()));
    }
}
