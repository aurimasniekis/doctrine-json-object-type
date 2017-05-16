<?php

namespace AurimasNiekis\DoctrineJsonObjectType;

use Doctrine\DBAL\Types\JsonType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use AurimasNiekis\DoctrineJsonObjectType\Exception\{
    InvalidValueTypeException,
    MissingClassParameterException
};

/**
 * Class JsonObjectType
 *
 * @package AurimasNiekis\DoctrineJsonObjectType
 * @author  Aurimas Niekis <aurimas@niekis.lt>
 */
class JsonObjectType extends JsonType
{
    const JSON_OBJECT = 'json_object';

    /**
     * @inheritDoc
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return parent::getSQLDeclaration($fieldDeclaration, $platform);
    }

    /**
     * @inheritDoc
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (false === ($value instanceof JsonObject)) {
            throw new InvalidValueTypeException();
        }

        $data = $value->jsonSerialize();
        $data['__class'] = get_class($value);

        return parent::convertToDatabaseValue($data, $platform);
    }

    /**
     * @inheritDoc
     *
     * @throws MissingClassParameterException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $data = parent::convertToPHPValue($value, $platform);

        if (null === $data) {
            return null;
        }

        if (false === isset($data['__class'])) {
            throw new MissingClassParameterException();
        }

        return call_user_func($data['__class'] . '::fromJson', $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return self::JSON_OBJECT;
    }

    /**
     * @inheritDoc
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}