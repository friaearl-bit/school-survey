<?php
declare(strict_types=1);

function processStudentInfo(array $input): array
{
    // Collect / sanitize inputs
    $data = [];
    $data['is_anonymous']   = !empty($input['is_anonymous']);
    $data['student_number'] = trim((string)($input['student_number'] ?? ''));
    $data['surname']        = trim((string)($input['surname']        ?? ''));
    $data['middle_name']    = trim((string)($input['middle_name']    ?? ''));
    $data['given_name']     = trim((string)($input['given_name']     ?? ''));
    $data['email']          = trim((string)($input['email']          ?? ''));
    $data['section_id']     = trim((string)($input['section_id']     ?? ''));


    // Validators
    $validEmail = static function(string $s): bool {
        if ($s === '') return false;
        return (bool) preg_match('/^((?!\.)[\w\-\._]*[^.])(@\w+)(\.\w+(\.\w+)?[^.\W])$/u', $s);
    };

    $validName = static function(string $s): bool {
        $s = trim($s);
        if ($s === '') return false;
        return (bool) preg_match("/^[a-zA-ZÀ-ÿЁёА-я0-9 '\-]{2,32}$/u", $s);
    };

    // Validate student number format: 2024-00000-MN-0
    // return (bool) preg_match('/^202[45]-\d{5}$/', $sn);
    $validSN = static function(string $s): ?string {
        $s = trim($s);
        if ($s === '') return null;
        if (preg_match('/^(202[345])-?(\d{5})-?(MN|QC)-?(\d+)$/i', $s, $m)) {
            return "{$m[1]}-{$m[2]}-".strtoupper($m[3])."-{$m[4]}";
        }
        return null;
    };


    $errors = [];

    if ($data['section_id'] === '') {
        $errors[] = 'Section is required.';
    }

    if (!$data['is_anonymous']) {
        if ($data['surname']        === '') { $errors[] = 'Surname required unless anonymous.';        }
        if ($data['given_name']     === '') { $errors[] = 'Given name required unless anonymous.';     }
        if ($data['email']          === '') { $errors[] = 'Email required unless anonymous.';          }
        if ($data['student_number'] === '') { $errors[] = 'Student number required unless anonymous.'; }
        if ($data['surname'] !== '' && ! $validName($data['surname'])) {
            $errors[] = 'Surname contains invalid characters or length.';
        }
        if ($data['given_name'] !== '' && ! $validName($data['given_name'])) {
            $errors[] = 'Given name contains invalid characters or length.';
        }
        if ($data['email'] !== '' && ! $validEmail($data['email'])) {
            $errors[] = 'Email is not valid.';
        }
        if (($formatted = $validSN($data['student_number'])) !== null) {
            $data['student_number'] = $formatted;
        } else {
            $errors[] = 'Student number must match format 2024-00000-MN-0.';
        }
    } else {
        // Set all to blank if anonymous
        $data['surname'] = $data['given_name'] = $data['middle_name'] = $data['email'] = $data['student_number'] = '';
    }

    return [$data, $errors];
}


function processSurvey(PDO $pdo, array $post): array {
    try {
        $pdo->beginTransaction();


////////////////////////////////////////////////////////////
//                       RESPONDENT                       //
////////////////////////////////////////////////////////////

        // If existing, only get studentID and do not insert into databasee
        //   else
        //     if not anonymous, insert into database & studentID = lastInsertId()
        //     else            , do not insert into database & studentID = null

        $respondent = $_SESSION['student_data'];

        if ($_SESSION['is_existing']) {
            $studentId = $respondent['student_id'];
        } else {
            // Insert new student only if not anonymous
            if (empty($respondent['is_anonymous'])) {
                $stmt = $pdo->prepare(
                    'INSERT INTO student
                        (surname, middle_name, given_name, email, student_number, section_id)
                     VALUES
                        (:surname, :middle_name, :given_name, :email, :student_number, :section_id)'
                );
                $stmt->execute([
                    ':surname'        => $respondent['surname']        ?: null,
                    ':middle_name'    => $respondent['middle_name']    ?: null,
                    ':given_name'     => $respondent['given_name']     ?: null,
                    ':email'          => $respondent['email']          ?: null,
                    ':student_number' => $respondent['student_number'] ?: null,
                    ':section_id'     => $respondent['section_id']     ?: null,
                ]);
                $studentId = $pdo->lastInsertId();
            } else {
                $studentId = null;
            }
        }


    ////////////////////////////////////////////////////////////
    //                     RESPONSE                           //
    ////////////////////////////////////////////////////////////

        $sql = 'INSERT INTO response (student_id, survey_id, target_id, section_id, is_anonymous, created_at)
                VALUES (:student_id, :survey_id, :target_id, :section_id, :is_anonymous, NOW())';
        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':student_id'  => $studentId,
            ':survey_id'   => 1,
            ':target_id'   => (int) $post['target'],
            ':section_id'  => (int) $respondent['section_id'],
            'is_anonymous' => !empty($respondent['is_anonymous']) ? 1 : 0,
        ]);
        
        $responseId = $pdo->lastInsertId();


    ////////////////////////////////////////////////////////////
    //                      ANSWERS                           //
    ////////////////////////////////////////////////////////////

        $allowed = ['1','2','3','4','5'];
        $stmt = $pdo->prepare('
            INSERT INTO answer (response_id, question_id, value)
            VALUES (:response_id, :question_id, :value)');

        foreach ($post as $key => $value) {
            if (strpos($key, 'q') === 0) {
                $questionId = (int) substr($key, 1); // remove q prefix
                if (!in_array((string)$value, $allowed, true)) { continue; } // skip invalid
                $stmt->execute([
                    ':response_id' => (int) $responseId,
                    ':question_id' => (int) $questionId,
                    ':value'       => (int) $value
                ]);
            }
        }


    ////////////////////////////////////////////////////////////
    //                     FEEDBACK                           //
    ////////////////////////////////////////////////////////////

        // Validate and sanitize input
        $feedback = isset($post['feedback']) ? trim($post['feedback']) : '';

        if ($feedback !== '') {
            $stmt = $pdo->prepare('INSERT INTO feedback (response_id, text) VALUES (?, ?)');
            $stmt->execute([$responseId, $feedback]);
        }


        $pdo->commit();
        return [
            'response_id'       => (int) $responseId,
            'submitted_targets' => (int) $post['target'],
            'has_submitted'     => true,
            'errors'            => []
        ];

    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}

?>
