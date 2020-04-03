<?php
namespace Src\Address\Repository;


/**
 * all address classes must implement.
 */

interface UserInterface
{
    /**
     * @return mixed
     */
    public function create($user);

    /**
     * @return mixed
     */
    public function getAll();

}
