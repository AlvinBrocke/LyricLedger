<?php

require_once __DIR__ . '/../models/royalty_transaction.php';

class RoyaltyController {
    private $royalty;
    public function __construct() { $this->royalty = new RoyaltyTransaction(); }

    public function record($data) {
        return $this->royalty->recordTransaction($data);
    }

    public function getByUser($userId) {
        return $this->royalty->getTransactionsByUser($userId);
    }
}