<?PHP
header('Content-type: text/html;charset=UTF-8');
?>
<HTML>
<?PHP
/*
 | FAKT blogs system copyright: Helicon technologies LTD. 2003
 | FAKT is distributed under the GNU public license
 |
 | This file is part of FAKT blogs system
 |
 | FAKT blogs system is a free software; you can redistribute it and/or modify
 | it under the terms of the GNU General Public License as published by
 | the Free Software Foundation;
 |
 | This software is distributed in the hope that it will be useful,
 | but WITHOUT ANY WARRANTY; without even the implied warranty of
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 | GNU General Public License for more details.
 |
 | You should have received a copy of the GNU General Public License
 | along with the software; if not, write to the Free Software
 | Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 |
 */
?>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<TITLE dir=RTL>FAKT - מערכת בלוגים</TITLE>
<STYLE>
table {font-size:14px; font-family: arial, sans-serif}
.para {display: none;}
</STYLE>
<SCRIPT language=JavaScript>
function blocking(nr, t) {

	if (document.getElementById)
	{
		current = (document.getElementById(nr).style.display == 'block') ? 'none' : 'block';
		document.getElementById(nr).style.display = current;
		document.getElementById(t).style.color = '#ff0000';
	}
	else if (document.all)
	{
		current = (document.all[nr].style.display == 'block') ? 'none' : 'block'
		document.all[nr].style.display = current;
		document.all[t].style.color = '#ff0000';$result = mysql_query($query);
	}
	else if (document.layers)
	{
		var i = parseInt(nr.substr(nr.length-1,1));
		var replacing = heights[i-1];
		if (shown[i])
		{
			shown[i] = false;
			replacing = -replacing;
			document.layers[nr].visibility = 'hide';
			document.layers[nr].top = safe;
		}
		else
		{
			shown[i] = true;
			document.layers[nr].visibility = 'show';
			var tempname = 'header' + i;
			document.layers[nr].top = document.layers[tempname].top + headerheight;
		}
		for (j=(i+1);j<=max;j++)
		{
			name1 = 'header' + j;
			document.layers[name1].top += replacing;
			if (shown[j])
			{
				name2 = 'number' + j;
				document.layers[name2].top += replacing;
			}
		}
	}
	else alert ('This link does not work in your browser.');
}

</SCRIPT>
</HEAD>
<?PHP
include('l10n.inc');
include('blogs.cfg.php');
include('glob.inc');
include('common.inc');

function GetTimeFromDateTime($timestr) {
	if(empty($timestr))
		return "";

	sscanf($timestr, "%d-%d-%d %d:%d:%d", &$year, &$month, &$day, &$hour, &$min, &$sec);
	if($min < 10)
		$min = "0$min";
	return "$hour:$min  $day/$month/$year";
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

function CheckMessages($blognum) {
	$query = "SELECT num FROM blogs WHERE blognum='$blognum'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	$num = mysql_num_rows($result);
	return $num;
}

function GetLastModTime($blognum) {
	$query = "SELECT MAX(time) FROM blogs WHERE blognum='$blognum'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	$line = mysql_fetch_array($result, MYSQL_NUM);
	return $line[0];
}

function GetLastCommentTime($blognum) {
	$query = "SELECT MAX(time) FROM responses WHERE blognum='$blognum'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	$line = mysql_fetch_array($result, MYSQL_NUM);
	return $line[0];
}

function GetBlogUser($blognum) {
	$query = "SELECT name FROM bloglist WHERE blognum='$blognum'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	$user = $line['name'];
	return $user;
}

function GetComments($reqnum) {
	$query = "SELECT num FROM responses WHERE blogmsgnum=$reqnum";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	$num = mysql_num_rows($result);
	return $num;
}

function PrintLinks($blognum) {
	$currgrp = "";
	
	print "<TABLE dir=RTL bgcolor=#FFFFFF width=100%>\n";
	print "<TR><TD dir=RTL align=center><H2>קישורים</H2>\n";
	print "<TR><TD dir=RTL>\n";
	
	$query = "SELECT grp,url,description FROM links WHERE blognum=$blognum ORDER BY GRP ASC";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	while($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$group = $line['grp'];
		$url = $line['url'];
		$desc = $line['description'];
		
		if($currgrp != $group) {
			print "<BR><H3>$group</H3>\n";
			$currgrp = $group;
		}
		print "<A HREF=$url>$desc</A><BR>\n";
	}
	print "</TABLE>\n";
}

/*
 | AddLinks:
 | Replace URL's in string with links
 */
function AddLinks($string) {
  $string = preg_replace("/(^|[^=\"\/])\b((\w+:\/\/|www\.)[^\s<]+)".
			 "((\W+|\b)([\s<]|$))/i", "$1<a href=\"$2\" target=_blank>$2</a>$4", $string);
  return preg_replace("/href=\"www/i", "href=\"http://www", $string);
  
  // " Dummy comment to reenable gedit syntax coloring...
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

/*
 | DispComments:
 | Recursive function to display comments for specific blog entry
 */
function DispComments($blogmsgnum, $ancestor, $level) {
	global $blognum;
	global $l10nstr;
	global $name, $user;

	$query = "SELECT * FROM responses WHERE blogmsgnum=$blogmsgnum AND ancestor=$ancestor ORDER BY time DESC";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	if(mysql_num_rows($result) == 0)
		return;	/* end of recursive call */

	if($level == 0)
			print "<TABLE width=100% height=$3px><TR><TD></TABLE>\n";	/* spacer between two threades */

	while($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$msgnum = $line['num'];
		$title = SpecialCodes($line['title']);
		$msguser = $line['name'];
		$email = $line['email'];
		$showname = $line['showname'];
		$comment = $line['comment'];
		$time = $line['time'];

		$i = $level * 4;

		print "<TABLE border=0 width=90% cellpadding=0 cellspacing=0 dir=RTL>\n";
		print "<TR>\n";
		print "<TD width=$i%>\n";	/* spacer before beginning of response */
		print "<TD>\n";	/* response title */
		print "<A id=t$msgnum HREF=javascript:(void) onclick=\"blocking('msg$msgnum', 't$msgnum')\">$title</A>\n";
		print "<TD width=20%>\n";		/* show name and email */
		if($showname) {
			print "<A HREF=$descscript?action=user&usrname=$msguser target=_blank>$msguser</A>\n";
		}
		else {
			if($email)
				print "<A HREF=mailto:$email>$msguser</A>\n";
			else
				print "$msguser\n";
		}

		print "<TD width=25%>\n";		/* show message time */
		sscanf($time, "%d-%d-%d %d:%d:%d", &$year, &$month, &$day, &$hour, &$min, &$sec);
		if($min < 10) {
			$min = "0$min";
		}
		$time = "$day-$month-$year &nbsp;&nbsp; $hour:$min";
		print "$time\n";
		/* Put message contents as DIV that will be displayed when clicking on message */
		print "<TR>\n";
		print "<TD width=$i%>\n";	/* spacer according to level */
		print "<TD bgcolor=#FFF8FF colspan=3>\n";
		print "<DIV class=para id=msg$msgnum>\n";
		DisplayContents($comment);

		/* show link to add response */
		print "<BR><A HREF=blogmsg.php?action=comment&blog=$blognum&num=$blogmsgnum&ancestor=$msgnum>";
		print "הגב להודעה";
		print "</A>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";

		if($user == $name) {
			print "<A HREF=blogs.php?action=delcomment&blog=$blognum&num=$blogmsgnum&msgnum=$msgnum>$l10nstr[25]</A>\n";		/* delete message */
		}
		print "<BR><BR>\n";
		print "</DIV>\n";
		print "</TABLE>\n";
		DispComments($blogmsgnum, $msgnum, $level+1);
	}
}

function RecursiveDelete($num) {

	$query = "SELECT num FROM responses WHERE ancestor='$num'";
	$result = mysql_query($query);
	if(mysql_num_rows($result) == 0)
		return;	/* end of recursive call */
	while($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$n = $line['num'];
		$query = "DELETE FROM responses WHERE num=$n";
		$newresult = mysql_query($query);
		RecursiveDelete($n);
	}
}

/*
 | PrintOrdDir:
 | Print Up or Down arrow if this is the sort field
 */
function PrintOrd($fld, $hebname) {
	global $sort;
	global $ord;

	if($sort == $fld) {
		if($ord == "ASC") {
			$neword = "DESC";
			$img = "<IMG SRC=up.png ALT=Ascending>";
		}
		else {
			$neword = "ASC";
			$img = "<IMG SRC=down.png ALT=Descending>";
		}
		$ord = $neword;
	}
	else
		$img = "";
	print "<A HREF=blogs.php?sort=$fld&ord=$ord>$hebname $img</A>\n";
}

/*
 | CreateTmpTbl:
 | Create temporary table used to sort blogs list using last modification time etc.
 */
function CreateTmpTbl() {

	$query = "DROP TABLE tmp";
	$result = mysql_query($query);

	$query = "CREATE TABLE tmp (";
	$query .= "blognum INTEGER UNSIGNED, ";
	$query .= "name VARCHAR(30), ";
	$query .= "blog_name VARCHAR(70), ";
	$query .= "grp VARCHAR(60), ";
	$query .= "articles INTEGER UNSIGNED, ";
	$query .= "lastmod DATETIME, ";
	$query .= "lastcomment DATETIME ";
	$query .= ")";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
}

/* Acutal code */
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
$sort = $_GET['sort'];
if(empty($sort))
	$sort = 'blog_name';
$ord = $_GET['ord'];
if(empty($ord))
	$ord = 'ASC';

if($blognum) {			/* we have a blog number so we extract page information from it */
	$query = "SELECT pageheader FROM bloglist WHERE blognum='$blognum'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	$pghead = $line['pageheader'];
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

/* process actions */
if($action == 'delcomment') {
	$msgnum = $_GET['msgnum'];
	$num = (int)$_GET['num'];

	$query = "DELETE FROM responses WHERE num=$msgnum";
	$result = mysql_query($query);
	RecursiveDelete($msgnum);
	print "ההודעה נמחקה, מיד תחזור לבלוג";
	print "\n<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=blogs.php?action=show&blog=$blognum&num=$num\">\n";
	exit;
}
if($action == 'show') {
	$user = GetBlogUser($blognum);
	print "<P dir=RTL>\n";
	print "הבלוג של: ";
	print "<A HREF=$descscript?action=user&usrname=$user target=_blank>$user</A>\n";
	$blog_name = GetBlogName($blognum);
	print "<P DIR=RTL><H2 dir=RTL><A HREF=blogs.php?blog=$blognum>$blog_name</A><H2>\n";

	$query = "SELECT * FROM blogs WHERE blognum=$blognum AND num='$num'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	$num = $line['num'];
	$title = $line['title'];
	$contents = $line['contents'];
	$time = $line['time'];
	$pic = $line['pic'];

	print "<CENTER><H1 dir=RTL>$title</H1>\n";
	print "</CENTER><P dir=RTL>\n";
	$timestr = GetTimeFromDateTime($time);
	print "$timestr<BR>\n";
	print "<P><BR>\n";

	print "<TABLE border=0 width=100% dir=RTL><TR bgcolor=#FFFFFF>\n";
	print "<TD valign=top>\n";
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
	print "<TR><TD>\n";
	if($user == $name) {
		print "<A HREF=blogmsg.php?action=edit&blog=$blognum&num=$num>";
		print "ערוך";
		print "</A>&nbsp;&nbsp;&nbsp;&nbsp;\n";
	}
	print "<A HREF=blogmsg.php?action=comment&blog=$blognum&num=$num>";
	print "הוסף תגובה";
	print "</A>&nbsp;&nbsp;&nbsp;&nbsp;\n";
	if($user == $name) {
		print "<A HREF=blogmsg.php?blog=$blognum>";
		print "הוסף כתבה לבלוג";
		print "</A>&nbsp;&nbsp;&nbsp;&nbsp;\n";
		print "<A HREF=blogs.php?action=delarticle&blog=$blognum&num=$num>";
		print "מחק כתבה";
		print "</A>\n";
	}
	print "</TABLE>\n";
	print "<CENTER>\n";
	print "<H1>תגובות</H1>\n";
	DispComments($num, 0, 0);
	exit;
}
if($action == 'delarticle') {
	$num = (int)$_GET['num'];
	$blognum = (int)$_GET['blog'];

	$query = "DELETE FROM blogs WHERE num='$num' AND blognum='$blognum'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	print "\n<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=blogs.php?blog=$blognum>\n"; //"
}
if($action == 'delblog') {
	$blognum = (int)$_GET['blog'];

	/* First delete from blogs list */
	$query = "DELETE FROM bloglist WHERE blognum='$blognum'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}

	/* Now delete all messages of this blog */
	$query = "DELETE FROM blogs WHERE blognum='$blognum'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}

	/* now delete from responses */
	$query = "DELETE FROM responses WHERE blognum='$blognum'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}

	/* Last, delete from mailing list table */
	$query = "DELETE FROM inform WHERE blognum='$blognum'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}

	/* After we are done, fall through to blog list so we have to make sure we don't have blog number */
	unset($blognum);
}

/* Done with action processing */
/* Now, we either display blog or blog list */
if(empty($blognum)) {	/* We do not have a blog so we display blog list */
	print "<P dir=RTL><A HREF=blogadmin.php>יצירת בלוג חדש</A>\n";
	print "<CENTER>\n";
	
	print "<H1>רשימת בלוגים</H1>\n";
	/* Print table headers */
	print "<TABLE border=1 dir=RTL><TR bgcolor=#E0E0E0>\n";
	if($name == 'admin')
		print "<TD>&nbsp;</TD>\n";		/* used for delete blog link */
	print "<TD>";
	PrintOrd('grp', 'קבוצה');
	print "<TD>";
	PrintOrd('blog_name', 'בלוג');
	print "<TD>";
	PrintOrd('name', 'שם');
	print "<TD>";
	PrintOrd('articles', 'מאמרים');
	print "<TD>";
	PrintOrd('lastmod', 'עדכון אחרון');
	print "<TD>";
	PrintOrd('lastcomment', 'תגובה אחרונה');
	print "</TR>\n";

	CreateTmpTbl();		/* Create temporary blogs table */

	$query = "SELECT blognum, name, blog_name, grp FROM bloglist";
	
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	while($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$num = $line['blognum'];
		$user = $line['name'];
		$blog_name = addslashes($line['blog_name']);
		$grp = $line['grp'];
		$n = CheckMessages($num);
		$lastmod = GetLastModTime($num);
		$lastcomment = GetLastCommentTime($num);

		$query = "INSERT INTO tmp VALUES ('$num', '$user', '$blog_name', '$grp', '$n', '$lastmod', '$lastcomment')";
		$tmpresult = mysql_query($query);
		if(!$tmpresult) {
			echo mysql_error();
			exit;
		}
	}

	$query = "SELECT * FROM tmp ORDER BY $sort $ord";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	while($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$num = $line['blognum'];
		$user = $line['name'];
		$blog_name = $line['blog_name'];
		$grp = $line['grp'];
		$articles = $line['articles'];
		$lastmod = GetTimeFromDateTime($line['lastmod']);
		$lastcomment = GetTimeFromDateTime($line['lastcomment']);

		print "<TR>\n";
		if($name == 'admin') {
			print "<TD><A HREF=blogs.php?blog=$num&action=delblog>מחק</A>\n";
		}
		print "<TD>$grp\n";
		print "<TD><A HREF=blogs.php?blog=$num>$blog_name</A>\n";
		print "<TD><A HREF=$descscript?action=user&usrname=$user target=_blank>$user</A> \n";
		print "<TD>$articles\n";
		print "<TD>$lastmod\n";
		print "<TD>$lastcomment\n";
		print "</TR>\n";
	}
	print "</TABLE>\n";
}
else {	/* Now the interesting part, we have a blog number... */
	$query = "SELECT name,blog_name,description FROM bloglist WHERE blognum='$blognum'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	$user = $line['name'];
	$blog_name = $line['blog_name'];
	$description = $line['description'];

	print "<CENTER>\n";
	print "<TABLE border=0 dir=RTL width=90%>\n";
	/* user name line */
	print "<TR><TD align=right >הבלוג של: ";
	print "<A HREF=$descscript?action=user&usrname=$user target=_blank>$user</A> \n";
	print "<TD align=left><A HREF=guestbook.php?blog=$blognum>ספר אורחים</A>\n";
	
	/* blog name line */
	print "<TR><TD colspan=2 align=right><CENTER><H1>$blog_name</H1></CENTER>\n";
	if($user == $name) {
		print "<A HREF=blogmsg.php?blog=$blognum>הוסף כתבה לבלוג</A>\n";
		print "&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;\n";
		print "<A HREF=blogadmin.php?blog=$blognum>";
		print "ערוך פרטי בלוג";
		print "</A>\n";
		print "&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;\n";
		print "<A HREF=linkedit.php?blog=$blognum>ערוך קישורים</A>\n";
	}
	print "<TR>";
	print "<TD align=center valign=top>\n";		/* blog messages */
	print "<TABLE border=1><TR bgcolor=#20A0FF>\n";
	print "<TD>כותרת\n";
	print "<TD>זמן פרסום\n";
	print "<TD>תגובות\n";
	print "</TR>\n";

	$query = "SELECT num,title,time FROM blogs WHERE blognum='$blognum' ORDER BY time DESC";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	while($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$n = $line['num'];
		$title = $line['title'];
		$t = $line['time'];

		print "<TR>\n";
		print "<TD><A HREF=blogs.php?action=show&blog=$blognum&num=$n>$title</A>\n";
		$tstr = GetTimeFromDateTime($t);
		print "<TD>$tstr\n";
		$c = GetComments($n);
		print "<TD>$c\n";
		print "</TR>\n";
	}
	print "</TABLE>\n";
	print "<TD bgcolor=#B0A0FF>\n";	/* blog description */
	DisplayContents($description);

	PrintLinks($blognum);
	
	print "<TR><TD colspan=2>\n";
	/* email inform list */
	print "<BR><P dir=RTL>\n";
	print "<FORM action=blogmail.php?action=add&blog=$blognum method=post>\n";
	print "<TABLE border=0>\n";
	print "<TR><TD colspan=2>\n";
	print "<H2>קבל עדכון במייל כאשר הבלוג מתעדכן</H2>\n";
	print "<TR><TD>\n";
	print "שם: ";
	print "<TD><INPUT type=text name=name size=20>\n";
	print "<TR><TD>\n";
	print "דואר אלקטרוני: ";
	print "<TD><INPUT type=text name=email size=20>\n";
	print "<TR><TD colspan=2 align=center>\n";
	print "<INPUT type=submit value=שלח>\n";
	print "</TABLE>\n";
	print "</FORM>\n";

	print "</TABLE>\n";
}
?>
<!-- Start of StatCounter Code -->
<script type="text/javascript" language="javascript">
var sc_project=256965; 
</script>

<script type="text/javascript" language="javascript" src="http://www.statcounter.com/counter/counter.js"></script><noscript><a href="http://www.statcounter.com" target="_blank"><img  src="http://c1.statcounter.com/counter.php?sc_project=256965&amp;amp;java=0" alt="free website counter" border="0"></a> </noscript>
<!-- End of StatCounter Code -->
<IMG SRC=webstat.php?page=blogsmain width=1 height=1>

</BODY>
</HTML>

