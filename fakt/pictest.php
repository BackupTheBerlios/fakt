<HTML>
<HEAD>
<TITLE>???? ???? ??????</TITLE>
</HEAD>
<BODY>

<?PHP
$action = $_GET['action'];
if($action == 'picupdate') {
	//$user = $_GET['name'];

	$size = (int)$_FILES['imgfile']['size'];
	if($size > 0) {
  		$tmpname = $_FILES['imgfile']['tmp_name'];
		$name = $_FILES['imgfile']['name'];
  		$name = urlencode($name);
  		//move_uploaded_file($tmpname, "$name");
		$picture = $name;
	}
	print "size: $size<BR>\n";
	print "name: $name<BR>\n";
	exit;
}

print "<FORM enctype=multipart/form-data action=addpic.php?action=picupdate method=post>\n";
print "<TABLE border=8><TR><TD colspan=2 align=center><BIG><B>\n";
print "???? ???? ??????";
print "<input type=hidden name=\"MAX_FILE_SIZE\" value=100240><TD>\n";
print "<input type=file name=imgfile size=60>\n";
print "<TR><TD colspan=2 align=center>\n";
print "<input type=\"submit\" value=???>\n";
print "</TABLE>\n</FORM>\n";
?>
</BODY>
</HTML>
