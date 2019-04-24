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
        $photos = $adminRoomManager->selectQuiteAllFromFirstJoined();
        $caracteristics = $adminRoomManager->selectQuiteAllFromSecondJoined();
        $adminRoomManager->rooms = $adminRoomManager->selectAll();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $postData = $_POST;
            $adminRoomManager->checkName($postData, $data);
            $adminRoomManager->checkDescription($postData, $data, 'description');
            $adminRoomManager->checkNumber($postData, $data, 'price');
            foreach ($caracteristics[0] as $key => $caracteristic) {
                $adminRoomManager->checkCaracteristics($postData, $data, $key);
            }
            $adminRoomManager->insert($adminRoomManager->data);
            $adminRoomManager->insertCaracteristics($adminRoomManager->data);
            if ($_FILES) {
                $files = $_FILES;
                $adminRoomManager->createAnArrayRankedByPhoto($files);
                $adminRoomManager->createAnArrayWithThePhotoNames($adminRoomManager->file);
                $adminRoomManager->checkSize();
                $adminRoomManager->checkType();
                $adminRoomManager->changeName($adminRoomManager->file);
                $adminRoomManager->addPhotosInDatabase($adminRoomManager->data);
                $adminRoomManager->transferFiles();
            }
            header('location:../Admin/rooms?success=true');
        }
        return $this->twig->render(
            'Admin/addroom.html.twig',
            ['photos' => $photos, 'caracteristics' => $caracteristics, 'rooms' => $adminRoomManager->rooms]
        );
    }
}
