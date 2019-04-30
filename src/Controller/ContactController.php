<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 16:07
 * PHP version 7
 */
namespace App\Controller;

use App\Model\ContactManager;

use App\Services\CleanForm;

/**
 * Class FormController
 *
 */
class ContactController extends AbstractController
{
    /**
     * Display item listing
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {

        $errors = [];
        $forms = ["firstname" => 50,
            "mail" => 50,
            "message" => 255];
        $contactManager = new ContactManager();
        $cleanForm = new CleanForm();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $postData = $_POST;
            $postData["firstname"] = $cleanForm->trim($postData["firstname"]);
            $postData["mail"] = $cleanForm->trim($postData["mail"]);
            $postData["message"] = $cleanForm->trim($postData["message"]);
            $errors = $cleanForm->checkIfEmpty($postData, "firstname", $errors);
            $errors = $cleanForm->checkIfEmpty($postData, "mail", $errors);
            $errors = $cleanForm->checkIfEmpty($postData, "message", $errors);
            $errors = $cleanForm->checkMaxLength(
                $postData["firstname"],
                $errors,
                $forms["firstname"],
                "firstname"
            );
            $errors = $cleanForm->checkMaxLength(
                $postData["mail"],
                $errors,
                $forms["mail"],
                "mail"
            );
            $errors = $cleanForm->checkMaxLength(
                $postData["message"],
                $errors,
                $forms["message"],
                "message"
            );
            if (empty($errors)) {
                $contactManager->insert($postData);
                header('location:index/?success=true&');
            }

        }
        return $this->twig->render('Contact/index.html.twig', ['errors' => $errors, 'get' => $_GET,]);
    }
}
