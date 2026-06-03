<?php

function isSuperAdmin(): bool
{
    return (!empty($_SESSION['admin_data']) && $_SESSION['admin_data']['role'] === 'superadmin');
}

function renderSectionHeader(string $icon, string $title, string $actionIcon=NULL, string $actionTitle=NULL): void
{
    echo "
        <div class=\"card-header title\">
            <h2 class=\"card-title\"><i data-lucide=\"{$icon}\" class=\"lucide-relative\"></i>{$title}</h2>";
            if (!empty($actionIcon) && !empty($actionTitle)) { echo "<a href=\"?action=add\" class=\"card-action\"><i data-lucide=\"{$actionIcon}\" class=\"card-icon\"></i> {$actionTitle}</a>"; }
    echo "</div>";

}

////////////////////////////////////////////////////////////
// SUMMARY CARDS
////////////////////////////////////////////////////////////

function renderSummaryCards(array $cards): void
{
    if (empty($cards)) { echo "<p>No data found</p>."; return; }

    foreach ($cards as $card) {
        echo "
            <div class=\"grid-item card summary-card\">
                <div class=\"summary-content\">
                    <div class=\"summary-icon\"><i data-lucide=\"{$card['icon']}\"></i></div>
                    <div class=\"summary-details\">
                        <h3>{$card['title']}</h3>
                        <p class=\"summary-value\">{$card['value']}</p>
                    </div>
                </div>
            </div>
        ";
    }
}


////////////////////////////////////////////////////////////
// CHART CARDS
////////////////////////////////////////////////////////////

function renderChartCards(array $cards): void
{
    if (empty($cards)) { echo "<p>No data found</p>."; return; }

    foreach ($cards as $c) {
        echo "
            <div class=\"{$c['classes']}\">
                <div class=\"card-header\">
                    <h2 class=\"card-title\">
                        <i data-lucide=\"{$c['icon']}\" class=\"lucide-relative\"></i>
                        {$c['title']}
                    </h2>
                </div>
                <div class=\"chart-container\"><canvas id=\"{$c['id']}\"></canvas></div>
            </div>
    ";  
    }
}


////////////////////////////////////////////////////////////
// RENDER RECENT FEEDBACKS
////////////////////////////////////////////////////////////

function renderFeedbacks(array $feedbacks): void
{
    if (empty($feedbacks)) { echo "<p>No data found</p>."; return; }

    foreach ($feedbacks as $feedback) {
        echo "<div class=\"card feedback-card\">
            <div class=\"card-header\">
                "
                .
                ($feedback['name'] === 'Anonymous'
                ? '<i data-lucide="hat-glasses" class="feedback-icon"></i>'
                : '<i data-lucide="circle-user" class="feedback-icon"></i>')
                .
                "<div class=\"card-headline\">
                    <span class=\"card-title\">{$feedback['name']}</span>
                    <span class=\"card-subtitle\">{$feedback['section']}</span>
                </div>
            </div>
            <p class=\"card-content\">
                {$feedback['feedback']}
            </p>
            <div class=\"feedback-recipient\">For: {$feedback['instructor']} ({$feedback['subject']})</div>
            <time class=\"feedback-date\" datetime=\"{$feedback['date']}\">{$feedback['date']}</time>
        </div>";
    }
}

function actionBtn(string $href, string $class, string $title, string $icon): string
{
    // Escape all dynamic values
    $href  = htmlspecialchars($href , ENT_QUOTES);
    $class = htmlspecialchars($class, ENT_QUOTES);
    $title = htmlspecialchars($title, ENT_QUOTES);
    $icon  = htmlspecialchars($icon , ENT_QUOTES);

    // Return properly escaped HTML
    return sprintf(
        '<a href="%s" class="%s btn-action" title="%s"><i data-lucide="%s"></i></a>',
        $href, $class, $title,  $icon
    );
}


////////////////////////////////////////////////////////////
// TABLE LAYOUT
////////////////////////////////////////////////////////////

function renderTable(array $rows, string $icon=NULL, string $title=NULL, string $query=NULL, string $href=NULL, string $actionIcon=NULL, string $actionTitle=NULL): void
{
    $editable = in_array($title, ['Students', 'All Responses', 'Deleted Students'], true) ? true : false;

    if (!empty($icon) && !empty($title)) {
        echo "
            <div class=\"card-header title\">
                <h2 class=\"card-title\"><i data-lucide=\"{$icon}\" class=\"lucide-relative\"></i>{$title}</h2>";
                if (!empty($href) && !empty($actionIcon) && !empty($actionTitle)) { echo "<a href=\"{$href}\" class=\"card-action\"><i data-lucide=\"{$actionIcon}\" class=\"card-icon\"></i> {$actionTitle}</a>"; }
        echo "</div>";
    }

    if (empty($rows)) { echo "<p>No data found.</p>"; return; }

    echo "<div class=\"table-container\">";
    echo "<table class=\"table-alt\">";

    // TABLE HEADERS
    echo "<thead><tr>";
    foreach (array_keys($rows[0]) as $column) { echo "<th>" . htmlspecialchars($column) . "</th>"; }
    if (isSuperAdmin() && !empty($editable)) { echo "<th>Actions</th>"; } // Add actions column if superadmin
    echo "</tr></thead>";

    // TABLE ROWS
    echo "<tbody>";
    foreach ($rows as $row) {
        echo "<tr>";
        foreach ($row as $value) { echo "<td>" . htmlspecialchars((string) $value) . "</td>"; }


        // Add action buttons if superadmin
        if (isSuperAdmin() && !empty($editable)) {

            // Parse and clean current URL
            $uri = parse_url($_SERVER['REQUEST_URI']);
            parse_str($uri['query'] ?? '', $queryParams);

            // Remove old action params
            unset($queryParams['action'], $queryParams['id']);

            $path = $uri['path'] ?? '/';


            // Determine the ID field name
            $idField = in_array($title, ['Students', 'Deleted Students']) ? 'student_id' : 'response_id';
            $idValue = $row[$idField];

            // Build base URL . preserves all other query params
            $baseUrl = $path . '?' . http_build_query($queryParams);

            echo '<td class="actions">';

            // Edit button (Students only)
            if ($title === 'Students') { echo actionBtn($baseUrl . '&action=edit&id=' . $idValue, 'btn btn-edit', 'Edit', 'edit'); }

            // Restore button (Deleted Students only)
            if ($title === 'Deleted Students') { echo actionBtn($baseUrl . '&action=restore&id=' . $idValue, 'btn btn-restore', 'Restore', 'undo-2'); }

            // Delete button (all except Deleted Students)
            if ($title !== 'Deleted Students') { echo actionBtn($baseUrl . '&action=delete&id=' . $idValue, 'btn btn-delete', 'Delete', 'trash'); }

            echo '</td>';
        }
            echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";
    echo "</div><br>";
}













////////////////////////////////////////////////////////////
// CHART.JS - GET LABELS AND DATA
////////////////////////////////////////////////////////////

function getLabelsAndDatas(array $arr, string $label, string $data): array
{
    // Array Column for Associative Array + Array Values for Indexed Array
    $labels = array_values(array_column($arr, $label));
    $datas  = array_values(array_column($arr, $data));
    return [$labels, $datas];
}


function getInstructorScores(array $rows, string $name = null): array
{
    $map = [];
    foreach ($rows as $r) {
        $map[$r['instructor']][] = $r['average'];
    }
    if ($name === null) return $map;
    return $map[$name] ?? [];
}


?>