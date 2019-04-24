<?php


namespace App\Model;

class AdminRoomManager extends AbstractManager
{

    /**An array with all the data allowed to go to the database
     * @var array
     */

    public $data = [];
    /**An array with all the $_POST data
     * @var array
     */
    public $postData = [];
    /**An array which displays the user's errors in the form
     * @var array
     */
    public $errors = [];
    /**Number of room caracteristics
     * @var int
     */
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
    public $secondTableToJoin;
    /**
     * @var array
     * Autorized pictures extensions
     */
    public $autorizedExtensions;
    /**
     * @var string
     * size max for pictures
     */
    public $autorizedSize;
    /**
     * @var array
     * the array which contains all the pictures data
     */
    public $file;
    /** An array with all the pictures names
     * @var array
     */
    public $photoNames;

    public $error;

    public $rooms;

    /**
     * RoomManager constructor
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
        $this->tableToJoin = 'room_photo';
        $this->secondTableToJoin = 'room_caracteristic';
        $this->autorizedExtensions = ['gif', 'jpg', 'png'];
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
     * returns table photos without ids
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
     * @return array
     */
    public function checkName(array $postData, array $data): array
    {
        $postData['name'] = trim($postData['name']);
        if ((strlen($postData['name']) < 1) || (strlen($postData['name']) > 50)) {
            $this->errors['name'] = 'Le nom de la chambre ne peut pas être vide ou dépasser 50 caractères';
            return $this->errors;
        } else {
            $data['name'] = $postData['name'];
            $this->data['name'] = $data['name'];
            return $this->data;
        }
    }


    /**Checks the descriptions, trims, fills an array errors in case of problem, and an array data if everything is OK
     * @param array $postData
     * @param array $data
     * @param array $errors
     * @param array $nameInArray
     * @return array
     */
    public function checkDescription(array $postData, array $data, string $nameInArray): array
    {
        $postData[$nameInArray] = trim($postData[$nameInArray]);
        if (strlen($postData[$nameInArray]) < 1) {
            $this->errors[$nameInArray] = 'La description ne peut pas être vide!';
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
     * @param string $nameInArray
     * @return array
     */
    public function checkNumber(array $postData, array $data, string $nameInArray): array
    {
        $postData[$nameInArray] = trim($postData[$nameInArray]);
        if (($postData[$nameInArray] < 0) || (!is_numeric($postData[$nameInArray]))) {
            $this->errors[$nameInArray] = 'Entrez un nombre supérieur à zéro.';
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
     * @param string $nameInArray
     * @return array
     */
    public function checkCaracteristics(array $postData, array $data, string $nameInArray): array
    {
        if (isset($postData[$nameInArray])) {
            if (strlen($postData[$nameInArray]) > 50) {
                $this->errors[$nameInArray] = "50 caractères maximum";
                return $this->errors;
            } else {
                $data[$nameInArray] = $postData[$nameInArray];
                $this->data[$nameInArray] = $data[$nameInArray];
                return $this->data;
            }
        }
    }
    /**
     * creates an array with each picture which has his own rubric
     * @param array $files
     * @return AdminRoomManager
     */
    public function createAnArrayRankedByPhoto(array $files): AdminRoomManager
    {
        foreach ($files['photo'] as $key => $rubrictitle) {
            $i = 0;
            foreach ($rubrictitle as $value) {
                $this->file[$i][$key] = $value;
                $i++;
            }
        }
        return $this;
    }

    /**
     * @param array $files
     * @return AdminRoomManager
     */
    public function createAnArrayWithThePhotoNames(array $files): AdminRoomManager
    {
        $galery = [];
        foreach ($files as $photo) {
            if (!empty($photo['name'])) {
                $galery = $photo['name'];
            }
        }
        $this->photoNames = $galery;
        return $this;
    }

    /**
     * check if the size of each picture is less than 1Mo
     * @return array
     */
    public function checkSize(): array
    {
        foreach ($this->file as $key => $photo) {
            if (isset($photo['size'][$key])) {
                if (($photo['size']) >= 100000) {
                    $this->errors[$key]['size'] = "La taille de votre fichier ne peut excéder 1 MB";
                }
            }
        }
        return $this->errors;
    }

    /**
     * checks if each file is an authorized type
     * @return AdminRoomManager
     */
    public function checkType(): AdminRoomManager
    {
        foreach ($this->file as $key => $photo) {
            if (isset($photo['type'][$key])) {
                $extension = strtolower(substr($photo['type'], -3));
                if (!in_array($extension, $this->autorizedExtensions)) {
                    $this->errors[$key]['type'] = 'Votre fichier doit être une image de type '
                        . implode(' ou ', $this->autorizedExtensions) . ' .';
                }
            }
        }
        return $this;
    }

    /**
     * gives an unique name for each picture
     * @param array $file
     * @return AdminRoomManager
     */
    public function changeName(array $file): AdminRoomManager
    {
        if (empty($this->errors)) {
            foreach ($file as $key => $photo) {
                if (isset($photo['name'][$key])) {
                    $photo['name'] = uniqid($photo['name']) . '.' . substr($photo['type'], -3);
                    $this->file[$key] = $photo;
                }
            }
        }
        return $this;
    }

    /**
     * Transfers the checked files in the folder upload
     */
    public function transferFiles(): void
    {
        if (empty($this->errors)) {
            foreach ($this->file as $key => $photo) {
                if (isset($photo['name'])) {
                    move_uploaded_file($photo['tmp_name'], 'assets/images/rooms/' . $photo['name']);
                }
            }
        }
    }

    /**
     * @param array $data
     */
    public function insert(array $data): void
    {
        if (empty($this->errors)) {
            // prepared request
            $statement = $this->pdo->prepare("INSERT INTO `room` (`name` , `description` , `price`)
VALUES ( :name, :description, :price );");
            $statement->bindValue('name', $data['name'], \PDO::PARAM_STR);
            $statement->bindValue('description', $data['description'], \PDO::PARAM_STR);
            $statement->bindValue('price', $data['price'], \PDO::PARAM_INT);
            $statement->execute();
        }
    }

    /**
     * @param array $data
     */
    public function insertCaracteristics(array $data): void
    {
        if (empty($this->errors)) {
            $id = count($this->rooms) - 1;
            $data['room_id'] = $this->rooms[$id]['id'] + 1;
            $statement = $this->pdo->prepare("INSERT INTO `room_caracteristic`
(`room_id`,`caracteristic1`,`caracteristic2`,`caracteristic3`, `caracteristic4`, `caracteristic5`,`caracteristic6`)
VALUES
(:room_id, :caracteristic1, :caracteristic2, :caracteristic3, :caracteristic4, :caracteristic5, :caracteristic6 );");
            $statement->bindValue('room_id', $data['room_id'], \PDO::PARAM_STR);
            $statement->bindValue('caracteristic1', $data['caracteristic1'], \PDO::PARAM_STR);
            $statement->bindValue('caracteristic2', $data['caracteristic2'], \PDO::PARAM_STR);
            $statement->bindValue('caracteristic3', $data['caracteristic2'], \PDO::PARAM_STR);
            $statement->bindValue('caracteristic4', $data['caracteristic4'], \PDO::PARAM_STR);
            $statement->bindValue('caracteristic5', $data['caracteristic5'], \PDO::PARAM_STR);
            $statement->bindValue('caracteristic6', $data['caracteristic6'], \PDO::PARAM_STR);
            $statement->execute();
        }
    }

    /**
     * @param array $data
     */
    public function addPhotosInDatabase(array $data): void
    {
        $id = count($this->rooms) - 1;
        $data['room_id'] = $this->rooms[$id]['id'] + 1;
        if (empty($this->errors)) {
            $statement = $this->pdo->prepare("INSERT INTO
 `room_photo` (`room_id`,`photo1`,`photo2`,`photo3`, `photo4`)
VALUES (:room_id, :photo1, :photo2, :photo3, :photo4);");
            $statement->bindValue('room_id', $data['room_id'], \PDO::PARAM_STR);
            $statement->bindValue('photo1', $this->file[0]['name'], \PDO::PARAM_STR);
            $statement->bindValue('photo2', $this->file[1]['name'], \PDO::PARAM_STR);
            $statement->bindValue('photo3', $this->file[2]['name'], \PDO::PARAM_STR);
            $statement->bindValue('photo4', $this->file[3]['name'], \PDO::PARAM_STR);
            $statement->execute();
        }
    }
}
