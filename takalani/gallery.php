<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'menu-bar.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Our Gallery - SubixStar Pre-School</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f9f9f9;
      margin: 0;
      padding: 0;
      color: #333;
    }

    main {
      text-align: center;
      padding: 20px;
    }

    h1 {
      font-size: 28px;
      margin-bottom: 20px;
    }

    h2 {
      font-size: 22px;
      margin-top: 30px;
      margin-bottom: 15px;
      color: #444;
    }

    .gallery {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 15px;
      margin-bottom: 20px;
    }

    .gallery img {
      width: 250px;
      height: 180px;
      object-fit: cover;
      border-radius: 8px;
    }

    footer {
      text-align: center;
      padding: 15px;
      background-color: #222;
      color: #fff;
      margin-top: 40px;
    }
  </style>
</head>
<body>

<main>
  <h1>Our Gallery</h1>

  <h2>🧒 Classroom Activities</h2>
  <div class="gallery">
    <img src="https://images.pexels.com/photos/8422203/pexels-photo-8422203.jpeg" alt="Story time in class">
    <img src="https://images.pexels.com/photos/8422208/pexels-photo-8422208.jpeg" alt="Art and craft time">
    <img src="https://images.pexels.com/photos/4144094/pexels-photo-4144094.jpeg" alt="Kids learning together">
  </div>

  <h2>🎨 Creative Corner</h2>
  <div class="gallery">
    <img src="https://images.pexels.com/photos/4144222/pexels-photo-4144222.jpeg" alt="Kids painting">
    <img src="https://images.pexels.com/photos/8422247/pexels-photo-8422247.jpeg" alt="Clay modeling">
    <img src="https://images.pexels.com/photos/4143793/pexels-photo-4143793.jpeg" alt="Coloring activities">
  </div>

  <h2>🎉 Events & Celebrations</h2>
  <div class="gallery">
    <img src="https://images.pexels.com/photos/1684072/pexels-photo-1684072.jpeg" alt="Birthday celebration">
    <img src="https://images.pexels.com/photos/1395907/pexels-photo-1395907.jpeg" alt="Cultural day">
    <img src="https://images.pexels.com/photos/1449791/pexels-photo-1449791.jpeg" alt="Fun fair">
  </div>

  <h2>🌳 Outdoor Play</h2>
  <div class="gallery">
    <img src="https://images.pexels.com/photos/2963011/pexels-photo-2963011.jpeg" alt="Kids playing">
    <img src="https://images.pexels.com/photos/3662835/pexels-photo-3662835.jpeg" alt="Sports day">
    <img src="https://images.pexels.com/photos/6811186/pexels-photo-6811186.jpeg" alt="Playground fun">
  </div>

  <h2>👩‍🏫 Our Teachers in Action</h2>
  <div class="gallery">
    <img src="https://images.pexels.com/photos/5212345/pexels-photo-5212345.jpeg" alt="Teacher helping student">
    <img src="https://images.pexels.com/photos/5212327/pexels-photo-5212327.jpeg" alt="Teacher leading a class">
    <img src="https://images.pexels.com/photos/5212341/pexels-photo-5212341.jpeg" alt="Friendly teacher">
  </div>

  <h2>🏫 Facilities</h2>
  <div class="gallery">
    <img src="https://images.pexels.com/photos/256395/pexels-photo-256395.jpeg" alt="Classroom view">
    <img src="https://images.pexels.com/photos/3184287/pexels-photo-3184287.jpeg" alt="Library">
    <img src="https://images.pexels.com/photos/3184292/pexels-photo-3184292.jpeg" alt="Play area">
  </div>
</main>

<footer>
  <p>© 2025 SubixStar Pre-School</p>
</footer>

</body>
</html>
