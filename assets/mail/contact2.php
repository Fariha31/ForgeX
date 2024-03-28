<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/phpmailer/src/Exception.php';
require '../../vendor/phpmailer/src/PHPMailer.php';
require '../../vendor/phpmailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process the POST request

    if (!$_POST) exit;

    function isEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    $fname = $_POST['first_name'];
    $lname = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone_number'];
    $homeland = $_POST['country'];
    $company_type = $_POST['choice_company'];
    $services = implode(", ", $_POST['services']);
    $message_body = $_POST['message'];
    $budget = isset($_POST['budget']) ? $_POST['budget'] : 'Not specified';
    $privacy_policy_accept = isset($_POST['privacy_policy_accept']) ? $_POST['privacy_policy_accept'] : '0';

    if (empty($fname)) {
        echo '<div class="alert alert-error">You must enter your first name.</div>';
        exit();
    } elseif (empty($lname)) {
        echo '<div class="alert alert-error">You must enter your last name.</div>';
        exit();
    } elseif (empty($email)) {
        echo '<div class="alert alert-error">You must enter your email address.</div>';
        exit();
    } elseif (!isEmail($email)) {
        echo '<div class="alert alert-error">You must enter a valid email address.</div>';
        exit();
    } elseif (empty($phone)) {
        echo '<div class="alert alert-error">Please fill in the phone number field!</div>';
        exit();
    } elseif (empty($homeland)) {
        echo '<div class="alert alert-error">Please fill in the country field!</div>';
        exit();
    } elseif (empty($company_type)) {
        echo '<div class="alert alert-error">Please select the type of your company!</div>';
        exit();
    } elseif (empty($services)) {
        echo '<div class="alert alert-error">Please select the services you need!</div>';
        exit();
    } elseif (empty($message_body)) {
        echo '<div class="alert alert-error">You must enter your message.</div>';
        exit();
    } elseif (!isset($privacy_policy_accept) || $privacy_policy_accept != '1') {
        echo '<div class="alert alert-error">You must agree to our terms and conditions!</div>';
        exit();
    }

    $mail = new PHPMailer(true);

            try {
                //Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // your smtp
                $mail->SMTPAuth = true;
                $mail->Username = 'helloforgex@gmail.com'; // your email
                $mail->Password = 'qciaabhuqdqyqbto'; // your pass
                $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        //Recipients
        $mail->setFrom($email, $fname . ' ' . $lname);
        // $mail->addAddress('wordpressriver@gmail.com');
        $mail->addAddress('helloforgex@gmail.com'); // your email address


        //Content
        $mail->isHTML(false);
        $mail->Subject = "Contact Form Submission from $fname $lname";
        $mail->Body = "You have received a contact form submission from $fname $lname. Below are the details:" . PHP_EOL . PHP_EOL;
        $mail->Body .= "First Name: $fname" . PHP_EOL;
        $mail->Body .= "Last Name: $lname" . PHP_EOL;
        $mail->Body .= "Email: $email" . PHP_EOL;
        $mail->Body .= "Phone Number: $phone" . PHP_EOL;
        $mail->Body .= "Country: $homeland" . PHP_EOL;
        $mail->Body .= "Company Type: $company_type" . PHP_EOL;
        $mail->Body .= "Services Needed: $services" . PHP_EOL;
        $mail->Body .= "Budget: $budget" . PHP_EOL . PHP_EOL;
        $mail->Body .= "Privacy_policy_accept: $privacy_policy_accept" . PHP_EOL . PHP_EOL;
        $mail->Body .= "Message: " . PHP_EOL . $message_body;

        $mail->send();
        echo '<div class="alert alert-success">';
        echo '<h3>Email Sent Successfully.</h3>';
        echo "<p>Thank you <strong>$fname</strong>, your message has been submitted to us.</p>";
        echo '</div>';
    } catch (Exception $e) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    }
} else {
    http_response_code(403);
    echo "There was a problem with your submission, please try again.";
}
?>
