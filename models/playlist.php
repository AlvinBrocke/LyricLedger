<?php

require_once __DIR__ . '/../classes/db.php';

class Playlist {
    private $db;
    private $userId;
    private $name;
    private $description;
    private $coverImage;
    private $contents = [];

    public function __construct($data = []) {
        $this->db = new DB();
        if (!empty($data)) {
            $this->setUserId($data['user_id'] ?? '');
            $this->setName($data['name'] ?? '');
            $this->setDescription($data['description'] ?? '');
            $this->setCoverImage($data['cover_image'] ?? '');
        }
    }

    public function setUserId($userId) {
        if (empty($userId)) {
            throw new InvalidArgumentException('User ID is required');
        }
        $this->userId = $userId;
    }

    public function setName($name) {
        if (strlen($name) < 3) {
            throw new InvalidArgumentException('Playlist name must be at least 3 characters long');
        }
        if (strlen($name) > 100) {
            throw new InvalidArgumentException('Playlist name cannot exceed 100 characters');
        }
        $this->name = $name;
    }

    public function setDescription($description) {
        if (strlen($description) > 500) {
            throw new InvalidArgumentException('Description cannot exceed 500 characters');
        }
        $this->description = $description;
    }

    public function setCoverImage($image) {
        if (!empty($image)) {
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            $extension = strtolower(pathinfo($image, PATHINFO_EXTENSION));
            if (!in_array($extension, $allowedTypes)) {
                throw new InvalidArgumentException('Invalid image format. Allowed formats: ' . implode(', ', $allowedTypes));
            }
        }
        $this->coverImage = $image;
    }

    public function addContent($contentId) {
        if (empty($contentId)) {
            throw new InvalidArgumentException('Content ID is required');
        }
        if (in_array($contentId, $this->contents)) {
            throw new InvalidArgumentException('Content already exists in playlist');
        }
        $this->contents[] = $contentId;
    }

    public function removeContent($contentId) {
        $key = array_search($contentId, $this->contents);
        if ($key !== false) {
            unset($this->contents[$key]);
            $this->contents = array_values($this->contents);
        }
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getName() {
        return $this->name;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getCoverImage() {
        return $this->coverImage;
    }

    public function getContents() {
        return $this->contents;
    }

    public function toArray() {
        return [
            'user_id' => $this->userId,
            'name' => $this->name,
            'description' => $this->description,
            'cover_image' => $this->coverImage,
            'contents' => $this->contents
        ];
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