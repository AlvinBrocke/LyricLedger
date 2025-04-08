<?php

require_once __DIR__ . '/../models/violation.php';

class ViolationController {
    private $violation;
    public function __construct() { $this->violation = new Violation(); }

    public function report($data) {
        return $this->violation->reportViolation($data);
    }

    public function listByContent($contentId) {
        return $this->violation->getViolationsByContent($contentId);
    }
}