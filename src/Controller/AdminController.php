<?php


namespace App\Controller;

use App\Model\AdminRoomManager;

class AdminController extends AbstractController
{
    /**Initializes the admin index.
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function index()
    {
        return $this->twig->render('Admin/index.html.twig');
    }

    /**
     * Gives the existing rooms in database
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     *
     */
    public function rooms()
    {
        $adminRoomManager = new AdminRoomManager();
        $rooms = $adminRoomManager->selectAll();

        return $this->twig->render('Admin/rooms.html.twig', ['rooms' => $rooms]);
    }


    /**
     * Checks the $_POST data
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function addroom()
    {
        $adminRoomManager = new AdminRoomManager();
        $data = [];
        $errors = [];
        $photos=$adminRoomManager->selectQuiteAllFromFirstJoined();
        $caracteristics=$adminRoomManager->selectQuiteAllFromSecondJoined();




        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $postData = $_POST;
            $adminRoomManager->checkName($postData, $data, $errors);
            $adminRoomManager->checkDescription($postData, $data, $errors, 'description');
            $adminRoomManager->checkNumber($postData, $data, $errors, 'price');
            $adminRoomManager->checkNumber($postData, $data, $errors, 'area');
            $adminRoomManager->checkCaracteristics($postData, $data, $errors, 'caracteristic1');
            $adminRoomManager->checkCaracteristics($postData, $data, $errors, 'caracteristic2');
            $adminRoomManager->checkCaracteristics($postData, $data, $errors, 'caracteristic3');
            $adminRoomManager->checkCaracteristics($postData, $data, $errors, 'caracteristic4');
            $adminRoomManager->checkCaracteristics($postData, $data, $errors, 'caracteristic5');
            $adminRoomManager->checkCaracteristics($postData, $data, $errors, 'caracteristic6');
        }


        return $this->twig->render('Admin/addroom.html.twig', ['data' => $adminRoomManager->data,
            'errors' => $adminRoomManager->errors, 'post' => $_POST]);
    }
}
