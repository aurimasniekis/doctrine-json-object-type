<?php

namespace AurimasNiekis\DoctrineJsonObjectType\Exception;

use InvalidArgumentException;

/**
 * Class InvalidValueTypeException
 *
 * @package AurimasNiekis\DoctrineJsonObjectType\Exception
 * @author  Aurimas Niekis <aurimas@niekis.lt>
 */
class InvalidValueTypeException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct(
            'Value object should implement "AurimasNiekis\DoctrineJsonObjectType\JsonObject" interface'
        );
    }
}