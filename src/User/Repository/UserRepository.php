<?php
namespace Src\Address\Repository;

use Src\PDO\Database;


class UserRepository implements UserInterface
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
    public function create($data)
    {
        return $this->database->insert(
            "INSERT INTO users (first_name, last_name, address_id) VALUES (?, ?, ?)",$data
        );
    }



    /**
     *
     */
    public function getAll()
    {
        return $this->database->get("select u.first_name, u.last_name, a.city, a.street, a.postal, c.name
                            from users as u
                            left join address as a on u.address_id = a.id
                            left join countries as c on a.country_id = c.id");
    }
}
