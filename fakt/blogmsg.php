<?PHP
header('Content-type: text/html;charset=UTF-8');
?>
<HTML>
<HEAD>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
<TITLE>מערכת בלוגים - כתיבת הודעות</TITLE>
<STYLE>
.para {display: none;}
table {font-size:14px; font-family: arial, sans-serif}
</STYLE>
<SCRIPT language=JavaScript>

function ShowNewGroup(nr) {
  if (document.getElementById){
    current = document.getElementById(nr).style.display = 'block';
    document.getElementById(nr).style.display = current;
  }
  else if (document.all) {
    current = document.all[nr].style.display = 'block';
    document.all[nr].style.display = current;
  }
  else if (document.layers) {
    var i = parseInt(nr.substr(nr.length-1,1));
    var replacing = heights[i-1];
    shown[i] = true;
    document.layers[nr].visibility = 'show';
    var tempname = 'header' + i;
    document.layers[nr].top = document.layers[tempname].top + headerheight;

    for (j=(i+1);j<=max;j++) {
      name1 = 'header' + j;
      document.layers[name1].top += replacing;
      if (shown[j]) {
	name2 = 'number' + j;
	document.layers[name2].top += replacing;
      }
    }
  }
  else alert ('This link does not work in your browser.');
}

function HideNewGroup(nr) {
  if (document.getElementById){
    current = document.getElementById(nr).style.display = 'none';
    document.getElementById(nr).style.display = current;
  }
  else if (document.all) {
    current = document.all[nr].style.display = 'none';
    document.all[nr].style.display = current;
  }
  else if (document.layers) {
    var i = parseInt(nr.substr(nr.length-1,1));
    var replacing = heights[i-1];
    shown[i] = false;
    document.layers[nr].visibility = 'hide';
    var tempname = 'header' + i;
    document.layers[nr].top = document.layers[tempname].top + headerheight;

    for (j=(i+1);j<=max;j++) {
      name1 = 'header' + j;
      document.layers[name1].top += replacing;
      if (shown[j]) {
	name2 = 'number' + j;
	document.layers[name2].top += replacing;
      }
    }
  }
  else alert ('This link does not work in your browser.');
}

function ChangeGrp() {
  var val = document.request.group.value;

  if(val == "__NEW__") {
    ShowNewGroup('newgrp');
  }
  else {
    HideNewGroup('newgrp');
  }
}
</SCRIPT>
</HEAD>
<?PHP
include('blogs.cfg.php');
include('common.inc');

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

function GetBlogName($num) {
	$query = "SELECT blog_name FROM bloglist WHERE blognum='$num'";
	$result = mysql_query($query);
	if(!$result) {
    	echo mysql_error();
    	exit;
  	}
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	$title = $line['blog_name'];
	return $title;
}

function InformList($blognum, $str) {
	global $scriptsurl;

	$blog_name = GetBlogName($blognum);

	$query = "SELECT * FROM inform WHERE blognum='$blognum'";
	$result = mysql_query($query);
	if(!$result) {
    	echo mysql_error();
    	exit;
  	}
//	print "Inform list query: $query<BR>\n";
	while($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$email = $line['email'];
		$user = $line['name'];

		print "Mail to: $email<BR>\n";

		$body = "בלוג: ";
		$body .= $blog_name;
		$body .= "\r\n$str\r\n";
		$body .= "$scriptsurl/blogs.php?blog=$blognum\r\n";
		$body .= "\r\nלהסרה מרשימת התפוצה: \r\n";
		$body .= "$scriptsurl/blogmail.php?action=delete&blog=$blogname&email=$email\r\n";
		mail($email, "FAKT blogs system blog update", $body);
	}
}

function GetExt($filename) {
	$carr = explode(".", $filename);
	$n = count($carr) - 1;
	$ext = $carr[$n];
	return $ext;
}

function IsImg($ext) {
	$imgext = array('jpg', 'gif', 'bmp', 'png', 'tif');

	foreach($imgext as $val) {
		if(!strcasecmp($ext, $val))
			return 1;
	}
	return 0;
}

function DisplayImg($fname) {
	global $userfiles;
	global $userfilesdir;

	$s = getimagesize("$userfilesdir/$fname");
	$w = $s[0];
	$ow = $w + 35;
	$h = $s[1];
	$oh = $h + 35;
	if($w > 100) {
		$ar = $h/$w;
		$h = 200*$ar;
		$w = 200;
	}
	print "<A HREF=\"javascript:void();\" onclick=\"javascript:window.open(";
	print "'$userfiles/$fname', 'Picture', 'height=$oh, width=$ow scrollbars=yes resizable=yes')\">";
	   //      print "<A HREF=$userfilesurl/$fname target=_blank>";
	print "<IMG SRC=$userfiles/$fname width=$w height=$h border=0>";
	print "</A><BR><BR>\n";
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

function GetTitle($num) {
	$query = "SELECT title FROM blogs WHERE num='$num'";
	$result = mysql_query($query);
	if(!$result) {
    	echo mysql_error();
    	exit;
  	}
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	$title = $line['title'];
	return $title;

}

$action = $_GET['action'];
$blognum = $_GET['blog'];
$num = $_GET['num'];

if($action == 'comment') {
	$ancestor = $_GET['ancestor'];

	$blog_name = GetBlogName($blognum);
	$article_title = GetTitle($num);

	print "<P dir=RTL><H2 dir=RTL><A HREF=blogs.php?blog=$blognum>$blog_name</A></H2>\n";
	print "<CENTER><H1><A HREF=blogs.php?action=show&blog=$blognum&num=$num>$article_title</A></H1>\n";
	/* print contents of message we are responding to */
	if(!$ancestor) {	/* This is comment to main article so display article */
		$query = "SELECT contents,pic FROM blogs WHERE num='$num'";
	}
	else {
		$query = "SELECT comment,title FROM responses WHERE num='$ancestor'";
	}
	$result = mysql_query($query);
	if(!$result) {
    	echo mysql_error();
   		exit;
 		}
	$line = mysql_fetch_array($result, MYSQL_NUM);
	$contents = $line[0];
	$pic = $line[1];

	print "<TABLE border=0 width=80%  dir=RTL>\n";
	if($ancestor) {
		print "<TR><TD>$pic\n";	/* $pic in this case contains title */
		$pic = '';
	}
	print "<TR bgcolor=#F0F0F0><TD valign=top>\n";
	DisplayContents($contents);
	if(!empty($pic)) {
		$ext = GetExt($pic);
		if(IsImg($ext)) {
			print "<TD>\n";
			DisplayImg($pic);
		}
		else {
			print "<BR><BR><A HREF=\"$userfiles/$pic\">$ext ";
			print "מצורף קובץ: ";
			print "</A>\n";
		}
	}
	print "</TD></TR></TABLE><BR>\n";

	print "<FORM action=blogmsg.php?action=submit&blog=$blognum&num=$num&ancestor=$ancestor method=post>\n";
	print "<TABLE border=8 dir=RTL>\n";
	print "<TR><TD colspan=2 align=left>\n";
	print "<CENTER><H1>תגובה להודעה</H1></CENTER>\n";

	print "<TR>\n";
	print "<TD>$l10nstr[77]: \n";		/* Name */
	print "<TD><INPUT type=text name=name dir=RTL value=\"$name\">\n";

	print "<TR>\n";
	print "<TD>$l10nstr[84]: \n";	/* email */
	print "<TD><INPUT type=text name=email dir=LTR>\n";

	print "<TR>\n";
	print "<TD>כותרת: \n";
	print "<TD><INPUT type=text name=title size=45 dir=RTL>\n";
	print "<TR>\n";
	print "<TD>תוכן: \n";
	print "<TD><TEXTAREA cols=50 rows=8 name=comment dir=RTL></TEXTAREA>\n";
	print "<TR><TD colspan=2 align=center>\n";
	print "<INPUT type=submit value=שלח>\n";
	print "</TABLE>\n</FORM>\n";
	exit;
}

if($action == 'submit') {
	$num = (int)$_GET['num'];
	$ancestor = (int)$_GET['ancestor'];
	$blognum = (int)$_GET['blog'];

	$user = $_POST['name'];
	$email = $_POST['email'];
	if($user == $name)
		$showname = 1;		/* link to details page when displaying commet */
	else
		$showname = 0;
	$title = $_POST['title'];
	$comment = addslashes($_POST['comment']);

	$query = "INSERT INTO responses (blogmsgnum, blognum, ancestor, title, comment, name, email, showname, time) ";
	$query .= "VALUES ('$num', '$blognum', '$ancestor', '$title', '$comment', '$user', '$email', '$showname', NOW())";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	InformList($blognum, "תגובה חדשה");
	print "</CENTER>\n";
	print "<P dir=RTL>\n";
	print "הודעתך נשלחה";
	print "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"3; URL=blogs.php?action=show&blog=$blognum&num=$num\">\n";
	print "\n<BR><A HREF=blogs.php?action=show&blog=$blognum&num=$num>$l10nstr[226]</A>\n";		/* click here to return to blog */
	exit;
}

if($action == 'add') {
	$title = addslashes($_POST['title']);
	if(empty($title)) {
		ErrorReport("לא ניתן לשלוח הודעה ללא נושא");
		exit;
	}
	$contents = addslashes($_POST['contents']);

	$fname = $_FILES['pic']['name'];
	if($fname) {
		$size = $_FILES['pic']['size'];
		$tmpname = $_FILES['pic']['tmp_name'];
		$fname = urlencode($fname);
		move_uploaded_file($tmpname, "$userfilesdir/$fname");
		// print "We have a file: $fname tempname: $tmpname size: $size<BR>\n";
	}

	$query = "INSERT INTO blogs VALUES (";
	/* num, blognum, title, contents, specfile, writer, email, pswd, comments, showname, time */
	$query .= "NULL, '$blognum', '$title', '$contents', '$fname', NOW())";
	//print "$query<BR>\n";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}

	InformList($blognum, "הודעה חדשה");

	print "</CENTER>\n";
	print "<P dir=RTL>\n";
	print "$l10nstr[227]: \n"; 		/* your message: */
	print "$title<BR>\n";
	print "נשלחה";
	print "<BR>\n";
	print "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"3; URL=blogs.php?blog=$blognum\">\n";
	print "<BR><A HREF=blogs.php?blog=$blognum>$l10nstr[226]</A><BR>\n";
	exit;
}

if($action == 'update') {
	$num = (int)$_GET['num'];
	$blognum = (int)$_GET['blog'];

	$query = "SELECT name FROM bloglist WHERE blognum='$blognum'";	/* get blog user */
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	$user = $line['name'];

	if($user != $name) {
		ErrorReport("אינך רשאי לערוך מאמר זה");
		exit;
	}


	$fname = $_FILES['pic']['name'];
	if($fname) {
		$size = $_FILES['pic']['size'];
		$tmpname = $_FILES['pic']['tmp_name'];
		$fname = urlencode($fname);
		move_uploaded_file($tmpname, "$userfiles/$fname");
		// print "We have a file: $fname tempname: $tmpname size: $size<BR>\n";
	}

	$title = addslashes($_POST['title']);
	if(empty($title)) {
		ErrorReport("לא ניתן לכתוב מאמר ללא כותרת");
		exit;
	}
	$contents = addslashes($_POST['contents']);

	$query = "UPDATE blogs ";
	$query .= "SET ";
	$query .= "title='$title', ";
	$query .= "contents='$contents', ";
	if($fname)
		$query .= "specfile='$specfile', ";
	$query .= "time=NOW() ";
	$query .= "WHERE num='$num'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	print "</CENTER>\n";
	print "המאמר: ";
	print "'$title'<BR>\n";
	print "נכנס למערכת";
	print "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"3; URL=blogs.php?blog=$blognum\">\n";
	print "<BR><A HREF=blogs.php?blog=$blognum>$l10nstr[226]</A>";
	exit;
}

if($action == 'edit') {
	$num = (int)$_GET['num'];
	$blognum = (int)$_GET['blog'];

	$query = "SELECT name FROM bloglist WHERE blognum='$blognum'";	/* get blog user */
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	$user = $line['name'];

	$query = "SELECT * FROM blogs WHERE num=$num";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	$msg_title = $line['title'];
	$time = $line['time'];
	$contents = stripslashes($line['contents']);
	$pic = $line['pic'];
}

$blog_name = GetBlogName($blognum);
print "<BR><CENTER><H1>$blog_name</H1>\n";

/*
 | Print form of message
 */
if($action == 'edit') {
	if($user != $name) {
		ErrorReport("אינך רשאי לערוך בלוג זה");
		exit;
	}
	print "<FORM enctype=multipart/form-data action=blogmsg.php?action=update&blog=$blognum&num=$num method=post>\n";
}
else
	print "<FORM enctype=multipart/form-data action=blogmsg.php?action=add&blog=$blognum method=post>\n";
print "<TABLE border=8 dir=RTL>\n";
print "<TR><TD colspan=4>\n";
if($action == 'edit')
	print "<CENTER><H1>עריכת מאמר</H1></CENTER>\n";
else
	print "<CENTER><H1>מאמר חדש</H1></CENTER>\n";
print "<TR>\n";
print "<TD >כותרת: \n";
print "<TD colspan=3><INPUT size=50 type=text name=title value=\"$msg_title\">\n";

print "<TR>\n";
print "<TD>תוכן: \n";
print "<TD colspan=3><TEXTAREA cols=80 rows=20 name=contents>$contents</TEXTAREA>\n";

print "<TR>\n";
print "<TD>קובץ נוסף: \n";
print "<TD colspan=3><INPUT type=file name=pic size=50>\n";
print "<TR><TD colspan=4 align=center>\n";
print "<INPUT type=submit value=שלח>\n";
print "</TABLE>\n</FORM>";

?>
</BODY>
</HTML>
