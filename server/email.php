<?php
if(isset($_POST['btnSend'])) {
	$email = $_POST['txtEmail'] ?? '';
	$subject = $_POST['txtSubject'] ?? '';
	$content = $_POST['txtContent'] ?? '';
	
	$email = strip_tags($email);
	$subject = strip_tags($subject);
	
	// validate du lieu
	$checkEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
	if($checkEmail && !empty($subject) && !empty($content)){
		// moi thuc hien send email
		// goi ham send mail o day
		if(mySendEmail($email, $subject, $content)){
			header("Location: ../index.php?state=success");
		} else {
			header("Location: ../index.php?state=fail");
		}
	} else {
		header("Location: ../index.php?state=error");
	}
}

function mySendEmail($email, $subject, $content)
{
	$header = "From:usergg0253@gmail.com \r\n";
	//$header = "Cc:afgh@somedomain.com \r\n";
	$header .= "MIME-Version: 1.0\r\n";
	$header .= "Content-type: text/html\r\n";
	$send = mail($email, $subject, $content, $header);
	if($send){
		return true;
	}
	return false;
}