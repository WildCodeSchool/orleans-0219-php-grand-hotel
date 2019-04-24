<?php


namespace App\Controller;

use App\Model\AdminRoomManager;

class AdminController extends AbstractController
{

    /**Initializes the admin index.
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        return $this->twig->render('Admin/index.html.twig');
    }


    /**Gives the existing rooms in database
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function rooms()
    {
        $adminRoomManager = new AdminRoomManager();
        $rooms = $adminRoomManager->selectAll();

        return $this->twig->render('Admin/rooms.html.twig', ['rooms' => $rooms]);
    }


    /**Checks the $_POST data
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function addroom()
    {
        $adminRoomManager = new AdminRoomManager();
        $data = [];
        $errors = [];
        $photos = $adminRoomManager->selectQuiteAllFromFirstJoined();
        $caracteristics = $adminRoomManager->selectQuiteAllFromSecondJoined();


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $postData = $_POST;
            $adminRoomManager->checkName($postData, $data, $errors);
            $adminRoomManager->checkDescription($postData, $data, $errors, 'description');
            $adminRoomManager->checkNumber($postData, $data, $errors, 'price');
            foreach ($caracteristics[0] as $key => $caracteristic) {
                $adminRoomManager->checkCaracteristics($postData, $data, $errors, $key);
            }
            if (isset($_FILES)) {
                $fileData = $_FILES;
                foreach ($photos[0] as $key => $photo) {
                    $adminRoomManager->checkPhotos($fileData, $data, $errors, $key);

                }
            }


        }


        return $this->twig->render('Admin/addroom.html.twig', ['data' => $adminRoomManager->data,
            'errors' => $adminRoomManager->errors, 'photos' => $photos, 'caracteristics' => $caracteristics,
            'filedata'=>$fileData]);
    }
}
