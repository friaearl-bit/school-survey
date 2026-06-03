<?php

require_once __DIR__ . '/queries.php';

$filename = 'export_' . date('Y-m-d_H-i-s') . '.csv';

$wideFormatSqlQuery = "
    SELECT
        COALESCE(s.name, '') AS section,
        CASE WHEN r.is_anonymous = 1 THEN 'Anonymous' ELSE COALESCE(
            CONCAT(
                NULLIF(su.surname, ''),
                CASE WHEN su.middle_name IS NOT NULL AND su.middle_name != '' THEN CONCAT(' ', su.middle_name) ELSE ''
            END,
            CASE WHEN su.given_name IS NOT NULL AND su.given_name != '' THEN CONCAT(' ', su.given_name) ELSE ''
        END
    ),
    ''
    )
    END AS student_name,
    CASE WHEN r.is_anonymous = 0 THEN COALESCE(su.student_number, '') ELSE ''
    END AS student_number,
    CASE WHEN r.is_anonymous = 0 THEN COALESCE(su.email, '') ELSE ''
    END AS student_email,
    COALESCE(t.instructor, '') AS target_instructor,
    COALESCE(t.subject, '') AS target_subject,
    COALESCE(f.text, '') AS feedback,
    MAX(
        CASE WHEN a.question_id = 1 THEN a.value ELSE NULL
    END
    ) AS q1,
    MAX(
        CASE WHEN a.question_id = 2 THEN a.value ELSE NULL
    END
    ) AS q2,
    MAX(
        CASE WHEN a.question_id = 3 THEN a.value ELSE NULL
    END
    ) AS q3,
    MAX(
        CASE WHEN a.question_id = 4 THEN a.value ELSE NULL
    END
    ) AS q4,
    MAX(
        CASE WHEN a.question_id = 5 THEN a.value ELSE NULL
    END
    ) AS q5,
    MAX(
        CASE WHEN a.question_id = 6 THEN a.value ELSE NULL
    END
    ) AS q6,
    MAX(
        CASE WHEN a.question_id = 7 THEN a.value ELSE NULL
    END
    ) AS q7,
    MAX(
        CASE WHEN a.question_id = 8 THEN a.value ELSE NULL
    END
    ) AS q8,
    MAX(
        CASE WHEN a.question_id = 9 THEN a.value ELSE NULL
    END
    ) AS q9,
    MAX(
        CASE WHEN a.question_id = 10 THEN a.value ELSE NULL
    END
    ) AS q10,
    MAX(
        CASE WHEN a.question_id = 11 THEN a.value ELSE NULL
    END
    ) AS q11,
    MAX(
        CASE WHEN a.question_id = 12 THEN a.value ELSE NULL
    END
    ) AS q12,
    MAX(
        CASE WHEN a.question_id = 13 THEN a.value ELSE NULL
    END
    ) AS q13,
    MAX(
        CASE WHEN a.question_id = 14 THEN a.value ELSE NULL
    END
    ) AS q14,
    MAX(
        CASE WHEN a.question_id = 15 THEN a.value ELSE NULL
    END
    ) AS q15,
    MAX(
        CASE WHEN a.question_id = 16 THEN a.value ELSE NULL
    END
    ) AS q16,
    MAX(
        CASE WHEN a.question_id = 17 THEN a.value ELSE NULL
    END
    ) AS q17,
    MAX(
        CASE WHEN a.question_id = 18 THEN a.value ELSE NULL
    END
    ) AS q18,
    MAX(
        CASE WHEN a.question_id = 19 THEN a.value ELSE NULL
    END
    ) AS q19,
    MAX(
        CASE WHEN a.question_id = 20 THEN a.value ELSE NULL
    END
    ) AS q20,
    MAX(
        CASE WHEN a.question_id = 21 THEN a.value ELSE NULL
    END
    ) AS q21,
    MAX(
        CASE WHEN a.question_id = 22 THEN a.value ELSE NULL
    END
    ) AS q22,
    MAX(
        CASE WHEN a.question_id = 23 THEN a.value ELSE NULL
    END
    ) AS q23,
    MAX(
        CASE WHEN a.question_id = 24 THEN a.value ELSE NULL
    END
    ) AS q24,
    MAX(
        CASE WHEN a.question_id = 25 THEN a.value ELSE NULL
    END
    ) AS q25
    FROM
        response r
    LEFT JOIN section s ON
        r.section_id = s.section_id
    LEFT JOIN student su ON
        r.student_id = su.student_id
    LEFT JOIN target t ON
        r.target_id = t.target_id
    LEFT JOIN feedback f ON
        r.response_id = f.response_id
    LEFT JOIN answer a ON
        r.response_id = a.response_id
    GROUP BY
        r.response_id,
        s.name,
        r.is_anonymous,
        su.surname,
        su.middle_name,
        su.given_name,
        su.student_number,
        su.email,
        t.instructor,
        t.subject,
        f.text
    ORDER BY
        s.name,
        CASE WHEN r.is_anonymous = 0 THEN su.surname ELSE 'ZZZ'
    END,
    CASE WHEN r.is_anonymous = 0 THEN su.given_name ELSE ''
    END,
    t.instructor;
";

function exportCSV(PDO $pdo, string $query, string $filename, bool $bom = false) {

    // 1. Fetch Data
    $stmt = $pdo->query($query);

    // 2. Set HTTP Headers for Download
    header("Content-Type: text/csv; charset=utf-8");
    header("Content-Disposition: attachment; filename=\"{$filename}\"");

    // 3. Open Output Stream
    $output = fopen('php://output', 'w');

    // Optional BOM for Excel
    if ($bom) { fwrite($output, "\xEF\xBB\xBF"); }

    // 4. Write header row
    $firstRow = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($firstRow !== false) {
        fputcsv($output, array_keys($firstRow));
        fputcsv($output, $firstRow);

        // 5. Write remaining rows
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            fputcsv($output, $row);
        }
    }

    fclose($output);
    exit;
}


if ( ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') && ($_REQUEST['action'] ?? '') === 'export') {
    exportCSV($pdo, $wideFormatSqlQuery, $filename, true);
}


?>
