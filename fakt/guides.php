<HTML>
<HEAD>
<?PHP
/*
  | Main file of FAKT guides system
  |
  | YAFS copyright: Helicon technologies LTD. 2003
  | YAFS is distributed under the GNU public license
  |
  | This file is part of YAFS (Yet Another Forums System).
  |
  | YAFS is free software; you can redistribute it and/or modify
  | it under the terms of the GNU General Public License as published by
  | the Free Software Foundation;
  |
  | YAFS is distributed in the hope that it will be useful,
  | but WITHOUT ANY WARRANTY; without even the implied warranty of
  | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  | GNU General Public License for more details.
  |
  | You should have received a copy of the GNU General Public License
  | along with the software; if not, write to the Free Software
  | Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
  |
  */
include('config.inc');
include('glob.inc');
include('l10n.inc');
include('common.inc');

print "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=$charset\">";
print "<TITLE>$l10nstr[148]</TITLE>\n";

?>
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

</HEAD>
<BODY>
<TABLE bgcolor=#5272A4 width=100% cellpadding=0 cellspacing=0><TR><TD><IMG SRC=fakt.jpg><BR>
<TD dir=RTL valign=center><FONT color=white>
<A HREF=guides.php><IMG src=doc_open.gif border=0><FONT color=white><BIG>
<?PHP print "$l10nstr[149]"; 	/* guides list */  ?>
</A>
</TABLE>

<?PHP
$data = $_GET['data'];
if(empty($data))
     $data = $_COOKIE['data'];
$name = $_GET['name'];
if(empty($name))
     $name = $_COOKIE['name'];
$name = str_replace("\'", '', $name);	/* prevent SQL injection */
$data = str_replace("\'", '', $data);
$users = array("");  /* this global array will be filled by GetPermissions */

/*
 | GetPermissions
 | Read file guides.allowed and fill global $users array
 */
function GetPermissions() {
  global $home;
  global $users;

  $users = file("$home/guides.allow");
}

/*
 | Check if name is allowed to access the system
 | return 2 if user has administration capabilities (first user on the list
 | or admin user) return 1 if user is allowed to access.
 | return 0 if user is not allowed to access the system
 */
function IsAllowed($name) {
  global $level;

  if($level == 2)
    return 2;
  if($users[0] == "")
    return 2;  /* empty users array means everyone is allowed to access */
  if($name == 'admin')
    return 2;
  if(!strcmp("$name\n", $users[0]))
    return 2;
  if(search_array("$name\n", $users))
    return 1;
  return 0;
}

function EditUserList() {
  global $users;

  print "<CENTER><H1>$l10nstr[150]</H1>\n";		/* allowed users edit */
  print "<BR><H2>$l10nstr[151]</H2>\n";		/* Add new user */
  print "<FORM action=guides.php?action=adduser method=post>\n";
  print "<TABLE dir=RTL border=8><TR>\n";
  print "<TD>$l10nstr[122]: \n";
  print "<TD><INPUT type=text name=new_user>\n";
  print "<TR><TD colspan=2 align=center>\n";
  print "<INPUT type=submit value=$l10nstr[152]>\n";
  print "</TABLE></FORM>\n";
  print "<BR>\n";

  if($users[0] == "")
    return;

  print "<TABLE border=2 dir=RTL>\n";
  print "<TR><TD colspan=2>";
  print "<BIG><B><FONT color=navy>$l10nstr[153]\n";
  foreach($users as $val) {
    print "<TR><TD>$val";
    $val = trim($val);
	print "<TD><A href=guides.php?action=deluser&user=$val>$l10nstr[154]</A>\n";	/* delete */
  }
  print "</TABLE>\n";
}

function AddUser($user) {
  global $users;
  global $home;

  if(array_search("$user\n", $users)) {
    print "<CENTER><H1>$l10nstr[155]</H1>\n";		/* this name already exists */
    return;
  }
  $fd = fopen("$home/guides.allow", "w");
  foreach($users as $val) {
    fputs($fd, "$val");
  }
  fwrite($fd, "$user\n");
  fclose($fd);
}

function DelUser($user) {
  global $home;
  global $users;

  $fd = fopen("$home/guides.allow", "w");
  foreach($users as $val) {
    $val = trim($val);
    if($user != $val) {
      fwrite($fd, "$val\n");
    }
  }
  fclose($fd);
}

function FormatDate($mysqldate) {
  list($yy, $mm, $dd) = explode('-', $mysqldate);
  return "$dd/$mm/$yy";
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

if(!empty($base)) {
	print "<BASE HREF=$base>\n";
}

$link = mysql_connect($host, $user, $pswd) or die("Could not connect to host $host");
mysql_select_db($database) or die("Could not select database: $database");

function CheckAllowed($name, $members) {
  if($name == 'admin')
    return 1;
  if(empty($members))
    return 1;
  $memberarr = explode(',', $members);
  foreach($memberarr as $val) {
    $val = trim($val);
    if($val == '*')
      return 1;
    if($val == $name)
      return 1;
  }
  return 0;
}

/* a little security check.... check if lastonline field of login table is equal to data */
$query = "SELECT lastonline, email, fullname FROM login WHERE name='$name'";
$result = mysql_query($query);
if(!$result) {
  echo mysql_error();
  exit;
}
$line = mysql_fetch_array($result, MYSQL_ASSOC);
$lastonline = $line['lastonline'];
if($lastonline != $data) {
  unset($name);	/* ignore name if lastonline is not right */
}
else {
  $email = $line['email'];
  $fullname = $line['fullname'];
}


$query = "SELECT * FROM titles ORDER BY grp ASC, name ASC";
$result = mysql_query($query);
if(!$result) {
  echo mysql_error();
  exit;
}

print "<TABLE width=100% dir=RTL cellspacing=0 cellpadding=1 border=1><TR>\n";
print "<TD width=30px><IMG SRC=blockdevice.png>\n";
print "<TD>\n";
if(!empty($name)) {
  ShowUserSex();
  print "<A HREF=$descscript?action=user&usrname=$name target=_blank>$name</A>\n";
}
print "<TD width=27px><IMG SRC=identity.png>\n";
print "<TD><A HREF=$loginscript?url=guides.php>$l10nstr[3]</A><BR>\n";		/* connect as another user */
if(!empty($name)) {
  print "<TD width=27px><IMG SRC=document.png>\n";
  print "<TD><A HREF=editguide.php?action=new>$l10nstr[156]</A><BR>\n";	/* create new guide */
}
print "</TABLE>\n";

GetPermissions();
if(($level = IsAllowed($name)) == 0) {
  print "<CENTER><H1>$l10nstr[157]</H1>\n";		/* you are not allowed to access */
  exit;
}
$action = $_GET['action'];
if($action == 'adduser') {
  $user = $_POST['new_user'];
  $user = str_replace("\'", '', $user);
  AddUser($user);
  print "<CENTER><H1 dir=$langdir>$l10nstr[118]: ";		/* user */
  print "$user ";
  print "<H1>$l10nstr[158]</H1>\n";			/* added to system */
  print "<A HREF=guides.php>$l10nstr[158]</A>\n";
  exit;
}
if($action == 'deluser') {
  $user = $_GET['user'];
  $user = str_replace("\'", '', $user);
  DelUser($user);
  print "<CENTER><H1 dir=RTL>$l10nstr[118]: ";		/* user */
  print "$user ";
  print "$l10nstr[159]</H1>\n";				/* deleted from system */

  print "<A HREF=guides.php>$l10nstr[158]</A>\n";
  exit;
}
if($action == 'users') {
  EditUserList();
  exit;
}
if($level == 2) {
  print "<P dir=RTL>\n";
  print "<A HREF=guides.php?action=users>";
  print "$l10nstr[160]";
  print "</A>\n";
}

print "<CENTER><H1>$l10nstr[161]</H1>\n";		/* choose guide */
print "<P dir=RTL>\n";
print "<TABLE border=8 dir=RTL>\n<TR>";
/* table titles */
print "<TD>&nbsp;\n";
print "<TD><B>$l10nstr[77]\n";		/* name */
print "<TD><B>$l10nstr[162]\n";
print "<TD><B>$l10nstr[163]\n";
print "<TD><B>$l10nstr[164]\n";
print "<TD>&nbsp;\n";

$lastgrp = "";
while($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
  $num = $line['num'];
  $grp = $line['grp'];
  $guidename = $line['name'];
  $publish = $line['publish'];
  $editors = $line['editors'];
  $creation = $line['creation'];
  $modification = $line['modification'];

  $creation = FormatDate($creation);
  $modification = FormatDate($modification);

  if($grp != $lastgrp) {
    $lastgrp = $grp;
    print "<TR><TD colspan=6><B>$grp\n";
    print "<TR>\n";
  }
  else
    print "<TR>\n";
  $override = CheckAllowed($name, $editors);

  if(!$publish)
    print "<TD><IMG src=01.jpg>\n";
  else
    print "<TD>&nbsp;\n";

  print "<TD><A HREF=editguide.php?id=$num>$guidename</A>\n";
  print "<TD>$creation\n";
  print "<TD>$modification\n";
  print "<TD>";
  $ea = explode(',', $editors);
  foreach($ea as $val) {
    print "<A HREF=$descscript?action=user&usrname=$val target=_blank>";
    print "$val</A> ";
  }
    //  print "<TD>$editors\n";
  if($override || ($name == 'admin'))
    print "<TD><A HREF=editguide.php?action=delete&id=$num>מחיקה</A>\n";
  else
    print "<TD>&nbsp;\n";
  //  print "grp: $grp, guidename: $guidename<BR>\n";
}

print "</TABLE>\n";
print "</CENTER>\n";
print "<P dir=$langdir class=text1>\n";
print "<HR>\n";
print "<DIV dir=$langdir class=text2>\n";
print "$l10nstr[165]<BR>\n";				/* FAKT guides system */
print "$l10nstr[166]: ";		/* programming */
print "<A HREF=mailto:ori@helicontech.co.il>$l10nstr[167]</A><BR>\n";		/* Ori Idan */
print "$l10nstr[168]: ";			/* idea and graphical design */
print "<A HREF=mailto:nuritavi@012.net.il>$l10nstr[169]</A><BR>";	/* Avi Abekasis */

?>
</DIV>
</BODY>
</HTML>
