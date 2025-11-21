<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Developer Daily Progress</title>
<link rel="stylesheet" href="forms/style.css">
</head>

<body>
<main class="container">
<h1>Developer Daily Progress</h1>

<form action="./submit.php" method="post" novalidate>

    <label for="name">Name *</label>
    <input id="name" name="name" type="text" required>

    <label for="date">Date *</label>
    <input id="date" name="date" type="date" required>

    <label for="project">Project / Feature</label>
    <input id="project" name="project" type="text" placeholder="(optional)">

    <label for="tasks">What task(s) did you complete today? *</label>
    <textarea id="tasks" name="tasks" rows="4" required></textarea>

    <label for="hours">How many hours did you spend? *</label>
    <input id="hours" name="hours" type="number" min="0" step="0.25" required>

    <label for="status">Task status</label>
    <select id="status" name="status">
        <option>Not started</option>
        <option>In progress</option>
        <option selected>Completed</option>
        <option>Blocked</option>
    </select>

    <label for="challenges">Any challenges?</label>
    <textarea id="challenges" name="challenges" rows="3"></textarea>

    <label for="need_help">Do you need help?</label>
    <select id="need_help" name="need_help">
        <option value="No" selected>No</option>
        <option value="Yes">Yes</option>
    </select>

    <label for="help_details">If yes, specify the help needed</label>
    <textarea id="help_details" name="help_details" rows="3" placeholder="(optional)"></textarea>

    <label for="productivity">Rate your productivity (1–5) *</label>
    <input id="productivity" name="productivity" type="number" min="1" max="5" required>

    <label for="notes">Additional notes (optional)</label>
    <textarea id="notes" name="notes" rows="3"></textarea>

    <button type="submit">Submit Progress</button>

</form>
</main>
</body>
</html>
