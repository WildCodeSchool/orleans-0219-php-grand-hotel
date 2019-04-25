<?php


namespace App\Controller;

use App\Model\RoomManager;

class RoomController extends AbstractController
{
    /**
     * returns a triple array with the rooms, the photos, and the caracteristics.
     * destroys some data that we don't need in the array, to make easier the for in the viewpage
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */

    public function index()
    {

        $roomManager = new RoomManager();
        $rooms = $roomManager->selectAllTheTables('room_id', 'id', 'room_id');

        $photos = $roomManager->selectAllThePicturesWithoutIds();
        $caracteristics = $roomManager->selectAllTheCaracteristicsWithoutIds();

        for ($i = 0; $i < count($caracteristics); $i++) {
            unset($caracteristics[$i]['id']);
            unset($caracteristics[$i]['room_id']);
        }
        return $this->twig->render('Rooms/index.html.twig', ['rooms' => $rooms, 'photos' => $photos,
            'caracteristics' => $caracteristics]);
    }
}
