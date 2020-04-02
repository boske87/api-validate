<?php
namespace Src\Address\Repository;

use Src\PDO\Database;


class AddressRepository implements AddressInterface
{
    /**
     * @var Database
     */
    private $database;

    //allow to implement different type database

    /**
     * AddressRepository constructor.
     * @param Database $database
     */
    public function __construct(Database $database)
    {
        $this->database = $database;

    }

    /**
     * @param  $address
     * @return mixed|void
     */
    public function create($address)
    {
        $this->database->insert(
            "INSERT INTO address (city, street, postal) VALUES (?, ?, ?)",$address
        );
    }



    /**
     * @return array
     */
    public function getAll(): array
    {
        $this->database->get();
    }
}
