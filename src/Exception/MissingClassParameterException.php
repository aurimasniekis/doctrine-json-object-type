<?php

namespace AurimasNiekis\DoctrineJsonObjectType\Exception;

use InvalidArgumentException;

/**
 * Class MissingClassParameterException
 *
 * @package AurimasNiekis\DoctrineJsonObjectType\Exception
 * @author  Aurimas Niekis <aurimas@niekis.lt>
 */
class MissingClassParameterException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct(
            'Missing "__class" key value for "json_object" type field'
        );
    }
}