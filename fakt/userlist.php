<?PHP
header('Content-type: text/html;charset=UTF-8');
?>
<HTML>
<HEAD>
<?PHP
/*
  | Show users list script of FAKT system
  | This script will show all users list except the main user 'admin'
  |
  | FAKT copyright: Helicon technologies LTD. 2003
  | The Esc forums system is distributed under the GNU public license
  |
  | This file is part of FAKT system.
  |
  | FAKT is free software; you can redistribute it and/or modify
  | it under the terms of the GNU General Public License as published by
  | the Free Software Foundation;
  |
  | FAKT is distributed in the hope that it will be useful,
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
include('l10n.inc');
include('glob.inc');
include('common.inc');

function GetAge($birthdate) {
	$today = getdate();
	$year = $today['year'];
	$month = $today['mon'];

	$birthdate = explode('-', $birthdate);
	$birthyear = $birthdate[0];
	if($birthyear == 0)
		return '';
	$birthmonth = $birthdate[1];
	if($birthmonth == 0)
		return '';
	$age = $year - $birthyear;
	if($month < $birthmonth)
		$age--;
	return $age;
}
?>
<meta http-equiv=Content-Type content="text/html; charset=UTF-8">
<TITLE>רשימת משתמשים</TITLE>
</HEAD>
<?PHP
print "<BODY $bodyparam >\n";
print "$pageheader\n";

$data = $_GET['data'];
if(empty($data))
     $data = $_COOKIE['data'];
$name = $_GET['name'];
if(empty($name))
     $name = $_COOKIE['name'];

//print "Name: $name<BR>\n";

$action = $_GET['action'];

$link = mysql_connect($host, $user, $pswd) or die("Could not connect to host $host");
mysql_select_db($database) or die("Could not select database: $database");

/* a little security check.... check if lastonline field of login table is equal to data */
$query = "SELECT name,lastonline FROM login WHERE name='$name'";
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
if(empty($name)) {
	print "<CENTER><H1>$l10nstr[220]</H1>\n";
	print "<BR>\n";
	print "<A HREF=$loginscript?url=$userlist>$l10nstr[61]</A></BR>\n";		/* login to system */
	exit;
}

/* We are connected Ok so print user list */

/* first print current connected user name */
print "<P dir=RTL align=right><CENTER>\n";
print "<TABLE dir=RTL width=80% border=0 cellpadding=0 bgcolor=lightblue><TR><TD>\n";
ShowUserSex();
print "<A HREF=$descscript?action=user&usrname=$name target=_blank>$name</A> \n";
print "<TD width=30%>\n";
print "<TD>\n";
print "<A HREF=$loginscript?url=$listlist>$l10nstr[3]</A>\n";		/* login as different user */
print "</TABLE><BR><BR>\n";

if($action == 'deluser') {
	$delname = $_GET['user'];

	$query = "DELETE FROM login WHERE name='$delname'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
}
$query = "SELECT name,fullname,pubemail,birthdate,sex FROM login WHERE name!='admin' ORDER by name ASC";
$result = mysql_query($query);
if(!$result) {
	echo mysql_error();
	exit;
}
?>
<TABLE border=2 dir=RTL><TR>
<?PHP
if($name == 'admin')	/* system administrator */
	print '<TD>';

print "<TD><BIG><B>$l10nstr[122]\n";
print "<TD><BIG><B>$l10nstr[83]<TD><BIG><B>$l10nstr[84]<TD><BIG><B>$l10nstr[87]<TD><BIG><B>$l10nstr[221]\n";
$i = 0;
while($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$username = $line['name'];
	$fullname = $line['fullname'];
	$email = $line['pubemail'];
	$birthdate = $line['birthdate'];
	$sex = $line['sex'];
	print "<TR>\n";
	if($name == 'admin') {
		print "<TD><A HREF=$userlist?action=deluser&user=$username>מחק</A>\n";
	}
	print "<TD><A HREF=$descscript?action=user&usrname=$username target=_blank>$username</A> \n";
	print "<TD>$fullname\n";
	print "<TD><A HREF=mailto:$email>$email</A>\n";
	if($sex == 'female')
		print "<TD>$l10nstr[88]\n";		/* female */
	else
		print "<TD>$l10nstr[89]\n";		/* male */
	$age = GetAge($birthdate);
	print "<TD>$age\n";
	$i++;
}
?>
</TABLE>
<?PHP
print "<P dir=RTL>\n";
print "$l10nstr[222]: ";
print "$i ";
print "$l10nstr[223]";
print "<BR>\n";
?>
</BODY>
</HTML>
