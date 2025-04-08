<?php

require_once __DIR__ . '/../classes/DB.php';

class Playlist {
    private $db;

    public function __construct() {
        $this->db = new DB();
    }

    public function createPlaylist($data) {
        $sql = "INSERT INTO playlists (id, user_id, name, description, cover_image) VALUES (UUID(), :user_id, :name, :description, :cover_image)";
        return $this->db->insert($sql, $data);
    }

    public function addToPlaylist($playlistId, $contentId) {
        $sql = "INSERT INTO playlist_content (playlist_id, content_id) VALUES (:playlist_id, :content_id)";
        return $this->db->insert($sql, [
            'playlist_id' => $playlistId,
            'content_id' => $contentId
        ]);
    }

    public function getPlaylistContents($playlistId) {
        $sql = "SELECT c.* FROM content c JOIN playlist_content pc ON c.id = pc.content_id WHERE pc.playlist_id = :playlist_id";
        return $this->db->fetchAll($sql, ['playlist_id' => $playlistId]);
    }
}

// models/Album.php
class Album {
    private $db;

    public function __construct() {
        $this->db = new DB();
    }

    public function createAlbum($data) {
        $sql = "INSERT INTO albums (id, user_id, title, description, cover_image, release_date) VALUES (UUID(), :user_id, :title, :description, :cover_image, :release_date)";
        return $this->db->insert($sql, $data);
    }
}

// models/Genre.php
class Genre {
    private $db;

    public function __construct() {
        $this->db = new DB();
    }

    public function getAllGenres() {
        $sql = "SELECT * FROM genres";
        return $this->db->fetchAll($sql);
    }
}