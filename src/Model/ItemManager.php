<?php
/**
 * Created by PhpStorm.
 * User: sylvain
 * Date: 07/03/18
 * Time: 18:20
 * PHP version 7
 */

namespace Model;

/**
 *
 */
class ItemManager extends AbstractManager
{
    const TABLE = 'item';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function searchItem($query) // fonction pour recherche live dans une liste d'item
    {
        $query = '%' . $query . '%' ;
        $query = $this->pdoConnection->quote($query);

        return $this->pdoConnection->query("SELECT * FROM  $this->table WHERE title LIKE $query", \PDO::FETCH_CLASS, $this->className)->fetchAll();
    }
}
