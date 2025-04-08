<?php

// controllers/fingerprint_controller.php
require_once __DIR__ . '/../models/fingerprint.php';

class FingerprintController {
    private $fp;
    public function __construct() { $this->fp = new Fingerprint(); }

    public function store($data) {
        return $this->fp->storeFingerprint($data);
    }
}