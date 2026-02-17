<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Send a Notification</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 800px; margin: 40px auto; }
        .section-title { border-left: 5px solid #009688; padding-left: 10px; margin-bottom: 10px; }
        .result-box { background: #f4f4f4; padding: 15px; border-radius: 6px; min-height: 80px; white-space: pre-wrap; word-wrap: break-word; margin-top: 10px; }
        .send-btn { margin-top: 10px; }
        label { font-weight: bold; color: #00796B; }
    </style>
</head>
<body class="w3-light-grey">

<div class="container">

    <div class="w3-container w3-teal w3-padding-24 w3-margin-bottom">
        <h1>Send a Notification</h1>
        <p class="w3-large">Test your notification system directly from the browser</p>
    </div>

    <?php
    $output = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $sendType = escapeshellarg($_POST['sendType'] ?? '');
        $message = escapeshellarg($_POST['message'] ?? '');

        if ($sendType && $message) {
            // Call your CLI script
            $command = "php index.php $sendType $message";
            $output = shell_exec($command); // capture output
        } else {
            $output = "Please select a send type and enter a message.";
        }
    }
    ?>

    <!-- Form card -->
    <div class="w3-card w3-white w3-padding w3-margin-bottom">
        <h2 class="section-title">Compose Message</h2>

        <form method="POST" action="sender.php">
            <label for="sendType">Send Type:</label>
            <select id="sendType" name="sendType" class="w3-select w3-border w3-margin-bottom">
                <option value="" disabled selected>Select type</option>
                <option value="email" <?php if(isset($sendType) && $sendType=='email') echo 'selected'; ?>>Email</option>
                <option value="sms" <?php if(isset($sendType) && $sendType=='sms') echo 'selected'; ?>>SMS</option>
                <option value="whatsapp" <?php if(isset($sendType) && $sendType=='whatsapp') echo 'selected'; ?>>WhatsApp</option>
            </select>

            <label for="message">Message:</label>
            <textarea id="message" name="message" rows="5" class="w3-input w3-border w3-margin-bottom" placeholder="Type your message here..."><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>

            <button type="submit" class="w3-button w3-teal send-btn">Send Message</button>
        </form>
    </div>

    <!-- Result card -->
    <div class="w3-card w3-white w3-padding">
        <h2 class="section-title">Result</h2>
        <div id="result" class="result-box">
            <?php echo $output ?: 'The output of sending will appear here.'; ?>
        </div>
    </div>

</div>

</body>
</html>
