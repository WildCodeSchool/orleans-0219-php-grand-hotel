<?php
/**
 * Created by PhpStorm.
 * User: aurelwcs
 * Date: 08/04/19
 * Time: 18:40
 */

namespace App\Controller;

use App\Model\RoomManager;

class HomeController extends AbstractController
{
    /**
     * Display home page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $photosPerRoom = [];
        $caracteristicsPerRoom = [];
        $roomManager = new RoomManager();
        $rooms = $roomManager->selectTableRoom();
        foreach ($rooms as $key => $room) {
            $caracteristicsPerRoom[] = $roomManager->selectCaracteristics($room['name']);
            $photosPerRoom[] = $roomManager->selectPhotos($room['name']);
        }
        return $this->twig->render('Home/index.html.twig', ['rooms' => $rooms, 'photos'=>$photosPerRoom]);
    }
}
