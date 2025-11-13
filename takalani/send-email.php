<?php
function sendAdmissionStatusEmail($to, $childName, $status) {
    $subject = "Admission Status Update - SubixStar Pre-School";
    $message = "
Dear Parent,

Your child *$childName* has been *$status*.

Thank you for choosing SubixStar Pre-School.

Best regards,
SubixStar Team
";
    
    $headers = "From: noreply@subixstar.com\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Send email
    if (mail($to, $subject, $message, $headers)) {
        return true;
    } else {
        return false;
    }
}
?>
