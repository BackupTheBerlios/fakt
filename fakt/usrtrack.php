<?PHP
header('Content-type: text/html;charset=UTF-8');
?>
<?PHP
/*
  | User tracking script for YAFS
  | This script will show table of last users action in the forums system
  |
  | Esc Forums system copyright: Helicon technologies LTD. 2003
  | The Esc forums system is distributed under the GNU public license
  |
  | This file is part of Esc Forums system.
  |
  | Esc Forums system is free software; you can redistribute it and/or modify
  | it under the terms of the GNU General Public License as published by
  | the Free Software Foundation;
  |
  | Esc Forums system is distributed in the hope that it will be useful,
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
include('common.inc');

print "<HTML>\n";
print "<HEAD>\n";
print "<meta http-equiv=Content-Type content=\"text/html; charset=$charset\">\n";
print "<TITLE>$l10nstr[224]</TITLE>\n";
print "</HEAD>\n";

function GetForumTitle($forum) {
	$query = "SELECT forum_title FROM mainlist WHERE forum='$forum'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	$line = mysql_fetch_array($result);
	$forum_title = $line['forum_title'];
	return $forum_title;
}

function FormatTime($t) {

	$timearr = explode(':', $t);

	$timestr = GetTimeStr($timearr[3], $timearr[4], $timearr[5], $timearr[1], $timearr[0], $timearr[2]);
	return $timestr;
}

$name = $_COOKIE['name'];
$link = mysql_connect($host, $user, $pswd) or die("Could not connect to host $host");
mysql_select_db($database) or die("Could not select database: $database");
?>
<BODY>
<CENTER>
Current time on server: 
<?PHP
$timenow = time() + $timeoffset * 3600;
$timestr = date("d/m/Y  H:i:s", $timenow);
print "$timestr<BR><BR>\n";
?>
<TABLE dir=RTL border=8><TR>
<?PHP
print "<TD>$l10nstr[118]\n";	/* user */
print "<TD>$l10nstr[41]\n";		/* forum */
print "<TD>$l10nstr[225]\n";	/* last opertaion time */

$query = "SELECT DATE_FORMAT(last, '%d:%m:%Y:%H:%I:%S'), forum, user FROM usrtrack ORDER BY last DESC";
$result = mysql_query($query);
if(!$result) {
	echo mysql_error();
	exit;
}
while($line = mysql_fetch_array($result, MYSQL_NUM)) {
	$last = $line[0];
	$forum = $line[1];
	$alias = GetAlias($line[2]);
	$user = $line[2];
	$forum_title = GetForumTitle($forum);

	print "<TR>\n";
	print "<TD><A HREF=$descscript?action=user&usrname=$user target=_blank>$alias</A>\n";
	print "<TD><A HREF=$mainfile?forum=$forum>$forum_title</A>\n";
	print "<TD dir=LTR>\n";
	$timestr = FormatTime($last);
	print "$timestr\n";
}
?>

</BODY>
</HTML>
