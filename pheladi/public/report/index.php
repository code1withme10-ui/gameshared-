<?php
// index.php

// Path to JSON storage (ensure writable by the webserver)
define('REPORTS_FILE', __DIR__ . '/reports.json');

// Ensure the JSON file exists
if (!file_exists(REPORTS_FILE)) {
    file_put_contents(REPORTS_FILE, "[]", LOCK_EX);
}

// Helper: sanitize POST values
function clean($key) {
    return isset($_POST[$key]) ? trim($_POST[$key]) : '';
}

$savedEntry = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize inputs
    $report = [
        'company_name'   => htmlspecialchars(clean('company_name'), ENT_QUOTES),
        'project_name'   => htmlspecialchars(clean('project_name'), ENT_QUOTES),
        'host_name'      => htmlspecialchars(clean('host_name'), ENT_QUOTES),
        'platform'       => htmlspecialchars(clean('platform'), ENT_QUOTES),
        // date is server-side generated to avoid client manipulation
        'date'           => date('Y-m-d'), 
        'attendance'     => htmlspecialchars(clean('attendance'), ENT_QUOTES),
        'agenda'         => htmlspecialchars(clean('agenda'), ENT_QUOTES),
        'discussions'    => htmlspecialchars(clean('discussions'), ENT_QUOTES),
        'challenges'     => htmlspecialchars(clean('challenges'), ENT_QUOTES),
        'tasks_tomorrow' => htmlspecialchars(clean('tasks_tomorrow'), ENT_QUOTES),
        'notes'          => htmlspecialchars(clean('notes'), ENT_QUOTES),
        'created_at'     => date('c') // full timestamp
    ];

    // Read existing reports
    $jsonData = file_get_contents(REPORTS_FILE);
    $reports = json_decode($jsonData, true);
    if (!is_array($reports)) {
        $reports = [];
    }

    // Append and save
    $reports[] = $report;
    $newJson = json_encode($reports, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    if (file_put_contents(REPORTS_FILE, $newJson, LOCK_EX) !== false) {
        $savedEntry = $report;
    } else {
        $error = "Failed to save report. Check file permissions for " . REPORTS_FILE;
    }
}

// For the form default date (server-generated)
$today = date('Y-m-d');

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Daily Report Template</title>
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="container">
        <header>
            <h1>Daily Report</h1>
            <p class="muted">Fill this template daily — saved as JSON in <code>reports.json</code></p>
        </header>

        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($savedEntry): ?>
            <div class="alert alert-success">
                <strong>Saved!</strong> Your report for <em><?= htmlspecialchars($savedEntry['date']) ?></em> was saved.
            </div>

            <section class="saved">
                <h3>Saved entry (JSON)</h3>
                <pre><?= htmlspecialchars(json_encode($savedEntry, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) ?></pre>
            </section>
        <?php endif; ?>

        <form method="post" class="report-form">
            <div class="row">
                <label>Company name
                    <input type="text" name="company_name" required placeholder="Vhutambo EDU">
                </label>

                <label>Project name
                    <input type="text" name="project_name" required placeholder="Website creation">
                </label>
            </div>

            <div class="row">
                <label>Host name
                    <input type="text" name="host_name" placeholder="Pheladi Nchabeleng">
                </label>

                <label>Platform
                    <input type="text" name="platform" placeholder="Google Meet">
                </label>

                <label>Date
                    <input type="text" name="date" value="<?= $today ?>" readonly>
                </label>
            </div>

            <label>Attendance (who attended)
                <textarea name="attendance" rows="2" placeholder="List attendees, e.g. John Doe, Jane Smith"></textarea>
            </label>

            <label>Agenda
                <textarea name="agenda" rows="3" placeholder="Meeting agenda"></textarea>
            </label>

            <label>Discussions (summary)
                <textarea name="discussions" rows="6" placeholder="Key discussion points, decisions, outcomes"></textarea>
            </label>

            <label>Challenges / Blockers
                <textarea name="challenges" rows="3" placeholder="Any issues or blockers"></textarea>
            </label>

            <label>Tasks for tomorrow
                <textarea name="tasks_tomorrow" rows="3" placeholder="Planned tasks / owners / deadlines"></textarea>
            </label>

            <label>Notes
                <textarea name="notes" rows="2" placeholder="Other notes"></textarea>
            </label>

            <div class="actions">
                <button type="submit" class="btn">Save report</button>
                <button type="reset" class="btn btn-muted">Reset</button>
            </div>
        </form>

        <footer>
            <small class="muted">Tip: Make <code>reports.json</code> writable (e.g. chmod 664) so the server can save reports.</small>
        </footer>
    </main>
</body>
</html>

