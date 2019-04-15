<?php


namespace App\Model;

class AdminRoomManager extends AbstractManager
{


    public $boum = [0, 1];
    public $data = [];
    public $postData = [];
    public $errors = [];
    public $numberOfCaracteristics = 6;

    /**
     *
     */
    const TABLE = 'room';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }


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

    public function checkDescription(array $postData, $data, array $errors, string $nameInArray): array
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

    public function checkNumber(array $postData, $data, array $errors, string $nameInArray): array
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

    public function checkArea(array $postData, $data, array $errors, string $nameInArray): array
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
