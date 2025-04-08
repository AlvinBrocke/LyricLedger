<?php 

require_once __DIR__ . '/../models/album.php';

class AlbumController {
    private $album;
    public function __construct() { $this->album = new Album(); }

    public function create($data) {
        return $this->album->createAlbum($data);
    }
}