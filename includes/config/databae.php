<?php
/**
 * ملف إعدادات قاعدة البيانات لنظام TRMS
 * إعدادات الاتصال وتكوين قاعدة البيانات
 */

// منع الوصول المباشر
defined('ROOT_PATH') or define('ROOT_PATH', dirname(dirname(__FILE__)));
if (!defined('TRMS_ACCESS')) {
    header('HTTP/1.0 403 Forbidden');
    die('Direct access not allowed');
}

class DatabaseConfig {
    // معلومات الاتصال بقاعدة البيانات
    private const DB_HOST = 'localhost';
    private const DB_PORT = '3306';
    private const DB_NAME = 'trms_database';
    private const DB_USER = 'root';
    private const DB_PASS = '';
    private const DB_CHARSET = 'utf8mb4';
    
    // إعدادات الأداء
    private const MAX_CONNECTIONS = 20;
    private const CONNECT_TIMEOUT = 5;
    private const QUERY_TIMEOUT = 30;
    
    // إعدادات النسخ الاحتياطي
    private const BACKUP_PATH = ROOT_PATH . '/backups/';
    private const BACKUP_RETENTION_DAYS = 30;
    
    // إعدادات التخزين المؤقت
    private const QUERY_CACHE_ENABLED = true;
    private const QUERY_CACHE_TIME = 3600; // ثانية
    
    // أمان الاتصال
    private const USE_SSL = false;
    private const SSL_CA = '';
    private const SSL_CERT = '';
    private const SSL_KEY = '';
    
    // أمان قاعدة البيانات
    private const PREVENT_SQL_INJECTION = true;
    private const ESCAPE_ALL_STRINGS = true;
    private const LIMIT_QUERY_RESULTS = 1000;
    
    // السجلات والمراقبة
    private const LOG_QUERIES = true;
    private const LOG_SLOW_QUERIES = true;
    private const SLOW_QUERY_THRESHOLD = 2; // ثانية
    private const LOG_ERRORS = true;
    
    // التكرار والنسخ الاحتياطي
    private const ENABLE_REPLICATION = false;
    private const READ_REPLICA_HOST = 'localhost';
    private const READ_REPLICA_PORT = '3307';
    
    // إعدادات خاصة بالتطبيق
    private const TIMEZONE = 'Asia/Riyadh';
    private const DATE_FORMAT = 'Y-m-d H:i:s';
    
    // التهيئة
    private static $instance = null;
    private $connection = null;
    private $read_connection = null;
    private $query_count = 0;
    private $query_log = [];
    private $last_query = '';
    private $transaction_level = 0;
    
    /**
     * الحصول على نسخة واحدة من الكائن (Singleton Pattern)
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * منع الإنشاء الخارجي
     */
    private function __construct() {
        $this->initialize();
    }
    
    /**
     * تهيئة الاتصال بقاعدة البيانات
     */
    private function initialize() {
        // تعيين المنطقة الزمنية
        date_default_timezone_set(self::TIMEZONE);
        
        // إنشاء مجلد النسخ الاحتياطي إذا لم يكن موجوداً
        if (!file_exists(self::BACKUP_PATH)) {
            mkdir(self::BACKUP_PATH, 0755, true);
        }
        
        // إنشاء الاتصال الرئيسي
        $this->createConnection();
        
        // إنشاء اتصال للقراءة فقط إذا تم تمكين التكرار
        if (self::ENABLE_REPLICATION) {
            $this->createReadConnection();
        }
    }
    
    /**
     * إنشاء اتصال بقاعدة البيانات
     */
    private function createConnection() {
        try {
            // إعدادات SSL إذا تم تمكينها
            $ssl_options = [];
            if (self::USE_SSL && self::SSL_CA) {
                $ssl_options = [
                    MYSQLI_CLIENT_SSL,
                    MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT
                ];
            }
            
            // إنشاء الاتصال
            $this->connection = new mysqli(
                self::DB_HOST,
                self::DB_USER,
                self::DB_PASS,
                self::DB_NAME,
                self::DB_PORT
            );
            
            // التحقق من الأخطاء
            if ($this->connection->connect_error) {
                throw new Exception('فشل الاتصال بقاعدة البيانات: ' . $this->connection->connect_error);
            }
            
            // تعيين مجموعة الأحرف
            $this->connection->set_charset(self::DB_CHARSET);
            
            // تعيين إعدادات الجلسة
            $this->connection->query("SET time_zone = '+03:00'");
            $this->connection->query("SET sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION'");
            $this->connection->query("SET NAMES " . self::DB_CHARSET);
            
            // تسجيل الاتصال الناجح
            $this->logEvent('DATABASE_CONNECTED', 'تم الاتصال بنجاح بقاعدة البيانات');
            
        } catch (Exception $e) {
            $this->handleError($e);
            throw $e;
        }
    }
    
    /**
     * إنشاء اتصال للقراءة فقط
     */
    private function createReadConnection() {
        try {
            $this->read_connection = new mysqli(
                self::READ_REPLICA_HOST,
                self::DB_USER,
                self::DB_PASS,
                self::DB_NAME,
                self::READ_REPLICA_PORT
            );
            
            if ($this->read_connection->connect_error) {
                throw new Exception('فشل الاتصال بقاعدة البيانات للقراءة: ' . $this->read_connection->connect_error);
            }
            
            $this->read_connection->set_charset(self::DB_CHARSET);
            
        } catch (Exception $e) {
            // في حالة فشل اتصال القراءة، نستخدم الاتصال الرئيسي
            $this->read_connection = $this->connection;
            $this->logEvent('READ_CONNECTION_FAILED', $e->getMessage());
        }
    }
    
    /**
     * الحصول على اتصال قاعدة البيانات
     */
    public function getConnection($for_write = false) {
        if ($for_write || !self::ENABLE_REPLICATION || $this->read_connection === null) {
            return $this->connection;
        }
        return $this->read_connection;
    }
    
    /**
     * تنفيذ استعلام
     */
    public function query($sql, $params = [], $types = '') {
        $start_time = microtime(true);
        $this->query_count++;
        $this->last_query = $sql;
        
        try {
            // الحصول على الاتصال المناسب
            $connection = $this->getConnection($this->isWriteQuery($sql));
            
            // إعداد الاستعلام إذا كانت هناك معاملات
            if (!empty($params)) {
                $stmt = $connection->prepare($sql);
                
                if (!$stmt) {
                    throw new Exception('فشل إعداد الاستعلام: ' . $connection->error);
                }
                
                // ربط المعاملات
                if (!empty($params)) {
                    $bind_params = [$types];
                    foreach ($params as &$param) {
                        $bind_params[] = &$param;
                    }
                    call_user_func_array([$stmt, 'bind_param'], $bind_params);
                }
                
                // تنفيذ الاستعلام
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();
                
            } else {
                // تنفيذ استعلام بسيط
                $result = $connection->query($sql);
                
                if (!$result && $connection->error) {
                    throw new Exception('فشل تنفيذ الاستعلام: ' . $connection->error);
                }
            }
            
            // حساب وقت التنفيذ
            $execution_time = microtime(true) - $start_time;
            
            // تسجيل الاستعلام البطيء
            if (self::LOG_SLOW_QUERIES && $execution_time > self::SLOW_QUERY_THRESHOLD) {
                $this->logSlowQuery($sql, $execution_time, $params);
            }
            
            // تسجيل الاستعلام
            if (self::LOG_QUERIES) {
                $this->query_log[] = [
                    'sql' => $sql,
                    'params' => $params,
                    'time' => $execution_time,
                    'timestamp' => date(self::DATE_FORMAT)
                ];
            }
            
            return $result;
            
        } catch (Exception $e) {
            $this->handleError($e, $sql, $params);
            return false;
        }
    }
    
    /**
     * تنفيذ استعلام SELECT
     */
    public function select($sql, $params = [], $types = '') {
        $result = $this->query($sql, $params, $types);
        
        if ($result === false) {
            return [];
        }
        
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        
        $result->free();
        return $rows;
    }
    
    /**
     * تنفيذ استعلام SELECT للحصول على صف واحد
     */
    public function selectOne($sql, $params = [], $types = '') {
        $result = $this->query($sql, $params, $types);
        
        if ($result === false) {
            return null;
        }
        
        $row = $result->fetch_assoc();
        $result->free();
        
        return $row ?: null;
    }
    
    /**
     * تنفيذ استعلام INSERT
     */
    public function insert($table, $data) {
        $columns = [];
        $placeholders = [];
        $values = [];
        $types = '';
        
        foreach ($data as $column => $value) {
            $columns[] = $this->escapeIdentifier($column);
            $placeholders[] = '?';
            $values[] = $value;
            $types .= $this->getParamType($value);
        }
        
        $sql = "INSERT INTO {$table} (" . implode(', ', $columns) . ") 
                VALUES (" . implode(', ', $placeholders) . ")";
        
        $result = $this->query($sql, $values, $types);
        
        if ($result === false) {
            return false;
        }
        
        return $this->getConnection(true)->insert_id;
    }
    
    /**
     * تنفيذ استعلام UPDATE
     */
    public function update($table, $data, $where, $where_params = [], $where_types = '') {
        $sets = [];
        $values = [];
        $types = '';
        
        foreach ($data as $column => $value) {
            $sets[] = $this->escapeIdentifier($column) . " = ?";
            $values[] = $value;
            $types .= $this->getParamType($value);
        }
        
        $sql = "UPDATE {$table} SET " . implode(', ', $sets) . " WHERE {$where}";
        
        // إضافة معاملات WHERE
        if (!empty($where_params)) {
            $values = array_merge($values, $where_params);
            $types .= $where_types;
        }
        
        $result = $this->query($sql, $values, $types);
        
        return $result !== false;
    }
    
    /**
     * تنفيذ استعلام DELETE
     */
    public function delete($table, $where, $params = [], $types = '') {
        $sql = "DELETE FROM {$table} WHERE {$where}";
        $result = $this->query($sql, $params, $types);
        
        return $result !== false;
    }
    
    /**
     * بدء معاملة
     */
    public function beginTransaction() {
        if ($this->transaction_level === 0) {
            $this->getConnection(true)->begin_transaction();
        }
        $this->transaction_level++;
        
        $this->logEvent('TRANSACTION_BEGIN', "بدء معاملة (المستوى: {$this->transaction_level})");
        return $this->transaction_level;
    }
    
    /**
     * تأكيد المعاملة
     */
    public function commit() {
        if ($this->transaction_level === 1) {
            $this->getConnection(true)->commit();
        }
        
        if ($this->transaction_level > 0) {
            $this->transaction_level--;
        }
        
        $this->logEvent('TRANSACTION_COMMIT', "تأكيد معاملة (المستوى: {$this->transaction_level})");
        return true;
    }
    
    /**
     * تراجع المعاملة
     */
    public function rollback() {
        if ($this->transaction_level === 1) {
            $this->getConnection(true)->rollback();
        }
        
        if ($this->transaction_level > 0) {
            $this->transaction_level--;
        }
        
        $this->logEvent('TRANSACTION_ROLLBACK', "تراجع عن معاملة (المستوى: {$this->transaction_level})");
        return true;
    }
    
    /**
     * التحقق مما إذا كان الاستعلام للكتابة
     */
    private function isWriteQuery($sql) {
        $sql = trim(strtoupper($sql));
        $write_keywords = ['INSERT', 'UPDATE', 'DELETE', 'REPLACE', 'CREATE', 'ALTER', 'DROP', 'TRUNCATE', 'RENAME', 'LOCK', 'UNLOCK'];
        
        foreach ($write_keywords as $keyword) {
            if (strpos($sql, $keyword) === 0) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * تهريب المعرفات
     */
    private function escapeIdentifier($identifier) {
        return '`' . str_replace('`', '``', $identifier) . '`';
    }
    
    /**
     * الحصول على نوع المعلمة
     */
    private function getParamType($value) {
        if (is_int($value)) {
            return 'i';
        } elseif (is_float($value)) {
            return 'd';
        } elseif (is_string($value)) {
            return 's';
        } else {
            return 'b'; // blob
        }
    }
    
    /**
     * معالجة الأخطاء
     */
    private function handleError(Exception $e, $sql = '', $params = []) {
        $error_message = $e->getMessage();
        $error_code = $e->getCode();
        
        // تسجيل الخطأ
        if (self::LOG_ERRORS) {
            $this->logError($error_message, $sql, $params);
        }
        
        // إرسال بريد للمسؤول في حالة الأخطاء الحرجة
        if ($error_code >= 500) {
            $this->notifyAdmin($error_message, $sql);
        }
        
        // عرض رسالة خطأ مناسبة للمستخدم
        if (defined('DEBUG_MODE') && DEBUG_MODE) {
            // في وضع التطوير، عرض التفاصيل
            die("خطأ في قاعدة البيانات: " . $error_message . "<br>الاستعلام: " . htmlspecialchars($sql));
        } else {
            // في الإنتاج، عرض رسالة عامة
            error_log("Database Error: " . $error_message);
            die("عذراً، حدث خطأ في النظام. الرجاء المحاولة لاحقاً.");
        }
    }
    
    /**
     * تسجيل الأخطاء
     */
    private function logError($error, $sql = '', $params = []) {
        $log_entry = [
            'timestamp' => date(self::DATE_FORMAT),
            'error' => $error,
            'sql' => $sql,
            'params' => json_encode($params),
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'script' => $_SERVER['PHP_SELF'] ?? 'unknown'
        ];
        
        $log_file = ROOT_PATH . '/logs/db_errors_' . date('Y-m-d') . '.log';
        $log_line = json_encode($log_entry, JSON_UNESCAPED_UNICODE) . PHP_EOL;
        
        file_put_contents($log_file, $log_line, FILE_APPEND | LOCK_EX);
    }
    
    /**
     * تسجيل الاستعلامات البطيئة
     */
    private function logSlowQuery($sql, $time, $params = []) {
        $log_entry = [
            'timestamp' => date(self::DATE_FORMAT),
            'execution_time' => round($time, 4),
            'sql' => $sql,
            'params' => json_encode($params),
            'threshold' => self::SLOW_QUERY_THRESHOLD
        ];
        
        $log_file = ROOT_PATH . '/logs/slow_queries_' . date('Y-m-d') . '.log';
        $log_line = json_encode($log_entry, JSON_UNESCAPED_UNICODE) . PHP_EOL;
        
        file_put_contents($log_file, $log_line, FILE_APPEND | LOCK_EX);
    }
    
    /**
     * تسجيل الأحداث
     */
    private function logEvent($type, $message) {
        $log_entry = [
            'timestamp' => date(self::DATE_FORMAT),
            'type' => $type,
            'message' => $message,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ];
        
        $log_file = ROOT_PATH . '/logs/db_events_' . date('Y-m-d') . '.log';
        $log_line = json_encode($log_entry, JSON_UNESCAPED_UNICODE) . PHP_EOL;
        
        file_put_contents($log_file, $log_line, FILE_APPEND | LOCK_EX);
    }
    
    /**
     * إشعار المسؤول
     */
    private function notifyAdmin($error, $sql = '') {
        // في الإصدار الحقيقي، سيتم إرسال بريد للمسؤول
        // هذا مثال بسيط
        $to = 'admin@trms.com';
        $subject = 'خطأ حرج في قاعدة بيانات TRMS';
        $message = "حدث خطأ في قاعدة البيانات:\n\n";
        $message .= "الخطأ: " . $error . "\n";
        $message .= "الاستعلام: " . $sql . "\n";
        $message .= "الوقت: " . date(self::DATE_FORMAT) . "\n";
        $message .= "IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown') . "\n";
        
        // mail($to, $subject, $message);
    }
    
    /**
     * النسخ الاحتياطي لقاعدة البيانات
     */
    public function backup($compress = true) {
        try {
            $backup_file = self::BACKUP_PATH . 'trms_backup_' . date('Y-m-d_H-i-s') . '.sql';
            
            // الأمر لتصدير قاعدة البيانات
            $command = sprintf(
                'mysqldump --host=%s --port=%s --user=%s --password=%s --databases %s > %s',
                escapeshellarg(self::DB_HOST),
                escapeshellarg(self::DB_PORT),
                escapeshellarg(self::DB_USER),
                escapeshellarg(self::DB_PASS),
                escapeshellarg(self::DB_NAME),
                escapeshellarg($backup_file)
            );
            
            // تنفيذ الأمر
            exec($command, $output, $return_var);
            
            if ($return_var !== 0) {
                throw new Exception('فشل إنشاء النسخة الاحتياطية');
            }
            
            // الضغط إذا طلب
            if ($compress) {
                $compressed_file = $backup_file . '.gz';
                exec("gzip -9 $backup_file");
                $backup_file = $compressed_file;
            }
            
            // تنظيف الملفات القديمة
            $this->cleanOldBackups();
            
            $this->logEvent('BACKUP_CREATED', "تم إنشاء نسخة احتياطية: $backup_file");
            return $backup_file;
            
        } catch (Exception $e) {
            $this->logEvent('BACKUP_FAILED', $e->getMessage());
            return false;
        }
    }
    
    /**
     * تنظيف النسخ الاحتياطية القديمة
     */
    private function cleanOldBackups() {
        $files = glob(self::BACKUP_PATH . 'trms_backup_*');
        $now = time();
        
        foreach ($files as $file) {
            if (is_file($file)) {
                $file_age = $now - filemtime($file);
                $max_age = self::BACKUP_RETENTION_DAYS * 24 * 60 * 60;
                
                if ($file_age > $max_age) {
                    unlink($file);
                    $this->logEvent('BACKUP_CLEANED', "تم حذف النسخة الاحتياطية القديمة: $file");
                }
            }
        }
    }
    
    /**
     * استعادة قاعدة البيانات من نسخة احتياطية
     */
    public function restore($backup_file) {
        try {
            if (!file_exists($backup_file)) {
                throw new Exception('ملف النسخة الاحتياطية غير موجود');
            }
            
            // فك الضغط إذا كان مضغوطاً
            if (pathinfo($backup_file, PATHINFO_EXTENSION) === 'gz') {
                $uncompressed_file = str_replace('.gz', '', $backup_file);
                exec("gunzip -c $backup_file > $uncompressed_file");
                $backup_file = $uncompressed_file;
            }
            
            // الأمر لاستيراد قاعدة البيانات
            $command = sprintf(
                'mysql --host=%s --port=%s --user=%s --password=%s %s < %s',
                escapeshellarg(self::DB_HOST),
                escapeshellarg(self::DB_PORT),
                escapeshellarg(self::DB_USER),
                escapeshellarg(self::DB_PASS),
                escapeshellarg(self::DB_NAME),
                escapeshellarg($backup_file)
            );
            
            // تنفيذ الأمر
            exec($command, $output, $return_var);
            
            if ($return_var !== 0) {
                throw new Exception('فشل استعادة النسخة الاحتياطية');
            }
            
            // تنظيف الملف غير المضغوط المؤقت
            if (isset($uncompressed_file)) {
                unlink($uncompressed_file);
            }
            
            $this->logEvent('BACKUP_RESTORED', "تم استعادة قاعدة البيانات من: $backup_file");
            return true;
            
        } catch (Exception $e) {
            $this->logEvent('BACKUP_RESTORE_FAILED', $e->getMessage());
            return false;
        }
    }
    
    /**
     * التحقق من صحة الاتصال
     */
    public function checkConnection() {
        try {
            $result = $this->query("SELECT 1");
            return $result !== false;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * الحصول على إحصائيات قاعدة البيانات
     */
    public function getStats() {
        $stats = [
            'query_count' => $this->query_count,
            'connection_status' => $this->connection->stat(),
            'server_info' => $this->connection->server_info,
            'server_version' => $this->connection->server_version,
            'client_info' => $this->connection->client_info,
            'client_version' => $this->connection->client_version,
            'host_info' => $this->connection->host_info,
            'protocol_version' => $this->connection->protocol_version,
            'thread_id' => $this->connection->thread_id,
            'warning_count' => $this->connection->warning_count,
            'affected_rows' => $this->connection->affected_rows,
            'insert_id' => $this->connection->insert_id,
            'transaction_level' => $this->transaction_level,
            'last_query' => $this->last_query
        ];
        
        return $stats;
    }
    
    /**
     * الحصول على حجم قاعدة البيانات
     */
    public function getDatabaseSize() {
        $sql = "SELECT 
                    table_schema as 'database',
                    SUM(data_length + index_length) as 'size_bytes',
                    ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) as 'size_mb'
                FROM information_schema.TABLES 
                WHERE table_schema = ?";
        
        $result = $this->selectOne($sql, [self::DB_NAME], 's');
        return $result;
    }
    
    /**
     * تحسين قاعدة البيانات
     */
    public function optimize() {
        $sql = "SHOW TABLES";
        $tables = $this->select($sql);
        
        $results = [];
        foreach ($tables as $table) {
            $table_name = current($table);
            $this->query("OPTIMIZE TABLE `$table_name`");
            $results[] = "تم تحسين الجدول: $table_name";
        }
        
        $this->logEvent('DATABASE_OPTIMIZED', "تم تحسين جميع الجداول");
        return $results;
    }
    
    /**
     * إغلاق الاتصال
     */
    public function close() {
        if ($this->connection) {
            $this->connection->close();
        }
        
        if ($this->read_connection && $this->read_connection !== $this->connection) {
            $this->read_connection->close();
        }
        
        $this->logEvent('CONNECTION_CLOSED', "تم إغلاق اتصالات قاعدة البيانات");
    }
    
    /**
     * إعدادات خاصة بالتطبيق
     */
    public static function getConfig($key = null) {
        $config = [
            'db_host' => self::DB_HOST,
            'db_port' => self::DB_PORT,
            'db_name' => self::DB_NAME,
            'db_charset' => self::DB_CHARSET,
            'timezone' => self::TIMEZONE,
            'date_format' => self::DATE_FORMAT,
            'max_connections' => self::MAX_CONNECTIONS,
            'backup_path' => self::BACKUP_PATH,
            'backup_retention_days' => self::BACKUP_RETENTION_DAYS
        ];
        
        return $key ? ($config[$key] ?? null) : $config;
    }
    
    /**
     * منع الاستنساخ
     */
    private function __clone() {}
    
    /**
     * منع unserialize
     */
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
    
    /**
     * المدمر
     */
    public function __destruct() {
        $this->close();
    }
}

/**
 * وظيفة مساعدة لإنشاء كائن قاعدة البيانات
 */
function getDatabase() {
    return DatabaseConfig::getInstance();
}

/**
 * وظيفة مساعدة للتنفيذ السريع للاستعلامات
 */
function db_query($sql, $params = [], $types = '') {
    $db = DatabaseConfig::getInstance();
    return $db->query($sql, $params, $types);
}

/**
 * وظيفة مساعدة للاستعلام SELECT
 */
function db_select($sql, $params = [], $types = '') {
    $db = DatabaseConfig::getInstance();
    return $db->select($sql, $params, $types);
}

/**
 * وظيفة مساعدة للاستعلام SELECT للحصول على صف واحد
 */
function db_select_one($sql, $params = [], $types = '') {
    $db = DatabaseConfig::getInstance();
    return $db->selectOne($sql, $params, $types);
}

/**
 * وظيفة مساعدة للاستعلام INSERT
 */
function db_insert($table, $data) {
    $db = DatabaseConfig::getInstance();
    return $db->insert($table, $data);
}

/**
 * وظيفة مساعدة للاستعلام UPDATE
 */
function db_update($table, $data, $where, $where_params = [], $where_types = '') {
    $db = DatabaseConfig::getInstance();
    return $db->update($table, $data, $where, $where_params, $where_types);
}

/**
 * وظيفة مساعدة للاستعلام DELETE
 */
function db_delete($table, $where, $params = [], $types = '') {
    $db = DatabaseConfig::getInstance();
    return $db->delete($table, $where, $params, $types);
}

/**
 * وظيفة مساعدة لبدء معاملة
 */
function db_begin_transaction() {
    $db = DatabaseConfig::getInstance();
    return $db->beginTransaction();
}

/**
 * وظيفة مساعدة لتأكيد معاملة
 */
function db_commit() {
    $db = DatabaseConfig::getInstance();
    return $db->commit();
}

/**
 * وظيفة مساعدة لتراجع معاملة
 */
function db_rollback() {
    $db = DatabaseConfig::getInstance();
    return $db->rollback();
}

/**
 * وظيفة مساعدة للحصول على آخر معرف مضاف
 */
function db_last_insert_id() {
    $db = DatabaseConfig::getInstance();
    $connection = $db->getConnection(true);
    return $connection->insert_id;
}

/**
 * وظيفة مساعدة للتهريب الآمن للبيانات
 */
function db_escape($string) {
    $db = DatabaseConfig::getInstance();
    $connection = $db->getConnection();
    return $connection->real_escape_string($string);
}

// تعريف ثوابت النظام
define('TRMS_ACCESS', true);
define('DEBUG_MODE', true); // تغيير إلى false في الإنتاج

// إنشاء كائن قاعدة البيانات عند تحميل الملف
$database = DatabaseConfig::getInstance();

?>