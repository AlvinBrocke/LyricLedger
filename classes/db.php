<?php
require_once __DIR__ . '/../settings/db_cred.php';

class DB {
    private static $instance = null;
    protected $pdo;
    private $lastError = null;
    private $lastErrorInfo = null;

    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            error_log("Attempting database connection with DSN: " . $dsn);
            
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, DB_OPTIONS);
            error_log("Database connection successful");
            
            // Test the connection
            $test = $this->pdo->query("SELECT 1");
            if (!$test) {
                throw new PDOException("Connection test failed");
            }
            error_log("Database connection test successful");
            
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            error_log("Database connection failed: " . $this->lastError);
            error_log("Connection details - Host: " . DB_HOST . ", Database: " . DB_NAME . ", User: " . DB_USER);
            throw new Exception("Database connection failed. Please try again later.");
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getLastError() {
        return $this->lastError;
    }

    public function getLastErrorInfo() {
        return $this->lastErrorInfo;
    }

    public function fetch($sql, $params = []) {
        try {
            error_log("Executing fetch query: " . $sql);
            error_log("With params: " . print_r($params, true));
            
            $stmt = $this->pdo->prepare($sql);
            if (!$stmt) {
                $this->lastError = "Failed to prepare statement";
                $this->lastErrorInfo = $this->pdo->errorInfo();
                error_log("Statement preparation failed: " . print_r($this->lastErrorInfo, true));
                return false;
            }
            
            $stmt->execute($params);
            $result = $stmt->fetch();
            
            if ($result === false) {
                error_log("No results found for query");
            } else {
                error_log("Query executed successfully");
            }
            
            return $result;
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            $this->lastErrorInfo = isset($stmt) ? $stmt->errorInfo() : $this->pdo->errorInfo();
            error_log("Database fetch error: " . $this->lastError);
            error_log("Error info: " . print_r($this->lastErrorInfo, true));
            error_log("Failed query: " . $sql);
            error_log("Failed params: " . print_r($params, true));
            return false;
        }
    }

    public function fetchAll($sql, $params = []) {
        try {
            error_log("Executing fetchAll query: " . $sql);
            error_log("With params: " . print_r($params, true));
            
            $stmt = $this->pdo->prepare($sql);
            if (!$stmt) {
                $this->lastError = "Failed to prepare statement";
                $this->lastErrorInfo = $this->pdo->errorInfo();
                error_log("Statement preparation failed: " . print_r($this->lastErrorInfo, true));
                return false;
            }
            
            $stmt->execute($params);
            $result = $stmt->fetchAll();
            
            if ($result === false) {
                error_log("No results found for query");
            } else {
                error_log("Query executed successfully");
            }
            
            return $result;
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            $this->lastErrorInfo = isset($stmt) ? $stmt->errorInfo() : $this->pdo->errorInfo();
            error_log("Database fetchAll error: " . $this->lastError);
            error_log("Error info: " . print_r($this->lastErrorInfo, true));
            error_log("Failed query: " . $sql);
            error_log("Failed params: " . print_r($params, true));
            return false;
        }
    }

    public function insert($sql, $params = []) {
        try {
            error_log("Executing insert query: " . $sql);
            error_log("With params: " . print_r($params, true));
            $this->pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );

            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute($params);
            
            if (!$result) {
                $this->lastErrorInfo = $stmt->errorInfo();
                $this->lastError = $this->lastErrorInfo[2];
                error_log("Database insert failed. Error info: " . print_r($this->lastErrorInfo, true));
                error_log("Failed query: " . $sql);
                error_log("Failed params: " . print_r($params, true));
            } else {
                error_log("Insert successful. Last insert ID: " . $this->pdo->lastInsertId());
            }
            
            return $result ? $this->pdo->lastInsertId() : false;
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            error_log("Database insert error: " . $this->lastError);
            error_log("Failed query: " . $sql);
            error_log("Failed params: " . print_r($params, true));
            return false;
        }
    }

    public function update($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            error_log("Database update error: " . $this->lastError);
            return false;
        }
    }

    public function delete($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            error_log("Database delete error: " . $this->lastError);
            return false;
        }
    }

    public function beginTransaction() {
        return $this->pdo->beginTransaction();
    }

    public function commit() {
        return $this->pdo->commit();
    }

    public function rollBack() {
        return $this->pdo->rollBack();
    }
} 
