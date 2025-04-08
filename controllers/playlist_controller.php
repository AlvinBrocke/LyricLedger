<?php

require_once __DIR__ . '/../models/playlist.php';

class PlaylistController {
    private $playlist;
    public function __construct() { $this->playlist = new Playlist(); }

    public function create($data) {
        return $this->playlist->createPlaylist($data);
    }

    public function addContent($playlistId, $contentId) {
        return $this->playlist->addToPlaylist($playlistId, $contentId);
    }

    public function getContents($playlistId) {
        return $this->playlist->getPlaylistContents($playlistId);
    }

}