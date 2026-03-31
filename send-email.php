<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $yourEmail = "chanupadewmith5678@gmail.com"; // your email here

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $subject = trim($_POST["subject"]);
    $message = trim($_POST["message"]);

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        die("Please fill all fields.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email address.");
    }

    $safeName = htmlspecialchars($name);
    $safeEmail = htmlspecialchars($email);
    $safeSubject = htmlspecialchars($subject);
    $safeMessage = htmlspecialchars($message);

    $fullMessage  = "You received a new message from your website.\n\n";
    $fullMessage .= "Name: $safeName\n";
    $fullMessage .= "Email: $safeEmail\n";
    $fullMessage .= "Subject: $safeSubject\n\n";
    $fullMessage .= "Message:\n$safeMessage\n";

    $headers  = "From: Website Contact <yourgmail@gmail.com>\r\n";
    $headers .= "Reply-To: $safeEmail\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    $result = mail($yourEmail, "Website Contact: " . $safeSubject, $fullMessage, $headers);

    if ($result) {
        echo "<h2>Email request sent.</h2>";
        echo "<p>Please also check Spam/Junk folder.</p>";
        echo "<a href='contact.html'>Go Back</a>";
    } else {
        echo "<h2>Mail sending failed.</h2>";
        echo "<a href='contact.html'>Go Back</a>";
    }

} else {
    echo "Invalid request.";
}
?>