<?PHP
header('Content-type: text/html;charset=UTF-8');
?>
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
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<TITLE>מערכת בלוגים - רשימת תפוצה</TITLE>
</HEAD>
<BODY>
<?PHP
include('blogs.cfg.php');
include('glob.inc');
include('l10n.inc');
include('common.inc');

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

function GetBlogUser($num) {
	$query = "SELECT name FROM bloglist WHERE blognum='$num'";
	$result = mysql_query($query);
	if(!$result) {
    	echo mysql_error();
    	exit;
  	}
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	$user = $line['name'];
	return $user;
}

$link = mysql_connect($host, $user, $pswd) or die("Could not connect to host $host");
mysql_select_db($database) or die("Could not select database: $database");

$data = $_GET['data'];
if(empty($data))
     $data = $_COOKIE['data'];
$name = $_GET['name'];
if(empty($name))
     $name = $_COOKIE['name'];
$name = str_replace("\'", '', $name);	/* prevent SQL injection */

print "$pageheader";

$action = $_GET['action'];
$blog = $_GET['blog'];

if($action == 'add') {
	$name = $_POST['name'];
	$email = $_POST['email'];

	$query = "INSERT INTO inform VALUES ('$blog', '$name', '$email')";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	print "<CENTER><H1>\n";
	print "שמך נוסף לרשימת התפוצה";
	print "</H1>\n<META HTTP-EQUIV=\"Refresh\" CONTENT=\"3; URL=blogs.php?blog=$blog\">\n";
	print "\n<BR><A HREF=blogs.php?blog=$blog>$l10nstr[226]</A>\n";		/* click here to return to blog */
	exit;
}

if($action == 'list') {
	if($blog) {
		$blog_name = GetBlogName($blog);
		$user = GetBlogUser($blog);
		print "<CENTER><H1>רשימת מנויים לבלוג: ";
		print "$blog_name</H1>\n";
		print "<BR><BR><TABLE border=1>\n";
		print "<TR bgcolor=#E0E0E0>\n";
		if(($user == $name) || ($name == 'admin'))
			print "<TD>&nbsp;</TD>\n";	/* used for delete link if needed */
		print "<TD>שם</TD>\n";
		print "<TD>דואר אלקטרוני</TD>\n";
		print "</TR>\n";
		$query = "SELECT * FROM inform WHERE blognum='$blog'";
	}
	else {
		print "<CENTER><H1>רשימת מנויים בבלוגים</H1>\n";
		print "<BR><BR><TABLE border=1>\n";
		print "<TR bgcolor=#E0E0E0>\n";
		print "<TD>&nbsp;</TD>\n";	/* used for delete link if needed */
		print "<TD>שם בלוג</TD>\n";
		print "<TD>שם</TD>\n";
		print "<TD>דואר אלקטרוני</TD>\n";
		print "</TR>\n";
		$query = "SELECT * FROM inform";
	}

	$result = mysql_query($query);
	while($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$blognum = $line['blognum'];
		$user = $line['name'];
		$email = $line['email'];
		print "<TR>\n";
		if(empty($blog)) {
			$blog_name = GetBlogName($blognum);
			$user = GetBlogUser($blognum);
			if(($user == $name) || ($name == 'admin')) {
				print "<TD>";
				print "<A HREF=blogmail.php?action=delete&email=$email&blog=$blognum>מחק</A>";
				print "</TD>\n";
			}
			print "<TD>$blog_name</TD>\n";
		}
		else {
			if(($user == $name) || ($name == 'admin')) {
				print "<TD>";
				print "<A HREF=blogmail.php?action=delete&email=$email&blog=$blognum&url=blogmail.php>מחק</A>";
				print "</TD>\n";
			}
			else
				print "<TD>&nbsp;</TD>\n";
		}
		print "<TD>$user</TD>\n";
		print "<TD><A HREF=mailto:$email>$email</A></TD>\n";
		print "</TR>\n";
	}
	print "</TABLE>\n";
	print "</BODY>\n</HTML>\n";
	exit;
}

if($action == 'delete') {
	$email = $_GET['email'];
	$blognum = $_GET['blog'];

	print "<CENTER>\n";
	$blog_name = GetBlogName($blognum);

	$query = "DELETE FROM inform WHERE email='$email' AND blognum='$blognum'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	print "הכתובת: ";
	print "$email<BR>\n";
	print "נמחקה מרשימת התפוצה לבלוג: ";
	print "<A HREF=blogs.php?blog=$blognum>$blog_name</A><BR>";
	print "</BODY>\n</HTML>\n";
	exit;
}
