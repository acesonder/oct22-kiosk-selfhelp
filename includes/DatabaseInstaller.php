<?php
/**
 * Database Installer/Initializer
 * Run this to set up or reset the database
 */

class DatabaseInstaller {
    private $db;
    private $schemaFile;
    
    public function __construct() {
        $this->schemaFile = __DIR__ . '/../database/schema.sql';
    }
    
    /**
     * Initialize database with schema
     */
    public function install($host, $username, $password, $database) {
        try {
            // First, connect without database to create it if needed
            $dsn = "mysql:host={$host};charset=utf8mb4";
            $pdo = new PDO($dsn, $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Create database if it doesn't exist
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $pdo->exec("USE `{$database}`");
            
            // Read and execute schema
            if (!file_exists($this->schemaFile)) {
                throw new Exception("Schema file not found: {$this->schemaFile}");
            }
            
            $schema = file_get_contents($this->schemaFile);
            
            // Split into individual statements
            $statements = array_filter(
                array_map('trim', explode(';', $schema)),
                function($stmt) {
                    return !empty($stmt) && !preg_match('/^--/', $stmt);
                }
            );
            
            foreach ($statements as $statement) {
                if (!empty($statement)) {
                    $pdo->exec($statement);
                }
            }
            
            return [
                'success' => true,
                'message' => 'Database initialized successfully!'
            ];
            
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Database installation failed: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Test database connection
     */
    public function testConnection($host, $username, $password, $database = null) {
        try {
            $dsn = "mysql:host={$host}";
            if ($database) {
                $dsn .= ";dbname={$database}";
            }
            $dsn .= ";charset=utf8mb4";
            
            $pdo = new PDO($dsn, $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            return ['success' => true, 'message' => 'Connection successful!'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Connection failed: ' . $e->getMessage()];
        }
    }
    
    /**
     * Backup database
     */
    public function backup($outputFile) {
        try {
            require_once __DIR__ . '/Database.php';
            $db = Database::getInstance();
            $conn = $db->getConnection();
            
            $tables = [];
            $result = $conn->query("SHOW TABLES");
            while ($row = $result->fetch(PDO::FETCH_NUM)) {
                $tables[] = $row[0];
            }
            
            $output = "-- KioskHelp Database Backup\n";
            $output .= "-- Date: " . date('Y-m-d H:i:s') . "\n\n";
            $output .= "SET FOREIGN_KEY_CHECKS=0;\n\n";
            
            foreach ($tables as $table) {
                // Drop table
                $output .= "DROP TABLE IF EXISTS `{$table}`;\n";
                
                // Create table
                $result = $conn->query("SHOW CREATE TABLE `{$table}`");
                $row = $result->fetch(PDO::FETCH_NUM);
                $output .= $row[1] . ";\n\n";
                
                // Insert data
                $result = $conn->query("SELECT * FROM `{$table}`");
                $numRows = $result->rowCount();
                
                if ($numRows > 0) {
                    $output .= "INSERT INTO `{$table}` VALUES\n";
                    $rows = [];
                    while ($row = $result->fetch(PDO::FETCH_NUM)) {
                        $row = array_map(function($value) use ($conn) {
                            return is_null($value) ? 'NULL' : $conn->quote($value);
                        }, $row);
                        $rows[] = '(' . implode(', ', $row) . ')';
                    }
                    $output .= implode(",\n", $rows) . ";\n\n";
                }
            }
            
            $output .= "SET FOREIGN_KEY_CHECKS=1;\n";
            
            file_put_contents($outputFile, $output);
            
            return [
                'success' => true,
                'message' => 'Backup created successfully!',
                'file' => $outputFile
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Backup failed: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get database statistics
     */
    public function getStats() {
        try {
            require_once __DIR__ . '/Database.php';
            $db = Database::getInstance();
            
            $stats = [
                'users' => $db->fetch("SELECT COUNT(*) as count FROM users")['count'],
                'clients' => $db->fetch("SELECT COUNT(*) as count FROM clients")['count'],
                'cases' => $db->fetch("SELECT COUNT(*) as count FROM cases")['count'],
                'assessments' => $db->fetch("SELECT COUNT(*) as count FROM assessments")['count'],
                'referrals' => $db->fetch("SELECT COUNT(*) as count FROM referrals")['count'],
                'appointments' => $db->fetch("SELECT COUNT(*) as count FROM appointments")['count'],
                'messages' => $db->fetch("SELECT COUNT(*) as count FROM messages")['count'],
                'providers' => $db->fetch("SELECT COUNT(*) as count FROM service_providers")['count'],
            ];
            
            return ['success' => true, 'stats' => $stats];
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
