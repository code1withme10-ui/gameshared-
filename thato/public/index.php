<?php
// Development Gateway Landing Page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>🚀 Welcome Developer – Your Workspace Is Ready!</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: "Segoe UI", Tahoma, sans-serif;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: #fff;
        }

        h1, h2, h3 {
            text-align: center;
            margin-top: 20px;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(6px);
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.20);
        }

        .card {
            background: rgba(255,255,255,0.20);
            padding: 20px;
            border-radius: 14px;
            margin: 20px 0;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }

        code {
            background: rgba(0,0,0,0.3);
            padding: 3px 6px;
            border-radius: 6px;
            color: #ffe;
        }

        ul { line-height: 1.7; }

        .folder {
            font-weight: bold;
            color: #ffeb3b;
        }
    </style>
</head>
<body>

<h1>🌟 Welcome to Your Creche Development Environment 🌟</h1>
<h3>You're all set! Your workspace is configured successfully 🎉</h3>

<div class="container">

    <div class="card">
        <h2>📍 Your Current Web Root</h2>
        <p>Your <b>wwwroot</b> is located at:</p>
        <p style="font-size: 1.2em; color:#fff;"><b><?php echo __DIR__; ?></b></p>
    </div>

    <div class="card">
        <h2>🚧 Why You're Seeing This Page</h2>
        <p>
            If this screen appears, it means you haven't yet created your main <code>index.php</code> in the web root.
            This placeholder page is here to help you get started quickly. 💡
        </p>
    </div>

    <div class="card">
        <h2>📁 Recommended Project Structure (Laravel-Style)</h2>
        <p>To prepare you for real-world development, we recommend using a Laravel-inspired layout.</p>

<pre style="background:rgba(0,0,0,0.4); padding:15px; border-radius:10px;">
project-root/   ← this is your current folder <?php echo dirname(__DIR__); ?> 
│── app/
│   ├── Http/
│   ├── Models/
│── public/      ← this is your current folder <?php echo __DIR__; ?>     
│   ├── index.php
│   ├── css/
│   ├── js/
│   ├── images/
│── vendor/
│── composer.json
</pre>

        <p>👉 You will be mainly working in the <span class="folder">../app</span>, <span class="folder">../views</span>, and <span class="folder">../routes</span> folders.</p>
    </div>

    <div class="card">
        <h2>📦 Sample <code>index.php</code> to Get You Started</h2>
        <p>Move this into your <b>web root</b> and customize as needed:</p>

<pre style="background:rgba(0,0,0,0.4); padding:15px; border-radius:10px; overflow:auto;">
&lt;?php
require __DIR__ . '/../vendor/autoload.php';

echo "&lt;h1&gt;🚀 Your Project Is Running!&lt;/h1&gt;";
echo "&lt;p&gt;Edit your files in ../app and ../views&lt;/p&gt;";
?&gt;
</pre>
    </div>

    <div class="card">
        <h2>🛠 Steps to Begin Building Your Website</h2>
        <ul>
            <li>Move your working <code>index.php</code>, <code>css</code>, <code>js</code>, and <code>images</code> into this directory.</li>
            <li>Place business logic in <span class="folder">../app</span>.</li>
            <li>Put templates in <span class="folder">../views</span>.</li>
            <li>Use Composer autoloading via <code>require ../vendor/autoload.php</code>.</li>
            <li>Follow modular folder separation (Controllers, Models, Views).</li>
        </ul>
        <p>✨ As you grow, this structure prepares you perfectly for full Laravel development.</p>
    </div>

    <div class="card">
        <h2>🎯 Final Tips</h2>
        <ul>
            <li>Don’t be afraid to experiment — this is your sandbox! 🧪</li>
            <li>Keep your files organized — future you will thank you 😊</li>
            <li>When stuck, search + ask mentors — collaboration is key 🤝</li>
            <li>Always commit your progress to Git early and often 📝</li>
        </ul>
    </div>

    <h2 style="text-align:center;">Happy coding, future software engineer! 💻✨</h2>

</div>

</body>
</html>
