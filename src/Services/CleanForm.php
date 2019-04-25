<?php


namespace App\Services;

class CleanForm
{
    public function checkIfEmpty($postData, $errors, $key)
    {
        if (empty($postData)) {
            $errors[$key]['empty'] = "Ce champ doit être rempli.";
        }
        return $errors;
    }


    public function checkMaxLength($postData, $errors, $maxLength, $key)
    {
        if (strlen($postData) > $maxLength) {
            $errors[$key]['length'] = 'Cette rubrique ne doit pas dépasser ' . $maxLength . ' caractères.';
        }
        return $errors;
    }

    public function checkGrade($postData, $errors, $minGrade, $maxGrade, $key)
    {
        $authorizedGrades = [];
        for ($i = $minGrade; $i <= $maxGrade; $i++) {
            $authorizedGrades[] = $i;
        }
        if (!in_array($postData, $authorizedGrades)) {
            $errors[$key]['interval'] = 'La note doit être choisie parmi les chiffres suivants: ' .
                implode(" ou ", $authorizedGrades) . '.';
        }
        return $errors;
    }
}
