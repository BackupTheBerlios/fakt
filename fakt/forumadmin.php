<?PHP
header('Content-type: text/html;charset=UTF-8');
?>
<HTML>
<HEAD>
<?PHP
/*
  | System administration script of FAKT Forums system
  | This script will show forum description or user details if action=user
  |
  | FAKT Forums system copyright: Helicon technologies LTD. 2003
  | The Esc forums system is distributed under the GNU public license
  |
  | This file is part of FAKT system.
  |
  | FAKT system is free software; you can redistribute it and/or modify
  | it under the terms of the GNU General Public License as published by
  | the Free Software Foundation;
  |
  | FAKT system is distributed in the hope that it will be useful,
  | but WITHOUT ANY WARRANTY; without even the implied warranty of
  | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  | GNU General Public License for more details.
  |
  | You should have received a copy of the GNU General Public License
  | along with the software; if not, write to the Free Software
  | Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
  |
  */
include('config.inc.php');
include('glob.inc');
include('l10n.inc');
include('common.inc');

print "<meta http-equiv=Content-Type content=\"text/html; charset=$charset\">\n";
print "<TITLE>Forums administration utility</TITLE>\n"; ?>
<?PHP

print "</HEAD>\n";
print "<BODY>\n";

print "<CENTER><H1>FAKT Forums system forums administration utility</H1></CENTER>\n";
$data = $_GET['data'];
$name = $_GET['name'];

$link = mysql_connect($host, $user, $pswd) or die("Could not connect to host $host");
mysql_select_db($database) or die("Could not select database: $database");
/*
 | First check that main tables, login and mainlist are present
 */
$query = "SHOW TABLES";
$result = mysql_query($query);
if(!$result) {
  echo mysql_error();
  exit;
}
$loginexist = 0;
$mainlistexist = 0;
$msgexist = 0;
$banexist = 0;
$articlesexist = 0;
$respexist = 0;
$usrtrackexist = 0;
print "Searching for main tables...<BR>\n";
while($line = mysql_fetch_array($result, MYSQL_NUM)) {
	if($line[0] == 'login')
		$loginexist = 1;
	if($line[0] == 'mainlist')
		$mainlistexist = 1;
	if($line[0] == 'msg')
		$msgexist = 1;
	if($line[0] == 'ban')
		$banexist = 1;
/*	if($line[0] == 'articles')
		$articlesexist = 1;
	if($line[0] == 'resp')
		$respexist = 1; */
	if($line[0] == 'usrtrack')
		$usrtrackexist = 1;
}
if(!$loginexist) {	/* we have to create login table */
	print "Table login does not exist...<BR>\n";
	$query = "CREATE TABLE login (";
	$query .= "name VARCHAR(40), ";
	$query .= "fullname VARCHAR(90), ";
	$query .= "email VARCHAR(50), ";
	$query .= "password VARCHAR(30), ";
	$query .= "lastonline TIMESTAMP, ";
	$query .= "pubemail VARCHAR(50), ";
	$query .= "web VARCHAR(80), ";
	$query .= "messangernum VARCHAR(15), ";
	$query .= "messangersoft VARCHAR(15), ";
	$query .= "birthdate DATE, ";
	$query .= "sex enum(\"female\", \"male\"), ";
	$query .= "martial VARCHAR(30), ";
	$query .= "occupation VARCHAR(30), ";
	$query .= "interest VARCHAR(120), ";
	$query .= "signature VARCHAR(255), ";
	$query .= "comments TEXT, ";
	$query .= "picture VARCHAR(70) ";
	$query .= ")";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	print "Table login created.<BR>\n";
}
if(!$mainlistexist) {
	print "Main forum list table does not exist...<BR>\n";
	$query = "CREATE TABLE mainlist (";
	$query .= "forum VARCHAR(60), ";
	$query .= "category VARCHAR(50), ";
	$query .= "forum_title VARCHAR(120), ";
	$query .= "manager VARCHAR(160), ";
	$query .= "tag_line VARCHAR(255), ";
	$query .= "description TEXT, ";
	$query .= "pghead TEXT, ";
	$query .= "pgbottom TEXT, ";
	$query .= "members TEXT ";
	$query .= ")";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	print "Table mainlist created.<BR>\n";
}
if(!$msgexist) {
	print "Private messages table does not exist...<BR>\n";
	$query = "CREATE TABLE msg (";
	$query .= "num INTEGER UNSIGNED AUTO_INCREMENT, PRIMARY KEY (num), ";
	$query .= "sender VARCHAR(40), ";
	$query .= "receiver VARCHAR(40), ";
	$query .= "flags TINYINT UNSIGNED, ";
	$query .= "time DATETIME, ";
	$query .= "subject VARCHAR(120), ";
	$query .= "contents TEXT ";
	$query .= ")";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	print "Table msg created.<BR>\n";
}
/* if(!$articlesexist) {
	print "Articles table does not exist...<BR>\n";
	$query = "CREATE TABLE articles (";
	$query .= "num INTEGER UNSIGNED AUTO_INCREMENT, PRIMARY KEY (num), ";
	$query .= "category VARCHAR(80), ";	// For use in later versions, not used in this version
	$query .= "subject VARCHAR(120), ";
	$query .= "user VARCHAR(40), ";
	$query .= "time DATETIME, ";
	$query .= "contents TEXT ";
	$query .= ")";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	print "Table articles created.<BR>\n";
}
if(!$respexist) {
	print "Articles response table does not exist...<BR>\n";
	 $query = "CREATE TABLE resp (";
  	$query .= "num INTEGER UNSIGNED AUTO_INCREMENT, PRIMARY KEY (num),";
	$query .= "article INTEGER UNSIGNED,";
  	$query .= "ancestor INTEGER UNSIGNED,";
  	$query .= "title VARCHAR(40),";
  	$query .= "date DATE, time TIME,";
  	$query .= "name VARCHAR(30),";
  	$query .= "contents TEXT,";
	$query .= "urldesc1 VARCHAR(40),";
	$query .= "url1 VARCHAR(140),";
	$query .= "urldesc2 VARCHAR(40),";
	$query .= "url2 VARCHAR(140),";
	$query .= "urldesc3 VARCHAR(40),";
	$query .= "url3 VARCHAR(1400),";
	$query .= "picture VARCHAR(60),";
	$query .= "flags TINYINT UNSIGNED";
	$query .=")";
  	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	print "Articles response table (resp) created.<BR>\n";
} */
if(!$banexist) {
	print "Ban table does not exist...<BR>\n";
	$query = "CREATE TABLE ban (";
	$query .= "forum VARCHAR(60), ";
	$query .= "user VARCHAR(40), ";
	$query .= "reason TEXT, ";
	$query .= "comment TEXT)";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	print "Ban table created.<BR>\n";
}
if(!$usrtrackexist) {
	print "User tracking table does not exist...<BR>\n";
	$query = "CREATE TABLE usrtrack (";
	$query .= "user VARCHAR(40), ";
	$query .= "forum VARCHAR(60), ";
	$query .= "last TIMESTAMP)";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	print "User tracking table (usrtrack) created.<BR>\n";
}
/* a little security check.... check if lastonline field of login table is equal to data */
$query = "SELECT lastonline FROM login WHERE name='$name'";
$result = mysql_query($query);
if(!$result) {
  echo mysql_error();
  exit;
}
$line = mysql_fetch_array($result, MYSQL_ASSOC);
$lastonline = $line['lastonline'];
if(($data == 0) || empty($name)) {
	print "<BR><BR><BIG>You must first login as general forums administrator<BR>\n";
	print "Click <A HREF=$loginscript?url=$adminscript>here</A> to login<BR>\n";
	exit;
}
else if($lastonline != $data) {
	//print "data: $data, lastonline: $lastonline<BR>\n";
	print "<BIG>User login error, please try loging in again<BR>\n";
	print "Click <A HREF=$loginscript?url=$adminscript>here</A> to login<BR>\n";
	exit;
}

$action = $_GET['action'];

if($action == 'add') {
	$forumname = $_POST['forum'];
	$category = $_POST['category'];
	$forum_title = $_POST['forum_title'];
	$manager = $_POST['manager'];
	$desc = $_POST['description'];
	$members = $_POST['members'];
	$query = "INSERT INTO mainlist (forum, category, forum_title, manager, description, members) VALUES ('$forumname', '$category', '$forum_title', '$manager', '$description', '$members')";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
    		exit;
  	}
  	$query = "CREATE TABLE $forumname (";
  	$query .= "num INTEGER UNSIGNED AUTO_INCREMENT, PRIMARY KEY (num),";
  	$query .= "ancestor INTEGER UNSIGNED,";
  	$query .= "title VARCHAR(80),";
  	$query .= "date DATE, time TIME,";
	$query .= "lastmod DATETIME, ";
  	$query .= "name VARCHAR(40),";
  	$query .= "contents TEXT,";
	$query .= "urldesc1 VARCHAR(80),";
	$query .= "url1 VARCHAR(140),";
	$query .= "urldesc2 VARCHAR(80),";
	$query .= "url2 VARCHAR(140),";
	$query .= "urldesc3 VARCHAR(80),";
	$query .= "url3 VARCHAR(140),";
	$query .= "picture VARCHAR(60), ";
	$query .= "flags TINYINT UNSIGNED";
	$query .=")";
  	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	print "<H1>Forum has been added</H1>\n";
}

if($action == 'edit') {
	$forum = $_GET['forum'];
	$query = "SELECT category,forum_title,manager,description,members FROM mainlist WHERE forum='$forum'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	list($category, $forum_title, $manager, $description, $members) = mysql_fetch_array($result, MYSQL_NUM);
	print "<CENTER>\n";
	print "<H1>Edit forum details forum: $forum</H1>\n";
	print "<TABLE border=5>\n";
	print "<FORM action=$adminscript?action=update&forum=$forum&name=$name&data=$data method=post>\n";
	print "<TR><TD>\n";
	print "Category:\n";
	print "<TD><INPUT type=text name=category value=\"$category\" size=30>\n";
	print "<TR><TD>\n";
	print "Forum title: ";
	print "<TD><INPUT type=text name=forum_title value=\"$forum_title\" size=40>\n";
	print "<TR><TD>\n";
	print "Managers: \n";
	print "<TD><INPUT type=text name=manager value=\"$manager\" size=40>\n";
	print "<TR><TD>\n";
	print "Description: \n";
	print "<TD><TEXTAREA name=description cols=30 rows=5>$description</TEXTAREA>\n";
	print "<TR><TD colspan=2 align=center><INPUT type=submit value=Submit>\n";
	print "<TR><TD>Members:\n";
	print "<TD><TEXTAREA name=members cols=30 rows=5>$members</TEXTAREA><BR>\n";
	print "comma seperated list of members, spaces ignored<BR>\n";
	print "Empty - all are members and forum shown in forums list<BR>\n";
	print "* - all are members but forums is excluded from forums list<BR>\n";
	print "</FORM>\n";
	print "</TABLE>\n";
	print "<BR>\n";
	print "<A HREF=$adminscript?action=delete&forum=$forum&name=$name&data=$data>Delete forum</A>\n";
	exit;
}

if($action == 'delete') {
	$forum = $_GET['forum'];
	$query = "DELETE FROM mainlist WHERE forum='$forum'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	$query = "DROP TABLE $forum";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
}

if($action == 'update') {
	$forum = $_GET['forum'];
	$category = $_POST['category'];
	$forum_title = $_POST['forum_title'];
	$manager = $_POST['manager'];
	$description = $_POST['description'];

	$query = "UPDATE mainlist SET category='$category', forum_title='$forum_title', manager='$manager',description='$description' WHERE forum='$forum'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
}

$query = "SELECT forum, category, forum_title FROM mainlist";
$result = mysql_query($query);
if(!$result) {
  echo mysql_error();
  exit;
}
$i = 1;
print "<CENTER><H3>Existing forums (select forum to edit details or delete)</H3>\n";
print "<TABLE border=1><TR bgcolor=lightblue>\n";
print "<TD>Num<TD>Category<TD>Forum<TD>Title</TR>\n";

while($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
  print "<TR>\n";
  $forum = $line['forum'];
  $category = $line['category'];
  $forum_title = $line['forum_title'];
  print "<TD>$i<TD>$category<TD><A HREF=$adminscript?action=edit&forum=$forum&name=$name&data=$data>$forum</A><TD>$forum_title</TR>\n";
  $i++;
}
print "</TABLE><BR>\n";


?>

<H2>Add forum</H2>

<TABLE border=5>
<?PHP print "<FORM action=$adminscript?action=add&name=$name&data=$data method=post>\n"; ?>
<TR>
<TD>Table name:
<TD><INPUT type=text name=forum>
<TR>
<TD>Category:
<TD><INPUT type=text name=category>
<TR>
<TD>Forum title:
<TD><INPUT type=text name=forum_title size=40>
<TR>
<TD>Managers:
<TD><INPUT type=text name=manager size=40>
<TR>
<TD>Description:
<TD><TEXTAREA name=description cols=30 rows=5></TEXTAREA>
<TR><TD colspan=2 align=center>
<INPUT type=submit value=Submit>
</TABLE>

</BODY>
</HTML>
