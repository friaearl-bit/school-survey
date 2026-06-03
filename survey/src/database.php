<?php
class database {
    private $host;
    private $dbname;
    private $username;
    private $password;
    private $port;
    private $pdo;

    public function __construct() {
        $this->host     = $_ENV['DB_HOST'] ?? 'localhost';
        $this->dbname   = $_ENV['DB_NAME'] ?? 'school_classroom_feedback_survey_1';
        $this->username = $_ENV['DB_USER'] ?? 'root';
        $this->password = $_ENV['DB_PASS'] ?? '';
        $this->port     = $_ENV['DB_PORT'] ?? '3306';

        try {
            $this->pdo = new PDO(
                "mysql:host={$this->host};port={$this->port};dbname={$this->dbname};charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function connect() {
        return $this->pdo;
    }

    public function getAdminById(int $adminId): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT id, first_name, last_name
            FROM admins
            WHERE id = ?
        ");
        $stmt->execute([$adminId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getStudentById(int $studentId): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT student_id, student_number, surname, middle_name, given_name, email, section_id
            FROM student
            WHERE student_id = ?
        ");
        $stmt->execute([$studentId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function findStudent(array $student): ?array
    {
        $email         = $student['email']          ?? null;
        $studentNumber = $student['student_number'] ?? null;

        if ($email === null && $studentNumber === null) { return null; }

        $stmt = $this->pdo->prepare("
            SELECT
                student_number,
                surname,
                middle_name,
                given_name,
                email,
                section_id
            FROM
                student
            WHERE
                email = :email OR student_number = :student_number
            LIMIT 1
        ");

        $stmt->execute([
            ':email' => $email,
            ':student_number' => $studentNumber
        ]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getSubmittedTargetsByStudentId($studentId): ?array
    {
        if (empty($studentId)) {
            return [];
        }
        
        $tstmt = $this->pdo->prepare('SELECT target_id FROM response WHERE student_id = :student_id ORDER BY target_id ASC');
        $tstmt->execute([':student_id' => $studentId]);

        // Returns indexed array of target_id values (strings by default)
        $submittedTargets = $tstmt->fetchAll(PDO::FETCH_COLUMN, 0) ?: [];
        return $submittedTargets;

        // return $tstmt->fetchAll(PDO::FETCH_COLUMN, 0) ?: [];
    }

    public function updateAdminProfile(array $adminData): bool
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE admins SET first_name = :first_name, last_name = :last_name WHERE id = :admin_id;");
            $stmt->execute([
                ':first_name' => $adminData['first_name'],
                ':last_name'  => $adminData['last_name'],
                ':admin_id'   => $adminData['admin_id'],
            ]);

            return true;
        } catch (PDOException $e) {
            error_log("Admin profile update failed: " . $e->getMessage());
            return false;
        }
    }

    public function updateStudent($studentData): bool
    {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE
                    student
                SET
                    surname = :surname,
                    middle_name = :middle_name,
                    given_name = :given_name,
                    email = :email,
                    student_number = :student_number,
                    section_id = :section_id,
                    updated_by = :updated_by,
                    updated_at = NOW()
                WHERE
                    student_id = :student_id
            ");

            $stmt->execute([
                ':surname'        => $studentData['surname'],
                ':middle_name'    => $studentData['middle_name'],
                ':given_name'     => $studentData['given_name'],
                ':email'          => $studentData['email'],
                ':student_number' => $studentData['student_number'],
                ':section_id'     => $studentData['section_id'],
                ':student_id'     => $studentData['student_id'],
                ':updated_by'     => $_SESSION['admin_data']['admin_id'],
            ]);

            return true;
        } catch (PDOException $e) {
            error_log("Soft delete failed: " . $e->getMessage());
            return false;
        }
    }

    public function softDeleteStudent($studentId): bool
    {
        try {
            $this->pdo->beginTransaction();

            // 1. Soft delete the student
            $stmt = $this->pdo->prepare("UPDATE `student` SET `deleted_at` = NOW() WHERE `student_id` = ?");
            $stmt->execute([$studentId]);

            // 2. Get all responses for this student
            $stmt = $this->pdo->prepare("SELECT `response_id` FROM `response` WHERE `student_id` = ?");
            $stmt->execute([$studentId]);
            $responseIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

            // 3. Soft delete each response AND its related data
            foreach ($responseIds as $responseId) {
                // Soft delete the response
                $stmt = $this->pdo->prepare("UPDATE `response` SET `deleted_at` = NOW() WHERE `response_id` = ?");
                $stmt->execute([$responseId]);

                // Soft delete related answers
                $stmt = $this->pdo->prepare("UPDATE `answer` SET `deleted_at` = NOW() WHERE `response_id` = ?");
                $stmt->execute([$responseId]);

                // Soft delete related feedback
                $stmt = $this->pdo->prepare("UPDATE `feedback` SET `deleted_at` = NOW() WHERE `response_id` = ?");
                $stmt->execute([$responseId]);
            }

            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Soft delete failed: " . $e->getMessage());
            return false;
        }
    }

    public function restoreStudent($studentId): bool
    {
        try {
            $this->pdo->beginTransaction();

            // 1. Restore the student
            $stmt = $this->pdo->prepare("UPDATE `student` SET `deleted_at` = NULL WHERE `student_id` = ?");
            $stmt->execute([$studentId]);

            // 2. Restore all responses for this student
            $stmt = $this->pdo->prepare("UPDATE `response` SET `deleted_at` = NULL WHERE `student_id` = ?");
            $stmt->execute([$studentId]);

            // 3. Restore related answers and feedback
            $stmt = $this->pdo->prepare("
                UPDATE `answer` a
                JOIN `response` r ON a.response_id = r.response_id
                SET a.deleted_at = NULL
                WHERE r.student_id = ?
            ");
            $stmt->execute([$studentId]);

            $stmt = $this->pdo->prepare("
                UPDATE `feedback` f
                JOIN `response` r ON f.response_id = r.response_id
                SET f.deleted_at = NULL
                WHERE r.student_id = ?
            ");
            $stmt->execute([$studentId]);

            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Restore failed: " . $e->getMessage());
            return false;
        }
    }


    function softDeleteResponse($responseId): bool
    {
        try {
            $this->pdo->beginTransaction();

            // Soft delete the response
            $stmt = $this->pdo->prepare("UPDATE `response` SET `deleted_at` = NOW() WHERE `response_id` = ?");
            $stmt->execute([$responseId]);

            // Soft delete related answers
            $stmt = $this->pdo->prepare("UPDATE `answer` SET `deleted_at` = NOW() WHERE `response_id` = ?");
            $stmt->execute([$responseId]);

            // Soft delete related feedback
            $stmt = $this->pdo->prepare("UPDATE `feedback` SET `deleted_at` = NOW() WHERE `response_id` = ?");
            $stmt->execute([$responseId]);

            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Soft delete failed: " . $e->getMessage());
            return false;
        }
    }

    public function restoreResponse($responseId): bool
    {
        try {
            $this->pdo->beginTransaction();

            // Restore the response
            $stmt = $this->pdo->prepare("UPDATE `response` SET `deleted_at` = NULL WHERE `response_id` = ?");
            $stmt->execute([$responseId]);

            // Restore related answers
            $stmt = $this->pdo->prepare("UPDATE `answer` SET `deleted_at` = NULL WHERE `response_id` = ?");
            $stmt->execute([$responseId]);

            // Restore related feedback
            $stmt = $this->pdo->prepare("UPDATE `feedback` SET `deleted_at` = NULL WHERE `response_id` = ?");
            $stmt->execute([$responseId]);

            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Restore failed: " . $e->getMessage());
            return false;
        }
    }
}
?>
