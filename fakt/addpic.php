<?PHP
header('Content-type: text/html;charset=UTF-8');
?>
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?PHP
	include('l10n.inc');
	print "<TITLE>$l10nstr[107]</TITLE>\n";			/* add file to message */
?>
</HEAD>
<BODY>
<?PHP
include('config.inc.php');
include('glob.inc');
include('common.inc');

function CheckExt($ext) {
	global $l10nstr;
	
	if(($ext == "doc") || ($ext == "ppt") || ($ext == "xls")) {
		print "<CENTER>במערכת זו לא ניתן להעלות קבצים מסוגים אלו<BR>\n";
		print "קבצים אלו הם בדרך כלל קבצים של תוכנת MS-Office<BR>\n";
		print "לפרטים נוספים ";
		print "<A HREF=no-word.html target=_blank>לחץ כאן</A>\n";
		print "<BR><INPUT type=button value=\"$l10nstr[74]\" onclick=javascript:window.close();>";
		exit;
	}
}


$action = $_GET['action'];

$data = $_GET['data'];
if(empty($data))
	$data = $_COOKIE['data'];
$name = $_GET['name'];
if(empty($name))
	$name = $_COOKIE['name'];
$name = str_replace("\'", '', $name);	/* prevent SQL injection */
$table = str_replace("\'", '', $_GET['forum']);
if(empty($table)) {
	ErrorReport("$l10nstr[108]");		/* please do not hack... */
	exit;
}
$num = (int)$_GET['num'];

$link = mysql_connect($host, $user, $pswd) or die("Could not connect to host $host");
mysql_select_db($database) or die("Could not select database: $database");

/* a little security check.... check if lastonline field of login table is equal to data */
if(empty($name)) {	// can't login with no name
	ErrorReport("$l10nstr[109]");		/* you are not connected, can not add file */
	exit;
}
$query = "SELECT lastonline FROM login WHERE name='$name'";
$result = mysql_query($query);
if(!$result) {
	echo mysql_error();
	exit;
}
$line = mysql_fetch_array($result, MYSQL_ASSOC);
if($line['lastonline'] != $data) {
	ErrorReport("$l10nstr[110]");	/* you are not allowed to add file */
	exit;	/* user is not logged in so display login script */
}

if($action == 'picupdate') {
	/* first check that current user can realy add files */
	$query = "SELECT name FROM $table WHERE num='$num'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	$user = $line['name'];
	if($user != $name) {
		ErrorReport("$l10nstr[111]");		/* you are not allowed to add file to this message */
		exit;
	}
	$size = (int)$_FILES['imgfile']['size'];
	if($size > 0) {
		if($size > 250000) {
			ErrorReport("$l10nstr[112]");		/* file too big */
			exit;
		}
  		$tmpname = $_FILES['imgfile']['tmp_name'];
		$name = $_FILES['imgfile']['name'];
		$carr = explode(".", $name);
		$n = count($carr) - 1;
		$ext = $carr[$n];
		CheckExt($ext);
  		$filename = "${table}_$num.$ext";
  		move_uploaded_file($tmpname, "$userfilesdir/$filename");
		$query = "UPDATE $table SET picture='$filename' WHERE num='$num'";
		$result = mysql_query($query);
		if(!$result) {
			echo mysql_error();
			exit;
		}
		print "<CENTER><H1>$l10nstr[113]</H1>\n";		/* file has ben added to message */
		print "$l10nstr[114]";		/* press refressh to see the file */
		print "<BR><BR><INPUT type=button value=\"$l10nstr[74]\" onclick=javascript:window.close();>";
	/*	print "בעוד מספר שניות תחזור לפורום, אם הקובץ לא נראה, לחץ רענון";
		print "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"2; URL=$mainfile?forum=$table\">\n"; */
	}
	else {
		ErrorReport("$l10nstr[115]");		/* Too big file or invalid type */
		exit;
	}
	exit;
}

print "<CENTER>\n";
print "<FORM enctype=multipart/form-data action=addpic.php?action=picupdate&forum=$table&num=$num method=post>\n";
print "<TABLE border=8><TR><TD colspan=2 align=center><BIG><B>\n";
print "$l10nstr[107]";		/* add file to message */
print "<TR>\n";
print "<input type=hidden name=\"MAX_FILE_SIZE\" value=250000><TD>\n";
print "<input type=file name=imgfile size=40>\n";
print "<TR><TD colspan=2 align=center>\n";
print "<input type=\"submit\" value=שלח>\n";
print "</TABLE>\n</FORM>\n";
print "</CENTER>\n";
?>
</BODY>
</HTML>
