<?php
// PHP code to generate and display the contact information page.

// The entire HTML structure is echoed using PHP for simple, immediate output.
echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ndlovu\'s Crèche Contact</title>
</head>
<body>
    <div>

        <!-- Title and Introduction -->
        <h1>We\'d Love to Hear From You!</h1>
        <p>
            Whether you have questions about enrollment, want to schedule a tour of Ndlovu\'s Crèche, or just need more information about our curriculum, our team is here to help. Use the contact information below or connect with us directly via phone or email.
        </p>

        <!-- Contact Details Section -->
        <h2>Detail Information</h2>

        <div>
            <!-- Detail Item: Phone -->
            <div>
                <div>Phone (Main): +27 (0)11 555 1234</div>

            </div>

            <!-- Detail Item: Email - CHANGED TO USE <p> FOR INLINE DISPLAY -->
            <p>
                Email (General): info@ndlovuscreche.co.za
            </p>

            <!-- Detail Item: Physical Address -->
            <div>
            <p>
                <div>Physical Address: 123 Mahube street, Mamelodi,0122</div>
                <p>
            </div>

            <!-- Detail Item: Office Hours -->
            
            <div>
            
                <div>Office Hours: Monday - Friday, 7:00 AM - 5:30 PM</div>
            </div>
        </div>
        
        <!-- NEW NAVIGATION BUTTONS -->
        <div style="margin-top: 30px; display: flex; gap: 10px;">
            <a href="about.php">
                <button>Back to About Page</button>
            </a>
        </div>

    </div>
</body>
</html>';
// Note: In a real-world scenario, you would typically use a templating engine or separate the PHP logic from the HTML to keep the code cleaner.
?>