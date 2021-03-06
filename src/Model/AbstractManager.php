<?php
/**
 * Created by PhpStorm.
 * User: sylvain
 * Date: 07/03/18
 * Time: 20:52
 * PHP version 7
 */

namespace Model;

use App\Connection;

/**
 * Abstract class handling default manager.
 */
abstract class AbstractManager
{
    protected $pdoConnection; //variable de connexion

    protected $table;
    protected $className;

    /**
     *  Initializes Manager Abstract class.
     *
     * @param string $table Table name of current model
     */
    public function __construct(string $table)
    {
        $connexion = new Connection();
        $this->pdoConnection = $connexion->getPdoConnection();
        $this->table = $table;
        $this->className = __NAMESPACE__ . '\\' . ucfirst($table);
    }

    /**
     * Get all row from database.
     *
     * @return array
     */
    public function selectAll(): array
    {
        return $this->pdoConnection->query('SELECT * FROM ' . $this->table, \PDO::FETCH_CLASS,
            $this->className)->fetchAll();
    }

    /**
     * Get all row from database.
     *
     * @return array
     */
    public function selectAllDescOrderedBy($field, $limit) : array
    {
        return $this->pdoConnection->query('SELECT * FROM ' . $this->table . ' ORDER BY ' . $field . ' DESC 
        LIMIT ' . $limit, \PDO::FETCH_CLASS, $this->className)->fetchAll();
    }

    /**
     * Get one row from database by ID.
     *
     * @param  int $id
     *
     * @return array
     */
    public function selectOneById(int $id)
    {
        // prepared request
        $statement = $this->pdoConnection->prepare("SELECT * FROM $this->table WHERE id=:id");
        $statement->setFetchMode(\PDO::FETCH_CLASS, $this->className);
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }

    public function selectOneByFieldName(string $fieldName, $fieldValue)
    {
        $statement = $this->pdoConnection->prepare("SELECT * FROM $this->table WHERE $fieldName = :fieldValue");
        $statement->setFetchMode(\PDO::FETCH_CLASS, $this->className);
        $statement->bindValue('fieldValue', $fieldValue);
        $statement->execute();

        return $statement->fetch();
    }

    /**
     * DELETE on row in dataase by ID
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $reqDelete = $this->pdoConnection->query("DELETE FROM $this->table WHERE id = $id");
        return $reqDelete;
    }


    /**
     * INSERT one row in dataase
     *
     * @param Array $data
     */
    public function insert(array $datas)
    {
        $fields = array_keys($datas);
        $fieldsAsString = implode(',', $fields);

        foreach ($datas as $k => $data) {
            $datas[$k] = "'$data'";
        }

        $datasAsString = implode(',', $datas);

        $this->pdoConnection->query("INSERT INTO $this->table ($fieldsAsString) VALUES ($datasAsString)");
    }


    /**
     * @param int   $id   Id of the row to update
     * @param array $data $data to update
     */
    public function update(int $id, array $data)
    {
        //TODO : Implements SQL UPDATE request
    }
}
