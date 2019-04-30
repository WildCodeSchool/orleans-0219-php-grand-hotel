<?php
/**
 * Created by PhpStorm.
 * User: sylvain
 * Date: 07/03/18
 * Time: 18:20
 * PHP version 7
 */
namespace App\Model;

class ContactManager extends AbstractManager
{
    const TABLE = 'formulaire';
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }
    public function selectFormValues()
    {
        return $this->pdo->query('SELECT * FROM formulaire;')->fetchAll();
    }
    public function insert(array $data): void
    {
        $statement = $this->pdo->prepare("INSERT INTO `formulaire` (`firstname`, `mail`, `message`)
        VALUES (:firstname, :mail, :message);");
        $statement->bindValue('firstname', $data['firstname'], \PDO::PARAM_STR);
        $statement->bindValue('mail', $data['mail'], \PDO::PARAM_STR);
        $statement->bindValue('message', $data['message'], \PDO::PARAM_STR);
        $statement->execute();
    }
}
