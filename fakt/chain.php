<?PHP
/*
  | message chaining change script of FAKT Forums system
  |
  | FAKT Free Authoring Knowledge & Thinking
  | Copyright: Helicon technologies LTD. 2003
  | The FAKT system is distributed under the GNU public license
  |
  | This file is part of FAKT forums system.
  |
  | FAKT is free software; you can redistribute it and/or modify
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
?>
<HTML>
<HEAD>
<?PHP
include('l10n.inc');
print "<TITLE>$l10nstr[16]</TITLE>\n";	/* change message chaining */ ?>
?>
</HEAD>
<BODY>
<?PHP
include('config.inc.php');
include('glob.inc');
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

$forum = $_GET['forum'];

$query = "SELECT lastonline FROM login WHERE name='$name'";
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

/* Check if this user is forum manager */
$query = "SELECT forum_title, manager FROM mainlist WHERE forum='$forum'";
$result = mysql_query($query);
if(!$result) {
	echo mysql_error();
	exit;
}
$line = mysql_fetch_array($result, MYSQL_ASSOC);
$manager = $line['manager'];
$forum_title = $line['forum_title'];
$managers = explode(',', $manager);
$IsManager = 0;
if($name == 'admin')
	$IsManager = 1;	/* bypass managers check */
else {
	foreach ($managers as $val) {	/* check if current user is forum manager */
		$val = trim($val);
		if($val == $name)
			$IsManager = 1;
	}
}

print "<CENTER><H1 dir=RTL>$l10nstr[7]: ";
print "$forum_title</H1>\n";
print "<BR>\n";
if(!$IsManager) {
	print "<H1>$l10nstr[117]</H1>\n";		/* you don't have management premissions in this file */
	exit;
}

$action = $_GET['action'];
if($action == 'dochain') {
	$msgnum = (int)$_POST['msgnum'];
	$chainto = (int)$_POST['chainto'];

	$query = "UPDATE $forum SET ancestor='$chainto' WHERE num='$msgnum'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	print "<H1>$l10nstr[131]</H1>\n";
	print "<BR><A HREF=$mainfile?forum=$forum>$l10nstr[35]</A>\n";			/* return to forum */
	exit;
}

print "<FORM action=chain.php?action=dochain&forum=$forum method=post>\n";
print "<CENTER>\n";
print "<TABLE border=8 dir=RTL><TR>\n";
print "<TD>$l10nstr[132]: \n";		/* message number to chain */
print "<TD><INPUT type=text name=msgnum>\n";
print "<TR>\n";
print "<TD>$l10nstr[133]: \n";		/* where to */
print "<TD><INPUT type=text name=chainto>\n";
print "<TR><TD colspan=2 align=center>\n";
print "<INPUT type=submit value=\"$l10nstr[134]\">\n";

?>
</TABLE>
</BODY>
</HTML>
