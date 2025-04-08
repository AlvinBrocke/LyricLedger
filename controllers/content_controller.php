<?php
require_once __DIR__ . '/../models/content.php';

class ContentController {
    private $content;
    public function __construct() { $this->content = new Content(); }

    public function upload($data) {
        return $this->content->uploadContent($data);
    }

    public function getByUser($userId) {
        return $this->content->getAllByUser($userId);
    }

    public function getDetails($id) {
        return $this->content->getContentDetails($id);
    }
}