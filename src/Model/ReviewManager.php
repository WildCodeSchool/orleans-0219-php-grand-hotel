<?php
/**
 * Created by PhpStorm.
 * User: sylvain
 * Date: 07/03/18
 * Time: 18:20
 * PHP version 7
 */

namespace App\Model;

/**
 *
 */
class ReviewManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'review';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    /**
     * Shows all the reviews accepted by the administrator.
     * @return array
     */
    public function selectAllOnLine()
    {
        return $this->pdo->query('SELECT name, comment, grade, date FROM review WHERE online =1;')->fetchAll();
    }

    /**
     * Insert all the data from post in the database; adds the date of the review, and an item online which is 0 ,
     * means that the review should not be published until seen by  the administrator
     * @param array $data
     */
    public function insert(array $data):void
    {
        $date = date("j" . "/" . "m" . "/" . "Y");
        // prepared request
        $statement = $this->pdo->prepare("INSERT INTO $this->table
    (`name`, `grade`, `comment`, `date`, `online`) VALUES (:name, :grade, :comment, :date, :online)");
        $statement->bindValue('name', $data['name'], \PDO::PARAM_STR);
        $statement->bindValue('grade', $data['grade'], \PDO::PARAM_STR);
        $statement->bindValue('comment', $data['review'], \PDO::PARAM_STR);
        $statement->bindValue('date', $date, \PDO::PARAM_STR);
        $statement->bindValue('online', 0, \PDO::PARAM_INT);
        $statement->execute();
    }
}
