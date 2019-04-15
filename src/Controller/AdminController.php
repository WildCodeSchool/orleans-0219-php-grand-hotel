<?php


namespace App\Controller;

use App\Model\AdminRoomManager;

class AdminController extends AbstractController
{
    /**
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



        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $postData = $_POST;
            $adminRoomManager->checkName($postData, $data, $errors);
            $adminRoomManager->checkDescription($postData, $data, $errors, 'description');
            $adminRoomManager->checkNumber($postData, $data, $errors, 'price');
            $adminRoomManager->checkNumber($postData, $data, $errors, 'area');

            for ($i = 1; $i < 7; $i++) {
                $nameInArray = ${'caracteristic' .$i};
                if (isset($postData[$nameInArray])) {
                    $adminRoomManager->checkCaracteristics($postData, $data, $errors, $nameInArray);
                }
            }
        }

        return $this->twig->render('Admin/addroom.html.twig', ['data' => $adminRoomManager->data,
            'errors' => $adminRoomManager->errors, 'post' => $_POST]);
    }
}
