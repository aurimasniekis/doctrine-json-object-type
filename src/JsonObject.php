<?php

namespace AurimasNiekis\DoctrineJsonObjectType;

use JsonSerializable;

/**
 * Interface JsonObject
 *
 * @package AurimasNiekis\DoctrineJsonObjectType
 * @author  Aurimas Niekis <aurimas@niekis.lt>
 */
interface JsonObject extends JsonSerializable
{
    /**
     * @param array $data
     *
     * @return self
     */
    public static function fromJson(array $data);
}