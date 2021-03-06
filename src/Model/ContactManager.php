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
class ContactManager extends AbstractManager
{
    const TABLE = 'contact';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function selectAll(): array
    {
        return  $this->pdoConnection->query('SELECT contact.id, contact.firstname, contact.lastname, civilty.name as civility FROM ' .
            $this->table .' JOIN civilty ON civilty.id = contact.civility_id', \PDO::FETCH_CLASS, $this->className)->fetchAll();
    }
}
