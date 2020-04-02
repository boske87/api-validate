<?php
namespace Src\Address\Repository;
use Src\Address\Address;

/**
 * all address classes must implement.
 */

interface AddressInterface
{
    /**
     * @return mixed
     */
    public function create($address);

    /**
     * @return mixed
     */
    public function getAll() : array;

}
