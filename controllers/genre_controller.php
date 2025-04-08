<?php

// controllers/genre_controller.php
require_once __DIR__ . '/../models/genre.php';

class GenreController {
    private $genre;
    public function __construct() { $this->genre = new Genre(); }

    public function listAll() {
        return $this->genre->getAllGenres();
    }
}