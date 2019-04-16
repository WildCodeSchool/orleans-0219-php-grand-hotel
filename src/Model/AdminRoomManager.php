<?php


namespace App\Model;

class AdminRoomManager extends AbstractManager
{


    public $data = [];
    public $postData = [];
    public $errors = [];
    public $numberOfCaracteristics = 6;

    /**
     * Gives the table name
     */
    const TABLE = 'room';


    /**
     * @var string
     */
    protected $className;
    /**
     * @var string
     */
    protected $tableToJoin;
    /**
     * @var string
     */
    protected $secondTableToJoin;

    /**
     * RoomManager constructor
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
        $this->tableToJoin = 'room_photo';
        $this->secondTableToJoin = 'room_caracteristic';
    }

    /**
     * get the table rooms joined with the tables photos and caracteristic
     * @param string $foreignKey
     * @param string $primaryKey
     * @param string $secondForeignKey
     * @return array
     */
    public function selectAllDoubleJoin(string $foreignKey, string $primaryKey, string $secondForeignKey): array
    {
        return $this->pdo->query('SELECT * FROM ' . $this->table . ' JOIN ' . $this->tableToJoin . ' ON ' .
            $this->tableToJoin . '.' . $foreignKey . '=' . $this->table . '.' . $primaryKey . ' JOIN ' .
            $this->secondTableToJoin . ' ON ' .
            $this->secondTableToJoin . '.' . $secondForeignKey . '=' . $this->table . '.' . $primaryKey)->fetchAll();
    }

    /**
     * retourne table photos without ids
     * @return array
     */
    public function selectQuiteAllFromFirstJoined(): array
    {

        return $this->pdo->query('SELECT photo1, photo2, photo3, photo4 FROM ' . $this->tableToJoin)->fetchAll();
    }

    /**
     * get the table caracteristics
     * @return array
     */
    public function selectQuiteAllFromSecondJoined(): array
    {

        return $this->pdo->query('SELECT caracteristic1, caracteristic2, caracteristic3, caracteristic4,
caracteristic5, caracteristic6 FROM ' . $this->secondTableToJoin)->fetchAll();
    }


    /**Checks the name, trims, fills an array errors in case of problem, and an array data if everything is OK
     * @param array $postData
     * @param array $data
     * @param array $errors
     * @return array
     */
    public function checkName(array $postData, array $data, array $errors): array
    {
        $postData['name'] = trim($postData['name']);
        if ((strlen($postData['name']) < 1) || (strlen($postData['name']) > 50)) {
            $errors['name'] = 'Le nom de la chambre ne peut pas être vide ou dépasser 50 caractères';
            $this->errors['name'] = $errors['name'];
            return $this->errors;
        } else {
            $data['name'] = $postData['name'];
            $this->data['name'] = $data['name'];
            return $this->data;
        }
    }

    /**Checks the descriptions, trims, fills an array errors in case of problem, and an array data if everything is OK
     * @param array $postData
     * @param $data
     * @param array $errors
     * @param string $nameInArray
     * @return array
     *
     */
    public function checkDescription(array $postData, array $data, array $errors, string $nameInArray): array
    {
        $postData[$nameInArray] = trim($postData[$nameInArray]);
        if (strlen($postData[$nameInArray]) < 1) {
            $errors[$nameInArray] = 'La description ne peut pas être vide!';
            $this->errors[$nameInArray] = $errors[$nameInArray];
            return $this->errors;
        } else {
            $data[$nameInArray] = $postData[$nameInArray];
            $this->data[$nameInArray] = $data[$nameInArray];
            return $this->data;
        }
    }

    /** Checks the area and the price, fills an array errors in case of problem, and an array data if everything is OK
     * @param array $postData
     * @param array $data
     * @param array $errors
     * @param string $nameInArray
     * @return array
     */
    public function checkNumber(array $postData, array $data, array $errors, string $nameInArray): array
    {
        $postData[$nameInArray] = trim($postData[$nameInArray]);
        if (($postData[$nameInArray] < 0) || (!is_numeric($postData[$nameInArray]))) {
            $errors[$nameInArray] = 'Entrez un nombre supérieur à zéro.';
            $this->errors[$nameInArray] = $errors[$nameInArray];
            return $this->errors;
        } else {
            $data[$nameInArray] = $postData[$nameInArray];
            $this->data[$nameInArray] = $data[$nameInArray];
            return $this->data;
        }
    }

    /**Checks the caracteristics length, fills an array errors in case of problem, and an array data if everything is OK
     * @param array $postData
     * @param array $data
     * @param array $errors
     * @param string $nameInArray
     * @return array
     */
    public function checkCaracteristics(array $postData, array $data, array $errors, string $nameInArray)
    {
        if (isset($postData[$nameInArray])) {
            if (strlen($postData[$nameInArray]) > 50) {
                $errors[$nameInArray] = "50 caractères maximum";
                $this->errors[$nameInArray] = $errors[$nameInArray];
                return $this->errors;
            } else {
                $data[$nameInArray] = $postData[$nameInArray];
                $this->data[$nameInArray] = $data[$nameInArray];
                return $this->data;
            }
        }
    }
}
