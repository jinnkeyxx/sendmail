<?php
require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';
	
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
	
$email = strip_tags($_POST['txtEmail']);
$subject = strip_tags($_POST['subject']);
$email = strip_tags($email);
$subject = strip_tags($subject);
$array_mail = [];
$file_array = [];
$time = 0;
$username = strip_tags($_POST['username']);
$password = strip_tags($_POST['password']);
$content = strip_tags($_POST['content']);
if(isset($_FILES['htmlContent']) && empty($_POST['content'])){
	$filename = $_FILES['htmlContent']['name'];
	$location = "../server/filemail/".$filename;
	$imageFileType = pathinfo($location,PATHINFO_EXTENSION);
	$valid_extensions = array("html");
	$uploadOk = 1;
	if(!in_array(strtolower($imageFileType),$valid_extensions) ) 
	{
		die(json_encode(array('status' => 1 , 'messages' => 'up load file lỗi' , 'mail' => $array_mail , 'time' => $time)));
	}
	
	else
	{
					/* Upload file */
		if(move_uploaded_file($_FILES['htmlContent']['tmp_name'],$location))
		{
			$check = 1;
			$file = fopen($location , 'r');
			$content =  fread($file, filesize($location));	
			
		}else
		{
			die( json_encode(array('status' => 1 , 'messages' => 'không thể up load file' , 'mail' => $array_mail , 'time' => $time)));
						
		}
					
	}
	
}
elseif(isset($_FILES['htmlContent']) && !empty($_POST['content'])) {
	$content = $_POST['content'];
}
else {
	$content = $_POST['content'];
	// die( json_encode(array('status' => 1 , 'messages' => 'không thể gửi 2 content' , 'mail' => $array_mail , 'time' => $time)));
}




if(isset($_FILES['select_image']))
{
	if(is_array($_FILES['select_image']))
	{
		foreach($_FILES['select_image']['name'] as $name => $value)  
		{  
			$file_name = explode(".", $_FILES['select_image']['name'][$name]);  
			$allowed_extension = array("jpg", "jpeg", "png", "gif" , "pdf" , "mp4" , "mkv" ,"Doc", "Docx", "Xls", "Xlsx", "ppt", "pptx" , "zip" , "rar" , "avi" , "wav");  
			if(in_array($file_name[1], $allowed_extension))  
			{  
				$new_name = rand() . '.'. $file_name[1];  
								
				$sourcePath = $_FILES["select_image"]["tmp_name"][$name];  
				$targetPath = "imgEmail/".$new_name;  
				if(move_uploaded_file($sourcePath, $targetPath))
				{
					array_push($file_array , $targetPath);
			

				}
				else
				{
				die (json_encode(array('status' => 1 , 'messages' => 'lỗi upload , file quá lớn' , 'mail' => $array_mail , 'time' => $time)));
				}
					
			}
			else
			{
				die(json_encode(array('status' => 1 , 'messages' => 'lỗi upload file , không đúng định dạng' , 'mail' => $array_mail , 'time' => $time)));
				
			}
		
		}
		
	}
	

}


if(!empty($email) && !isset($_FILES['files']['tmp_name']))
{
	
	$email = explode("\n" , $email);
	send_mail($email , $subject , $content );
}
			
		
			   
			
			 
		 	
			
elseif(isset($_FILES['files']['name']) && empty($email))
{
				
	$filename = $_FILES['files']['name'];
	$location = "../server/filemail/".$filename;
	$imageFileType = pathinfo($location,PATHINFO_EXTENSION);
	$valid_extensions = array("txt");
	$uploadOk = 1;
	if(!in_array(strtolower($imageFileType),$valid_extensions) ) 
	{
		die(json_encode(array('status' => 1 , 'messages' => 'up load file lỗi' , 'mail' => $array_mail , 'time' => $time)));
	}
	
	else
	{
					/* Upload file */
		if(move_uploaded_file($_FILES['files']['tmp_name'],$location))
		{
			$check = 1;
			$file = fopen($location , 'r');

			// chuyển đổi file sang mảng
			$read =  fread($file, filesize($location));
			$data = explode("\n" , $read);
			// die(print_r($data));
			send_mail($data , $subject , $content);
						
		}else
		{
			die( json_encode(array('status' => 1 , 'messages' => 'không thể up load file' , 'mail' => $array_mail , 'time' => $time)));
						
		}
					
	}
}
	
	
		
		// dung thu vien php mailer
function send_mail($email  , $subject , $content)
{
	global $file_array;
	global $username;
	global $password;
	$array_mail = [];
	$mail = new PHPMailer(true);
	try {
			                 
		$mail->isSMTP();                                            
		$mail->Host       = 'smtp.googlemail.com ';                  
		$mail->SMTPAuth   = true;                                  
		$mail->Username   = $username;     
		#your email                
		$mail->Password   = $password;                
		#pass your email            
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         
		$mail->Port       = 587;                                    
		$mail->setFrom($username, ''); 
		#TitTe Youremail
		
		foreach ($email as $key => $value)
		{
			$mail->addAddress($value); #setting email address send
			array_push($array_mail , $value ."\r");
			$time = count($email) * 100 / count($email) * 10;	
			// dinh kem file
			if(isset($_FILES['select_image']))
			{
				foreach($file_array as $img)
				{
					$mail->addAttachment($img);
				}
			}	
					
		}
			
				$mail->isHTML(true);     // Set email format to HTML
				$mail->Subject = $subject;
				$mail->Body    = $content;
		if($mail->send())
		{
			$status = 0 ;
			$messages =  "gửi mail thành công";

		} 
		else
		{
			$status = 1 ;
			$messages =  "gửi mail thất bại";
						
		}
			
		
	
	}
	catch (Exception $e)
	{
		$status = 1 ;
		$messages =  "{$mail->ErrorInfo}";
				
	}
	echo json_encode(array('status'=> $status , 'messages' => $messages , 'mail' => $array_mail , 'time' => $time));
		
}
		
			
	
