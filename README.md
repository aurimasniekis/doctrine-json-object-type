# Doctrine Json Object Type

[![Latest Version](https://img.shields.io/github/release/aurimasniekis/doctrine-json-object-type.svg?style=flat-square)](https://github.com/aurimasniekis/doctrine-json-object-type/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build Status](https://img.shields.io/travis/aurimasniekis/doctrine-json-object-type.svg?style=flat-square)](https://travis-ci.org/aurimasniekis/doctrine-json-object-type)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/aurimasniekis/doctrine-json-object-type.svg?style=flat-square)](https://scrutinizer-ci.com/g/aurimasniekis/doctrine-json-object-type)
[![Quality Score](https://img.shields.io/scrutinizer/g/aurimasniekis/doctrine-json-object-type.svg?style=flat-square)](https://scrutinizer-ci.com/g/aurimasniekis/doctrine-json-object-type)
[![Total Downloads](https://img.shields.io/packagist/dt/aurimasniekis/doctrine-json-object-type.svg?style=flat-square)](https://packagist.org/packages/aurimasniekis/doctrine-json-object-type)

[![Email](https://img.shields.io/badge/email-aurimas@niekis.lt-blue.svg?style=flat-square)](mailto:aurimas@niekis.lt)

Doctrine Json Object Type provides a ability to serialize/deserialize object which implements JsonObject interface to json and backwards.


## Install

Via Composer

```bash
$ composer require aurimasniekis/doctrine-json-object-type
```

## Configuration

Symfony:
```yaml
doctrine:
    dbal:
        url: '%env(DATABASE_URL)%'
        types:
          json_object: AurimasNiekis\DoctrineJsonObjectType\JsonObjectType
```

Plain Doctrine:
```php
<?php

use Doctrine\DBAL\Types\Type;

Type::addType('json_object', 'AurimasNiekis\DoctrineJsonObjectType\JsonObjectType');
```

## Usage

Value object should implement `JsonObject` interface.

```php
<?php

use AurimasNiekis\DoctrineJsonObjectType\JsonObject;

class ValueObject implements JsonObject
{
    private $name;
    
    public function setName($name)
    {
        $this->name = $name;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public static function fromJson(array $data)
    {
        $inst = new self();
        
        $inst->setName($data['name']);
        
        return $inst;
    }
    
    public function jsonSerialize()
    {
        return [
            'name' => $this->getName()
        ];
    }
}
```

Entity

```php
<?php

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="entity")
 */
class Entity
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * "json_object" is extended "json" type 
     * 
     * @var ValueObject
     *
     * @ORM\Column(type="json_object)
     */
    private $value;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return ValueObject
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param ValueObject $value
     */
    public function setValue(ValueObject $value)
    {
        $this->value = $value;
    }
}
```

Usage

```php
<?php

$value = new ValueObject();
$value->setName('foo_bar');

$entity = new Entity();
$entity->setValue($value);

$em->persist($entity);
$em->flush(); // INSERT INTO `entity` (`id`, `value`) VALUES (1, '{"name": "foo_bar", "__class": "ValueObject"}');


$findResult = $repo->find(1);

/// $findResult->getValue() === $value;
```


## Testing

```bash
$ composer test
```


## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.


## License

Please see [License File](LICENSE) for more information.
