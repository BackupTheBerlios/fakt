<?PHP
header('Content-type: text/html;charset=UTF-8');
?>
<HTML>
<HEAD>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
<TITLE>ספר אורחים</TITLE>
<STYLE>
.para {display: none;}
table {font-size:14px; font-family: arial, sans-serif}
</STYLE>
</HEAD>
<?PHP
include('blogs.cfg.php');
include('common.inc');


function GetTimeFromDateTime($timestr) {
	if(empty($timestr))
		return "";

	sscanf($timestr, "%d-%d-%d %d:%d:%d", &$year, &$month, &$day, &$hour, &$min, &$sec);
	if($min < 10)
		$min = "0$min";
	return "$hour:$min  $day/$month/$year";
}

function CheckPrivateMessages() {
	global $l10nstr;
	global $name;

	$query = "SELECT num FROM msg WHERE receiver='$name' AND flags=0";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	$num = mysql_num_rows($result);
	if($num == 0)
		print "$l10nstr[19]";			/* no new messages */
	else if($num == 1)
		print "$l10nstr[20]";			/* one new message */
	else {
		print "$num ";
		print "$l10nstr[21]";			/* new messages */
	}

}

/*
 | AddLinks:
 | Replace URL's in string with links
 */
function AddLinks($string) {
  $string = preg_replace("/(^|[^=\"\/])\b((\w+:\/\/|www\.)[^\s<]+)".
			 "((\W+|\b)([\s<]|$))/i", "$1<a href=\"$2\" target=_blank>$2</a>$4",
			 $string);
  return preg_replace("/href=\"www/i", "href=\"http://www", $string);
  //  $txt = preg_replace( "/(?<!<a href=\")((http|ftp)+(s)?:\/\/[^<>\s]+)/i", "<a href=\"\\0\">\\0</a>", $txt );
  //  "
}

/*
 | DisplayContents:
 | Show a string of section body, parsing dot commands
 | currently only one command is supported
 | .C that will start or stop code section
 */
function DisplayContents($contents) {
	$incode = 0;

	$contents = SpecialCodes($contents);

  	$a = explode('<BR>', $contents);
  	foreach($a as $val) {
		if($val[0] == '.') { /* this is a command */
    		if($val[1] == 'C') {
				if(!$incode) {
					print "<CENTER>\n";
	  				print "<TABLE width=90% border=0 cellspacing=1 cellpadding=0 bgcolor=000000>";
	  				print "<TR><TD>\n";
	  				print "<table width=100% cellspacing=0 cellpadding=0 bgcolor=#EEEEEE><tr><td dir=ltr>";
					$incode++;
				}
				else {
		  			print "</TABLE>\n";
	  				print "</TABLE>\n";
	  				print "</CENTER>\n";
	  				$incode--;
				}
			}
    	}
    	else {
      		$val = AddLinks($val);
      		print "$val<BR>\n";
    	}
  	}
  	if($incode) {
	    while($incode) {
      		print "</TABLE>\n";
      		print "</TABLE>\n";
      		print "</CENTER>\n";
      		$incode--;
    	}
  	}
}

/* Actual code */
$link = mysql_connect($host, $user, $pswd) or die("Could not connect to host $host");
mysql_select_db($database) or die("Could not select database: $database");

$data = $_GET['data'];
if(empty($data))
     $data = $_COOKIE['data'];
$name = $_GET['name'];
if(empty($name))
     $name = $_COOKIE['name'];
$name = str_replace("\'", '', $name);	/* prevent SQL injection */

$blognum = $_GET['blog'];
$action = $_GET['action'];

if($blognum) {			/* we have a blog number so we extract page information from it */
	$query = "SELECT pageheader,name FROM bloglist WHERE blognum='$blognum'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	$pghead = $line['pageheader'];
	$bloguser = $line['name'];
}

if($pghead) {
	print "$pghead\n";
}
else {
	print "<BODY>\n";
	print "$pageheader\n";
}

print "<CENTER><TABLE dir=RTL width=100% border=0 cellpadding=0 bgcolor=lightblue><TR><TD>\n";
if(!empty($name)) {
	ShowUserSex();
	print "<A HREF=$descscript?action=user&usrname=$name target=_blank>$name</A> \n";
	print "<TD>\n";
	print "<A HREF=$loginscript?url=blogs.php?blog=$blognum>התחבר כמשתמש אחר</A>\n";		/* connect as different user */
	print "<TD>\n";
	print "<A HREF=$privatemsg>";
	CheckPrivateMessages();
	print "<A>\n";
}
else {
	print "<A HREF=$loginscript?url=blogs.php?blognum=$blognum>התחברות למערכת</A>\n";			/* connect to system */
}
print "</TABLE></CENTER>\n";

print "<BR><BR>\n";

/* Action processing */
if($action == 'new') {
	$author = $_POST['author'];
	$email = $_POST['email'];
	$comment = $_POST['comment'];
	
	$query = "INSERT INTO guestbook (blognum, author, email, comment, time) ";
	$query .= "VALUES ('$blognum', '$author', '$email', '$comment', NOW())\n";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	print "</CENTER>\n";
	print "<P dir=RTL>\n";
	print "הודעתך נשלחה";
	print "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=guestbook.php?blog=$blognum\">\n";
	print "\n<BR><A HREF=guestbook.php?blog=$blognum>לחץ כאן לחזרה לספר האורחים</A>\n";
	exit;
}
if($action == 'del') {
	$num = $_GET['msg'];
	
	$query = "DELETE FROM guestbook WHERE num='$num'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	print "</CENTER>\n";
	print "<P dir=RTL>\n";
	print "ההודעה נמחקה";
	print "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=guestbook.php?blog=$blognum\">\n";
	print "\n<BR><A HREF=guestbook.php?blog=$blognum>לחץ כאן לחזרה לספר האורחים</A>\n";
	exit;
} 	

print "<TABLE dir=RTL width=100% border = 1 cellpadding=0><TR>\n";
print "<TD>\n";	/* form for writing new comments */
	
print "הבלוג של: ";
print "<A HREF=$descscript?action=user&usrname=$bloguser target=_blank>$bloguser</A><BR>\n";
print "לכתיבת הודעה פרטית לבעל הבלוג לחצו על השם.<BR><BR>\n";

	print "<FORM action=guestbook.php?blog=$blognum&action=new method=post>\n";
	print "<CENTER><TABLE border=8 dir=RTL>\n";
	
	print "<TR><TD>\n";
	print "שם:\n";
	print "<TD>\n";
	print "<INPUT type=text name=author value=$name>\n";
	
	print "<TR><TD>\n";	
	print "דואר אלקטרוני:\n";
	print "<TD>\n";
	print "<INPUT type=text name=email>\n";
	
	print "<TR><TD>\n";
	print "תוכן:\n";
	print "<TD>\n";
	print "<TEXTAREA name=comment cols=30 rows=10 dir=RTL></TEXTAREA>\n";
	
	print "<TR><TD colspan=2 align=center>\n";
	print "<INPUT type=submit value=שלח>\n";
	print "</TABLE>\n";
	print "</FORM>\n";
	
	print "</TD><TD>\n";	/* Left side contains other user's messages */
	$query = "SELECT * FROM guestbook WHERE blognum='$blognum' ORDER BY time DESC";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	print "<CETNER><TABLE width=100% dir=RTL border=1 cellpadding=2 bgcolor=#FEFEFE>\n";
	while($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$msgnum = $line['num'];	
		$author = $line['author'];
		$email = $line['email'];
		$time = $line['time'];
		$tstr = GetTimeFromDateTime($time);
		$comment = $line['comment'];
		
		print "<TR bgcolor=#F0F0F0><TD align=right>\n";
		if($email)
			print "<A HREF=mailto:$email>";
		print "$author";
		if($email)
			print "</A>\n";
		if($user == $name) {	/* we are the blog owner */
			print "&nbsp;&nbsp;&nbsp;\n";
			print "<A HREF=guestbook.php?blognum=$blognum&msg=$msgnum&action=del>מחק</A>\n";
		}
		print "<TD align=left>$tstr\n";
		print "<TR><TD colspan=2>\n";
		DisplayContents($comment);
		print "</TR>\n";
	}
	print "</TABLE>\n";	/* end of internal table */
print "</TABLE>\n";	/* end of external table */

?>
</BODY>
</HTML>
