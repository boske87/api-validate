<?php
namespace Src\PDO;

use Exception;
use \PDO;
use \PDOException;

class Database extends PDO
{
    private $host, $port, $base, $user, $pass, $charset;

    /**
     * connect to database
     */
    protected function connect()
    {
        try {
            $options = $this->charset ? array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES '.$this->charset) : null;
            parent::__construct(
                'mysql:host='.$this->host.';'.
                'port='.$this->port.';'.
                'dbname='.$this->base,
                $this->user,
                $this->pass,
                $options
            );
        } catch(Exception $e) {
            echo 'Error : '.$e->getMessage().'<br />';
            echo 'N : '.$e->getCode();
        }
    }

    /**
     * Database constructor.
     * @param string $host
     * @param string $base
     * @param string $user
     * @param string $pass
     * @param int $port
     * @param string $charset
     */
    public function __construct(
        $host='localhost', $base='database', $user='user', $pass='', $port=3306, $charset='utf8'
    )
    {
        $this->host    = $host;
        $this->base    = $base;
        $this->user    = $user;
        $this->pass    = $pass;
        $this->port    = $port;
        $this->charset = $charset;
        $this->connect();
    }

    /**
     * @param $query
     * @param array $data
     * @return bool|\PDOStatement
     */
    public function executeQuery($query, $data = [])
    {
        $stmt = parent::prepare($query);
        try {
            $stmt->execute($data);
            return $stmt;
        }
        catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * @param $query
     * @param array $data
     * @return bool|string
     */
    public function insert($query, $data = [])
    {
        return $this->executeQuery($query, $data) ? ( parent::lastInsertId() ? parent::lastInsertId() : true ) : false;
    }

    /**
     * @param $query
     * @param array $data
     * @return bool|\PDOStatement
     */
    public function select($query, $data = [])
    {
        return $this->executeQuery($query, $data);
    }

    /**
     * @param $query
     * @param array $data
     * @return bool|\PDOStatement
     */
    public function update($query, $data = [])
    {
        return $this->executeQuery($query, $data);
    }

    /**
     * @param $query
     * @param array $data
     * @return bool|\PDOStatement
     */
    public function delete($query, $data = [])
    {
        return $this->executeQuery($query, $data);
    }

    /**
     * @param $query
     * @param array $data
     * @return mixed
     */
    public function first($query, $data = [])
    {
        $result = $this->select($query, $data);
        return $result->fetchObject();
    }

    /**
     * @param $query
     * @param array $data
     * @param int $type
     * @return array
     */
    public function get($query, $data = [], $type = PDO::FETCH_OBJ)
    {
        $results = $this->select($query, $data);
        return $results->fetchAll($type);
    }
}
