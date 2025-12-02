<?php
// ملف: includes/auth.php
session_start();

class Authentication {
    private $conn;
    
    public function __construct($db_connection) {
        $this->conn = $db_connection;
    }
    
    public function login($username, $password) {
        // التحقق من هجمات Brute Force
        if ($this->checkBruteForce($username)) {
            return ['success' => false, 'message' => 'تم تجاوز عدد محاولات الدخول. حاول لاحقًا.'];
        }
        
        $stmt = $this->conn->prepare("SELECT id, username, password_hash FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows == 1) {
            $stmt->bind_result($user_id, $username, $password_hash);
            $stmt->fetch();
            
            if (password_verify($password, $password_hash)) {
                // إنشاء رمز جلسة آمن
                $session_id = bin2hex(random_bytes(32));
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;
                $_SESSION['session_token'] = $session_id;
                
                // تحديث آخر وقت دخول
                $this->updateLoginTime($user_id);
                
                return ['success' => true, 'message' => 'تم الدخول بنجاح'];
            } else {
                $this->logFailedAttempt($username);
                return ['success' => false, 'message' => 'اسم المستخدم أو كلمة المرور غير صحيحة'];
            }
        }
        
        return ['success' => false, 'message' => 'اسم المستخدم أو كلمة المرور غير صحيحة'];
    }
    
    public function checkBruteForce($username) {
        $now = time();
        $valid_attempts = $now - (30 * 60); // آخر 30 دقيقة
        
        $stmt = $this->conn->prepare("
            SELECT COUNT(*) as attempts 
            FROM login_attempts 
            WHERE username = ? AND attempt_time > ?
        ");
        $stmt->bind_param("si", $username, $valid_attempts);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        
        return $result['attempts'] >= 5;
    }
    
    public function validateSession() {
        if (!isset($_SESSION['user_id'], $_SESSION['session_token'])) {
            return false;
        }
        
        // التحقق من صلاحية الجلسة
        $stmt = $this->conn->prepare("
            SELECT session_token FROM user_sessions 
            WHERE user_id = ? AND expires_at > NOW()
        ");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        
        return $result && hash_equals($result['session_token'], $_SESSION['session_token']);
    }
}
?>