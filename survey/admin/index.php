<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__ . '/../src/database.php';
require_once __DIR__ . '/helpers.php';
require __DIR__ . '/../src/queries.php';


if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'export') {
    require_once __DIR__ . '/../src/export.php';
}

// List allowed pages and returns 404 page not found if not found
$page    = $_GET['page'] ?? null;
$allowed = ['', 'tables', 'responses', 'students', 'feedbacks', 'deleted', 'sections'];
if (!in_array($page, $allowed)) {
    http_response_code(404);

    include_once 'views/head.php';
    include_once 'views/admin-sidebar.php';
    include_once 'views/admin-header.php';
    include_once 'views/modals.php';

    echo "<div class=\"admin-content\">";

    echo ($page === 'settings' || $page === 'notifications') ? "<h3>Page under construction.</h3>" : "<h3>Page not found.</h3>";
    echo "</div></main></div>";

    include 'views/admin-footer.php';
    exit;
}

redirectNoAdminData();

$db = new database();
$pdo = $db->connect();


// SECTION CARDS CONTENTS

// STEM
$results['sections_name_count'][0]['icon'] = 'atom';
$results['sections_name_count'][1]['icon'] = 'atom';
$results['sections_name_count'][2]['icon'] = 'atom';

// HUMMS
// $results['sections_name_count'][3]['icon'] = 'users';
// $results['sections_name_count'][4]['icon'] = 'users';

// ABM
// $results['sections_name_count'][5]['icon'] = 'briefcase-business';
// $results['sections_name_count'][6]['icon'] = 'briefcase-business';

// GAS
// $results['sections_name_count'][7]['icon'] = 'circle-dot';

// TVL
// $results['sections_name_count'][8]['icon'] = 'tool-case';

$sectionCards = $results['sections_name_count'];


// SUMMARY CARDS CONTENTS

$summaryCards = [
    [
        'icon'  => 'file-text',
        'value' => (string) ($results['total_responses'] ?? 0),
        'title' => 'Total Responses',
    ],
    [
        'icon'  => 'star',
        'value' => (string) ($results['overall_average'] ?? 0),
        'title' => 'Avg Rating',
    ],
    [
        'icon'  => 'target',
        'value' => (string) ($results['total_targets'] ?? 0),
        'title' => 'Survey Targets',
    ],
    [
        'icon'  => 'users',
        'value' => (string) ($results['total_students'] ?? 0),
        'title' => 'Total Students',
    ],
    // [
    //     'icon'  => 'bar-chart-3',
    //     'value' => (string) round(((($results['total_responses'] ?? 0) / 300) * 100), 2) . '%',
    //     'title' => 'Response Rate',
    // ],
];

// CHART CARDS CONTENTS

$chartCards = [
    [
        'title'   => 'Average Score by Category',
        'id'      => 'radarChart',
        'icon'    => 'pentagon',
        'classes' => 'grid-item card chart-card span-2'
    ],
    [
        'title'   => 'Responses by Section',
        'id'      => 'doughnutChart',
        'icon'    => 'torus',
        'classes' => 'grid-item card chart-card'
    ],
    [
        'title'   => 'Anonymous vs. Named',
        'id'      => 'pieChart',
        'icon'    => 'chart-pie',
        'classes' => 'grid-item card chart-card'
    ],

    [
        'title'   => 'Responses Over Time',
        'id'      => 'lineChart',
        'icon'    => 'chart-spline',
        'classes' => 'grid-item card chart-card span-2'
    ],
    [
        'title'   => 'Top 5 Highest-Rated Questions',
        'id'      => 'horizontalBarChart',
        'icon'    => 'chart-column',
        'classes' => 'grid-item card chart-card span-2'
    ],
    [
        'title'   => 'Score Distribution',
        'id'      => 'barChart',
        'icon'    => 'chart-column-increasing',
        'classes' => 'grid-item card chart-card'
    ],

    [   
        'title'   => 'Instructor Performance by Category',
        'id'      => 'groupedBarChart',
        'icon'    => 'trophy',
        'classes' => 'grid-item card chart-card span-3'
    ],
];


include_once 'views/head.php';
include_once 'views/admin-sidebar.php';
include_once 'views/admin-header.php';
include_once 'views/modals.php';

echo "<div class=\"admin-content\">";


if (isset($_GET['search'])) {
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $searchResults = [];

    // Search Query
    if (!empty($search)) {
        $searchTerm = "%$search%";
        $query = "
            SELECT
                CONCAT(COALESCE(s.surname, ''), ' ', COALESCE(s.given_name, '')) AS student_name,
                sec.name AS section_name,
                t.instructor,
                t.subject,
                f.text AS feedback,
                r.created_at
            FROM response r
            LEFT JOIN student s ON r.student_id = s.student_id
            LEFT JOIN target t ON r.target_id = t.target_id
            LEFT JOIN feedback f ON r.response_id = f.response_id
            LEFT JOIN section sec ON r.section_id = sec.section_id
            WHERE s.surname LIKE ?
              OR s.given_name LIKE ?
              OR t.instructor LIKE ?
              OR t.subject LIKE ?
              OR f.text LIKE ?
              OR sec.name LIKE ?
            ORDER BY r.created_at DESC;
        ";
        $stmt = $pdo->prepare($query);
        $stmt->execute(array_fill(0, 6, $searchTerm));
        $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->execute();
    }

    // Return search results as table
    if (!empty($searchResults)) {
        echo renderTable($searchResults, 'search', 'Search Results...');
    } elseif (!empty($search)) {
        echo "<h3>No results found.</h3>";
    }

    echo "</div></main></div>";
    include 'views/admin-footer.php';
    exit;
}


// debug();
// print_r($results);

// Pages



// Tables page
if ($page === 'tables') {
    // echo renderTable($results['Surveys'], 'poll', 'Surveys');
    // echo renderTable($results['instructor_leaderboard'], 'trophy', 'Instructor Leaderboard');
    // echo "<hr>";
    // echo renderTable($results['Students'], 'graduation-cap', 'Students');
    // echo renderTable($results['all_responses'], 'clipboard-list', 'All Responses');
    echo renderTable($results['Sections'], 'school', 'Sections');
    echo renderTable($results['Teachers (Targets)'], 'presentation', 'Subject and Teachers');
    echo renderTable($results['Questions'], 'file-question-mark', 'Questions');
    echo renderTable($results['Feedback with Context'], 'message-square-text', 'Feedback with Context');
    echo renderTable($results['Answers with Questions'], 'circle-question-mark', 'Answers with Questions');

    echo "</div></main></div>";
    include 'views/admin-footer.php';
    exit;
}


// Responses page
if ($page === 'responses') {
    echo renderTable($results['all_responses'], 'clipboard-list', 'All Responses');
    // echo renderTable($results['student_response'], 'clipboard-list', 'All Student Responses');

    echo "</div></main></div>";
    include 'views/admin-footer.php';
    exit;
}


// Students Page
if ($page === 'students') {
    echo renderTable($results['Students'], 'graduation-cap', 'Students');
    echo renderTable($results['students_updated'], 'circle-fading-arrow-up', 'Recently Updated Students');

    echo "</div></main></div>";
    include 'views/admin-footer.php';
    exit;
}


// Sections Page
if ($page === 'sections') {
    echo renderTable($results['Sections'] , 'table', 'All Sections', NULL, '?page=sections&action=add', 'plus', 'Add Section');

    echo "<section class=\"summary-grid\">";
    // renderSummaryCards($sectionCards);
    echo "</section>";
    
    // STEM
    renderSectionHeader('atom', 'STEM');
    echo "<section class=\"summary-grid\">";
    renderSummaryCards(array_splice($sectionCards, 0, 3));
    echo "</section>";
    
    // HUMSS
    renderSectionHeader('users', 'HUMSS');
    echo "</section>"; echo "<section class=\"summary-grid\">";
    renderSummaryCards(array_splice($sectionCards, 0, 2));
    echo "</section>";
    
    // ABM    
    renderSectionHeader('briefcase-business', 'ABM');
    echo "</section>"; echo "<section class=\"summary-grid\">";
    renderSummaryCards(array_splice($sectionCards, 0, 1));
    echo "</section>";

    // GAS
    renderSectionHeader('circle-dot', 'GAS');
    echo "</section>"; echo "<section class=\"summary-grid\">";
    renderSummaryCards(array_splice($sectionCards, 0, 1));
    echo "</section>";
    
    // TVL
    renderSectionHeader('tool-case', 'TVL');
    echo "</section>"; echo "<section class=\"summary-grid\">";
    renderSummaryCards(array_splice($sectionCards, 0, 1));
    echo "</section>";


    echo "</div></main></div>";
    include 'views/admin-footer.php';
    exit;
}


// Feedbacks Page
if ($page === 'feedbacks') {
    echo "<div class=\"card-header\"><h2 class=\"card-title\"><i data-lucide=\"message-square\" class=\"lucide-relative\"></i>All Feedbacks</h2></div>";
    echo "<section class=\"feedbacks-section\">";
    echo "<div class=\"feedback-masonry\">";
    echo renderFeedbacks($results['feedbacks']);
    echo "</div>";

    echo "</div></main></div>";
    include 'views/admin-footer.php';
    exit;
}

// Deleted page
if ($page === 'deleted') {
    echo renderTable($results['deleted_students'], 'user-x', 'Deleted Students');
    echo renderTable($results['deleted_responses'], 'message-square-off', 'Deleted Responses');

    echo "</div></main></div>";
    include 'views/admin-footer.php';
    exit;
}


?>

<!-- Default Page -->
<section class="card survey-card">
    <div class="survey-header" onclick="toggleCollapse(this)">
        <div class="survey-id-section">
            <span class="survey-id-label">ID</span>
            <select>
                <option value="v1">1</option>
                <option value="v2">2</option>
                <option value="v3" selected>3</option>
            </select>
        </div>

        <div class="survey-title-section">
            <h3 class="survey-title">
                <?= $results['survey'][0]['title']; ?>
            </h3>
            <div class="survey-title-actions">
                <a href="" class="btn survey-action-btn" title="Print">
                    <i data-lucide="printer">Print</i>
                </a>
                <a href="/survey/admin/index.php?action=export" class="btn survey-action-btn" title="Export CSV" value="export">
                    <i data-lucide="file-text">Export as CSV</i>
                </a>
            </div>
        </div>

        <i data-lucide="chevron-down" class="survey-chevron"></i>
    </div>

    <div class="survey-body">
        <p class="card-content">
            <?= $results['survey'][0]['description']; ?>
        </p>

        <div class="survey-actions">
            <button class="survey-btn btn-edit super-admin-only">
                <i data-lucide="pencil"></i> Edit
            </button>
            <button class="survey-btn btn-delete super-admin-only">
                <i data-lucide="trash-2"></i> Delete
            </button>
        </div>
    </div>
</section>

<section class="summary-grid"><?= renderSummaryCards($summaryCards); ?></section>
<section class="dashboard-grid"><?= renderChartCards($chartCards); ?></section>

<?php // echo renderTable($results['instructor_leaderboard'], 'trophy', 'Instructor Leaderboard'); ?>

<!-- RECENT FEEDBACKS SECTION -->
<section class="feedbacks-section">
    <div class="card-header">
        <h2 class="card-title"><i data-lucide="message-square" class="lucide-relative"></i>Recent Feedbacks</h2>
        <a href="?page=feedbacks" class="card-action">
            View All <i data-lucide="arrow-right" class="card-icon"></i>
        </a>
    </div>

    <!-- Masonry Container -->
    <div class="feedback-masonry">
        <?= renderFeedbacks($results['recent_feedbacks']); ?>
    </div>
</section>


<!-- admin-content / main-wrapper / admin-layout -->
</div>
</main>
</div>




<?php

$apiUrl = rtrim(getenv('API_URL') ?: 'https://localhost:8080', '/');
$someValue = 42;
[$categoryLabels, $categoryScores]   = getLabelsAndDatas($results['categories'], 'category', 'average');
[$sectionNames  , $sectionResponses] = getLabelsAndDatas($results['responses_by_section'], 'section', 'responses');
[$questionTexts , $questionScores]   = getLabelsAndDatas($results['highest_rating_question'], 'question', 'score');
[$answerLabels  , $answerCounts]     = getLabelsAndDatas($results['score_distribution'], 'label', 'count');
[$responseDates , $responseCounts]   = getLabelsAndDatas($results['responses_over_time'], 'day', 'count');
$instructorScores                    = getInstructorScores($results['instructor_rating_by_category']);
// [$instructorNames, $instructorScores] = getLabelsAndDatas($results['instructor_rating_by_category'], 'instructor', 'average');

// print_r($instructorNames);
// print_r($instructorScores);

?>

<script>
window.serverConfig = {
    apiUrl:           <?= json_encode($apiUrl);            ?>,

    // Pie Chart: Named vs Anonymous
    totalNamed:       <?= $results['total_anonymous'];     ?>,
    totalAnonymous:   <?= $results['total_not_anonymous']; ?>,

    // Radar Chart: Category Averages
    categoryLabels:   <?= json_encode($categoryLabels);    ?>,
    categoryScores:   <?= json_encode($categoryScores);    ?>,

    // Doughnut Chart: Responses by Section
    sectionNames:     <?= json_encode($sectionNames);      ?>,
    sectionResponses: <?= json_encode($sectionResponses);  ?>,

    // Horizontal Bar Chart: Top 5 Highest-Rated Questions
    questionTexts:    <?= json_encode($questionTexts);     ?>,
    questionScores:   <?= json_encode($questionScores);    ?>,

    // Bar Chart: Score Distribution
    answerLabels:     <?= json_encode($answerLabels);      ?>,
    answerCounts:     <?= json_encode($answerCounts);      ?>,

    // Line Chart: Responses Over Time
    responseDates:    <?= json_encode($responseDates);     ?>,
    responseCounts:   <?= json_encode($responseCounts);    ?>,

    // Grouped Bar Chart: Instructor Performance by Category
    instructorScores: <?= json_encode($instructorScores);  ?>,
};
</script>
<script src="/survey/admin/assets/js/admin-charts.js"></script>

<?php include 'views/admin-footer.php'; ?>
