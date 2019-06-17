<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;


require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';


$config = parse_ini_file("EmailConfig.ini");
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = 2;                                       // Enable verbose debug output
    $mail->isSMTP();                                            // Set mailer to use SMTP
    $mail->Host       = $config["host"];  // Specify main and backup SMTP servers
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = $config["username"];                     // SMTP username
    $mail->Password   = $config["password"];                               // SMTP password
    $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
    $mail->Port       = 587;                                    // TCP port to connect to

    
	if(isset($_POST))
	{
	http_response_code(400);
	echo "Missing POST Parameters.";
	}else if(isset($_POST["receiver"])){
	http_response_code(400);
	echo "Missing receiver address.";
	}else if(isset($_POST["subject"])){
	http_response_code(400);
	echo "Missing email subject.";
	}else if(isset($_POST["body"])){
	http_response_code(400);
	echo "Missing email body.";
	}
	else{
    $mail->setFrom($config["username"], 'Bot Singh');
    $mail->addAddress($_POST["receiver"]);
    if(isset($_POST["replyTO"]))
	$mail->addReplyTo($_POST["replyTO"]);
	if(isset($_POST["cc"]))
    $mail->addCC($_POST["cc"]);
	if(isset($_POST["bcc"]))
    $mail->addBCC($_POST["bcc"]);

    // Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');        
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    


    $mail->isHTML(true);                                  
    $mail->Subject = $_POST["subject"];
    $mail->Body    = $_POST["body"];
    $mail->AltBody = $_POST["altBody"];

    $mail->send();
	http_response_code(200);
    echo 'Message has been sent';
	}
} catch (Exception $e) {
	http_response_code(400);
    echo "Message could not be sent.";
}