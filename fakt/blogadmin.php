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
<TITLE>מערכת בלוגים FAKT
- מערכת ניהול בלוגים
</TITLE>
<STYLE>
.para {display: none;}
table { font-size: 14px; font-family: arial, sans-serif}
body {margin:0; font-size: 12px, font-family: arial, sans-serif}
a:visited {color:blue}
a:link {color:navy; font-family:arial, sans-serif }
a:hover {color:red}
.text1 { font-size:10px; font-family: arial, sans-serif}
.text2 { font-size:11px; font-family: arial, sans-serif}
.text3 { font-size:14px; font-family: arial, sans-serif}
h1 {font-size: 24; font-weight:bold; font-family: arial, sans-serif; color: navy}
h2 {font-size: 18; font-weight:bold; font-family: arial, sans-serif; color: navy}
</STYLE>
<SCRIPT Language=JavaScript>

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
  var val = document.blogadmin.group.value;

  if(val == "__NEW__") {
    ShowNewGroup('newgrp');
  }
  else {
    HideNewGroup('newgrp');
  }
}
</SCRIPT>

</HEAD>
<BODY>
<?PHP
include('blogs.cfg.php');
include('glob.inc');
include('common.inc');

function PrintGroups($default) {
  $query = "SELECT grp FROM bloglist GROUP BY grp";
  $result = mysql_query($query);
  if(!$result) {
    echo mysql_error();
    exit;
  }
  print "<SELECT name=group onchange=ChangeGrp()>\n";
  print "<OPTION value=__NULL__ ";
  if(!$default || ($default == "__NULL__")) {
    print "SELECTED";
  }
  print ">";
  print "-- בחר קבוצה --";
  print "\n";
  while($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
    $grp = $line['grp'];
    print "<OPTION value=\"$grp\"";
    if($grp == $default) {
      print "SELECTED";
    }
    print ">$grp\n";
  }
  print "<OPTION value=\"__NEW__\">-- קבוצה חדשה --\n";
  print "</SELECT>\n";
}

function CheckPrivateMessages() {
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
$blogsexist = 0;
$responsesexist = 0;
$informexist = 0;
$linksexist = 0;
$guestbookexist = 0;
while($line = mysql_fetch_array($result, MYSQL_NUM)) {
	if($line[0] == 'login')
		$loginexist = 1;
	if($line[0] == 'bloglist')
		$mainlistexist = 1;
	if($line[0] == 'blogs')
		$blogsexist = 1;
	if($line[0] == 'responses')
		$responsesexist = 1;
	if($line[0] == 'inform')
		$informexist = 1;
	if($line[0] == 'links')
		$linksexist = 1;
	if($line[0] == 'guestbook')
		$guesbookexist = 1;
}

if(!$loginexist) {
	print "Table login does not exist...<BR>\n";
	$query = "CREATE TABLE login (";
	$query .= "name VARCHAR(30), ";
	$query .= "fullname VARCHAR(50), ";
	$query .= "email VARCHAR(40), ";
	$query .= "password VARCHAR(15), ";
	$query .= "lastonline TIMESTAMP, ";
	$query .= "pubemail VARCHAR(40), ";
	$query .= "web VARCHAR(60), ";
	$query .= "messangernum VARCHAR(15), ";
	$query .= "messangersoft VARCHAR(15), ";
	$query .= "birthdate DATE, ";
	$query .= "sex enum(\"female\", \"male\"), ";
	$query .= "martial VARCHAR(30), ";
	$query .= "occupation VARCHAR(30), ";
	$query .= "interest VARCHAR(60), ";
	$query .= "signature VARCHAR(255), ";
	$query .= "comments TEXT, ";
	$query .= "picture VARCHAR(50) ";
	$query .= ")";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	print "Table login created.<BR>\n";
}

if(!$mainlistexist) {
	print "Table bloglist does not exist...<BR>\n";
	$query = "CREATE TABLE bloglist (";
	$query .= "blognum INTEGER UNSIGNED AUTO_INCREMENT, PRIMARY KEY (blognum), ";
	$query .= "name VARCHAR(40), ";		/* blog owner name */
	$query .= "blog_name VARCHAR(140), ";
	$query .= "grp VARCHAR(90), ";
	$query .= "keywords VARCHAR(254), ";
	$query .= "description TEXT, ";
	$query .= "background VARCHAR(60), ";		/* background picture */
	$query .= "pageheader TEXT ";
	$query .= ")";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	print "Table bloglist created.<BR>\n";
}
if(!$blogsexist) {
	print "Table blogs does not exist...<BR>\n";
	$query = "CREATE TABLE blogs (";
	$query .= "num INTEGER UNSIGNED AUTO_INCREMENT, PRIMARY KEY (num), ";
	$query .= "blognum INTEGER UNSIGNED, ";
	$query .= "title VARCHAR(120), ";
	$query .= "contents TEXT, ";
	$query .= "pic VARCHAR(60), ";
	$query .= "time DATETIME";
	$query .= ")";

	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	print "Table blogs created.<BR>\n";
}

if(!$responsesexist) {
	print "Table responses does not exist...<BR>\n";
	$query = "CREATE TABLE responses (";
	$query .= "num INTEGER UNSIGNED AUTO_INCREMENT, PRIMARY KEY (num), ";
	$query .= "blogmsgnum INTEGER UNSIGNED, ";
	$query .= "blognum INTEGER UNSIGNED, ";
	$query .= "ancestor INTEGER UNSIGNED, ";
	$query .= "title VARCHAR(60), ";
	$query .= "comment TEXT, ";
	$query .= "name VARCHAR(60), ";
	$query .= "email VARCHAR(60), ";
	$query .= "showname TINYINT, ";
	$query .= "time DATETIME";
	$query .= ")";

	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	print "Table responses created.<BR>\n";
}
if(!$informexist) {
	print "Table inform does not exist...<BR>\n";
	$query = "CREATE TABLE inform (";
	$query .= "blognum INTEGER UNSIGNED, ";
	$query .= "name VARCHAR(60), ";
	$query .= "email VARCHAR(40) ";
	$query .= ")";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	print "Table inform created.<BR>\n";
}
if(!$linksexist) {
	print "Table links does not exist...<BR>\n";
	$query = "CREATE TABLE links (";
	$query .= "blognum INTEGER UNSIGNED, ";
	$query .= "grp VARCHAR(50), ";
	$query .= "url VARCHAR(128), ";
	$query .= "description VARCHAR(128) ";
	$query .= ")";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	print "Table links created.<BR>\n";
}
if(!$guestbookexist) {
	print "Table guestbook does not exist...<BR>\n";
	$query = "CREATE TABLE guestbook (";
	$query .= "num INTEGER UNSIGNED AUTO_INCREMENT, PRIMARY KEY (num), ";
	$query .= "blognum INTEGER UNSIGNED, ";
	$query .= "author VARCHAR(50), ";
	$query .= "email VARCHAR(60), ";
	$query .= "time DATETIME, ";
	$query .= "comment TEXT ";
	$query .= ")";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	print "Table guestbook created.<BR>\n";
}

/* All tables checked and created if needed, now starts the real work */
print "$pageheader";

/*
 | First check if we are connected to the system, this is a preliminary requirent since
 | only registered users can create a blog or edit an exising blog (if they have one)
 | BTW we have a limitation of one blog per registered user
 */
$data = $_GET['data'];
if(empty($data))
     $data = $_COOKIE['data'];
$name = $_GET['name'];
if(empty($name))
     $name = $_COOKIE['name'];
$name = str_replace("\'", '', $name);	/* prevent SQL injection */

$blognum = $_GET['blog'];
$action = $_GET['action'];

/* a little security check.... check if lastonline field of login table is equal to data */

$query = "SELECT lastonline FROM login WHERE name='$name'";
$result = mysql_query($query);
if(!$result) {
	echo mysql_error();
	exit;
}
$line = mysql_fetch_array($result, MYSQL_ASSOC);
if(empty($action)) {
	if($line['lastonline'] != $data) {
		print "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"4; URL=$loginscript?url=blogadmin.php?blog=$blognum\">\n";
		exit;	/* user is not logged in so display login script */
	}
}

print "<CENTER><TABLE dir=RTL width=80% border=0 cellpadding=0 bgcolor=lightblue><TR><TD>\n";
if(!empty($name)) {
	ShowUserSex();
	print "<A HREF=$descscript?action=user&usrname=$name target=_blank>$name</A> \n";
	print "<TD>\n";
	print "<A HREF=$loginscript?url=blogadmin.php?blog=$blognum&action=$action>$l10nstr[3]</A>\n";		/* connect as different user */
	print "<TD>\n";
	print "<A HREF=$privatemsg>";
	CheckPrivateMessages();
	print "<A>\n";
}
else {
	print "<A HREF=$loginscript?url=blogadmin.php?blognum=$blognum&action=$action>$l10nstr[27]</A>\n";			/* connect to system */
	exit;
}
print "</TABLE></CENTER>\n";
print "<P dir=RTL align=right>\n";
$result = mysql_query($query);
if(!$result) {
	echo mysql_error();
	exit;
}
$line = mysql_fetch_array($result, MYSQL_ASSOC);

if(empty($name)) {
	print "<CENTER><H1>לא ניתן ליצור בלוג מבלי להרשם למערכת</H1></CENTER>\n";
	exit;
}

if($action == 'new') {
	$blog_name = $_POST['blog_name'];
	$grp = $_POST['group'];
	if($grp == "__NEW__")
		$grp = $_POST['new_grp'];
	$keywords = $_POST['keywords'];
	$description = $_POST['description'];
	$pghead = $_POST['pageheader'];

	$query = "INSERT INTO bloglist (name, blog_name, grp, keywords, description, pageheader) ";
	$query .= "VALUES ('$name', '$blog_name', '$grp', '$keywords', '$description', '$pghead')";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}

	print "<P dir=RTL><CENTER><H1>הבלוג החדש נוצר</H1>\n";
	print "<A HREF=blogs.php>לחץ כאן לרשימת הבלוגים</A>\n";
	exit;
}
if($action == 'update') {
	$blog_name = $_POST['blog_name'];
	$grp = $_POST['group'];
	if($grp == "__NEW__")
		$grp = $_POST['new_grp'];
	$keywords = $_POST['keywords'];
	$description = $_POST['description'];
	$pghead = $_POST['pageheader'];

	$query = "UPDATE bloglist ";
	$query .= "SET blog_name='$blog_name', ";
	$query .= "keywords='$keywords', ";
	$query .=  "description='$description', ";
	$query .= "pageheader='$pghead' ";
	$query .= "WHERE blognum='$blognum'";

	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}

	print "<P dir=RTL><CENTER><H1>הבלוג עודכן</H1>\n";
	print "<A HREF=blogs.php>לחץ כאן לרשימת הבלוגים</A>\n";
	exit;
}

if(empty($blognum)) {
	/* We do not have a blog num so... we give our user the option to create one */
	print "<CENTER><H1>יצירת בלוג חדש</H1>\n";
	print "<BR>\n";
	print "<FORM name=blogadmin action=blogadmin.php?action=new method=post>\n";
}
else {
	/* We have a blog num so we edit it */
	$query = "SELECT * FROM bloglist WHERE blognum='$blognum'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	$blog_name = $line['blog_name'];
	$group = $line['grp'];
	$keywords = $line['keywords'];
	$description = $line['description'];
	$pghead = $line['pageheader'];

	print "<CENTER><H1>עדכון פרטי בלוג</H1>\n";
	print "<BR>\n";
	print "<FORM name=blogadmin action=blogadmin.php?action=update&blog=$blognum method=post>\n";

}
/* Show actual edit/create form */
print "<TABLE border=8 dir=RTL><TR>\n";
print "<TD>שם הבלוג: \n";
print "<TD><INPUT type=text name=blog_name size=40 value=\"$blog_name\">\n";

print "<TR>\n";
print "<TD>קבוצה: \n";
print "<TD>\n";
print "<TABLE border=0><TR><TD>\n";
PrintGroups($group);
print "<TD>\n";
print "<DIV class=para id=newgrp><INPUT type=text name=new_grp></DIV>\n";
print "</TABLE>\n";

print "<TR>\n";
print "<TD>מילות מפתח: \n";
print "<TD><INPUT type=text name=keywords size=60 value=\"$keywords\">\n";

print "<TR>\n";
print "<TD>תיאור: \n";
print "<TD><TEXTAREA name=description rows=5 cols=50>$description</TEXTAREA>\n";


print "<TR>\n";
print "<TD>קוד ראש עמוד: \n";
print "<TD><TEXTAREA name=pageheader rows=5 cols=50>$pghead</TEXTAREA>\n";

print "<TR>\n";
print "<TD colspan=2 align=center><INPUT type=submit value=עדכן>\n";

print "</TABLE>\n</FORM>\n";


?>
</BODY>
</HTML>
