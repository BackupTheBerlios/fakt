<?
 function uploadNcheck($tmp_file, $filename, $max_size, $type, $file_type, $file_size,$with_pic)
 {
  $error_msg="";
     
   if ($file_size > $max_size) // הקובץ גדול מידי
      {header("location: ../php/index.php?error=101");exit;};
   
   /* if ($with_pic="picture")
      {if (!($file_type =="image/jpeg" OR $file_type=="image/gif")) // סוג הקובץ שגוי 
       {header('Location: ../php/index.php?error=102');};}; */
   
   $upload_to="../uploads/$type/$filename"; 
   
   if (!(move_uploaded_file($tmp_file,$upload_to)))
      {header("location: ../php/index.php?error=103");exit;};
 }
 ?>