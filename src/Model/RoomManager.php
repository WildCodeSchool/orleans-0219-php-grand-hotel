<?php

namespace App\Model;

class RoomManager extends AbstractManager
{
    /**
     * Gives the table name
     */
    const TABLE = 'room';

    /**
     * RoomManager constructor
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    /**
     * Selects the data from the table room
     * @return array
     */
    public function selectTableRoom(): array
    {
        return $this->pdo->query('SELECT name, description, price FROM room;')->fetchAll();
    }

    /**
     * Selects the caracteristics data for one room
     * @param string $roomName
     * @return array
     */
    public function selectCaracteristics(string $roomName): array
    {
        return $this->pdo->query(
            "SELECT  caracteristic.caracteristic_name FROM room
    JOIN room_caracteristic ON room_caracteristic.room_id = room.id
    JOIN caracteristic ON room_caracteristic.caracteristic_id = caracteristic.id WHERE
    room.name = '$roomName';"
        )->fetchAll();
    }

    /**
     * Selects the photos for one room
     * @param string $roomName
     * @return array
     */
    public function selectPhotos(string $roomName): array
    {
        return $this->pdo->query(
            "SELECT room_photo.photo_name FROM room_photo JOIN room ON
    room_photo.room_id = room.id WHERE room.name ='$roomName';"
        )->fetchAll();
    }
}
