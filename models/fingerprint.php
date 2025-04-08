<?php

require_once __DIR__ . '/../classes/db.php';
class Fingerprint {
    private $db;

    public function __construct() {
        $this->db = new DB();
    }

    public function storeFingerprint($data) {
        $sql = "INSERT INTO fingerprints (id, content_id, fingerprint, segment_start, segment_end) VALUES (UUID(), :content_id, :fingerprint, :segment_start, :segment_end)";
        return $this->db->insert($sql, $data);
    }
}