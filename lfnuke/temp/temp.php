<?$a=1;$b=3;$c=6;$d=7; 
$e = round(($a+$b+$c+$d)/4); 
echo $e; 
?>
<form enctype="multipart/form-data" action="_URL_" method="post">
 <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
 Send this file: <input name="userfile" type="file" />
 <input type="submit" value="Send File" />
</form>
