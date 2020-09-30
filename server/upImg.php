<?php  
 //upload.php  
 $output = '';  
 if(is_array($_FILES))  
 {  
      foreach($_FILES['select_image']['name'] as $name => $value)  
      {  
           $file_name = explode(".", $_FILES['select_image']['name'][$name]);  
           $allowed_extension = array("jpg", "jpeg", "png", "gif");  
           if(in_array($file_name[1], $allowed_extension))  
           {  
                $new_name = rand() . '.'. $file_name[1];  
               
                $sourcePath = $_FILES["select_image"]["tmp_name"][$name];  
                $targetPath = "imgEmail/".$new_name;  
               if(move_uploaded_file($sourcePath, $targetPath)) {
                   
               }else {
                    echo "error";
               }
           }  
      }  
     
      
 }  
 ?>  