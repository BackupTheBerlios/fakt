<?PHP
header('Content-type: text/html;charset=UTF-8');
?>
<?PHP
/*
  | ban users script of FAKT Forums system
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
include('config.inc.php');
include('glob.inc');
include('l10n.inc');
include('common.inc');

$link = mysql_connect($host, $user, $pswd) or die("Could not connect to host $host");
mysql_select_db($database) or die("Could not select database: $database");

$forum = addslashes($_GET['forum']);
$user = str_replace("\'", '', $_GET['user']);
$action = $_GET['action'];

$name = $_GET['name'];
if(empty($name))
     $name = $_COOKIE['name'];
/* look for forum in mainlist and check if 'name' is a manager of this forum */
$IsManager = 0;
$query = "SELECT forum_title, manager FROM mainlist WHERE forum='$forum'";
$result = mysql_query($query);
if(!$result) {
	echo mysql_error();
	exit;
}
$line = mysql_fetch_array($result, MYSQL_ASSOC);
if($name == 'admin')
	$IsManager = 1;	/* bypass administrator check since user is global administrator */
else {
	$manager = $line['manager'];
	$managers = explode(',', $manager);
	foreach ($managers as $val) {	/* check if current user is forum manager */
		$val = trim($val);
		if($val == $name)
			$IsManager = 1;
	}
}
$forum_title = $line['forum_title'];

?>
<HTML>
<HEAD>
<?PHP
	print "<meta http-equiv=Content-Type content=\"text/html; charset=$charset\">\n";
	print "<TITLE>$l10nstr[116]</TITLE>\n";
	print "</HEAD>\n";
	print "<BODY>\n";
	print "<CENTER><H1>$l10nstr[116]</H1>\n";		/* ban users */

print "<H2 dir=$langdir>$l10nstr[41]: ";		/* forum */
print "$forum_title</H2>\n";

if(!$IsManager) {
	print "<BR><BR><H1>$l10nstr[117]</H1>\n";		/* you do not have management premission in this file */
	exit;
}

if($action == 'doban') {
	/* put user details in ban table */
	$comments = str_replace("\'", "\\'", $_POST['comments']);
	$reason = str_replace("\'", "\\'", $_POST['reason']);
	$query = "INSERT INTO ban VALUES ('$forum', '$user', '$reason', '$comments')";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	print "</CENTER><P dir=RTL><BIG>\n";
	print "$l10nstr[118]: ";			/* user */
	print "$user \n";
	print "$l10nstr[119].";			/* banned from writing messages in forum */
	print "<BR>\n";
	print "$l10nstr[120] ";			/* to view banned list */
	print "<A HREF=$banscript?action=showlist&forum=$forum>$l10nstr[121]</A>\n";		/* Press here */
	exit;
}
if($action == 'showlist') {
	/* show banned users list */
	print "<BR><TABLE border=3 dir=RTL><TR>\n";
	print "<TD>\n";
	print "<TD><BIG><B>$l10nstr[122]\n";		/* user name */
	print "<TD><BIG><B>$l10nstr[123]\n";		/* ban reason */
	print "<TD><BIG><B>$l10nstr[104]\n";		/* comments */

	$query = "SELECT * from ban WHERE forum='$forum'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	while($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$user = $line['user'];
		$reason = $line['reason'];
		$comments = $line['comment'];
		$reason = str_replace("\n", "<BR>\n", $reason);
		$comments = str_replace("\n", "<BR>\n", $comments);
		print "<TR>\n";
		print "<TD><A HREF=$banscript?action=del&user=$user&forum=$forum>$l10nstr[124]</A>\n";		/* revmove ban */
		print "<TD><A HREF=$descscript?action=user&usrname=$user target=_blank>$user</A>\n";
		print "<TD>$reason\n";
		print "<TD>$comments\n";
	}
	print "</TABLE>\n";
	print "</CENTER><BR><BR>\n";
	print "<P dir=RTL>\n";
	print "$l10nstr[125]";		/* to ban a user choose ban user from the user's message */
	print "<BR><BR><A HREF=$mainfile?forum=$forum>$l10nstr[35]</A>\n";		/* return to forum */
	exit;
}
if($action == 'del') {
	$query = "DELETE FROM ban WHERE forum='$forum' AND user='$user'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	print "<BR><BR><H1 dir=RTL>$l10nstr[118]: ";		/* user */
	print "$user ";
	print "$l10nstr[126]";		/* removed from ban list */
	print "</H1>\n";
	print "<BR><BR><A HREF=$mainfile?forum=$forum>$l10nstr[35]</A>\n";		/* return to forum */
	exit;
}

print "<FORM action=$banscript?action=doban&forum=$forum&user=$user method=post>\n";
print "<TABLE border=8 dir=RTL><TR>\n";
print "<TD>$l10nstr[41]:\n";		/* forum */
print "<TD>$forum\n";
print "<TR><TD>$l10nstr[118]:\n";		/* user */
print "<TD>$user\n";
print "<TR><TD>$l10nstr[127]:<BR>($l10nstr[128])\n";		/* reson (will be displayed to user) */
print "<TD><TEXTAREA name=reason rows=3 cols=40></TEXTAREA>\n";
print "<TR><TD>$l10nstr[129]:<BR>($l10nstr[130])\n";		/* comment (will not be displayed to user) */
print "<TD><TEXTAREA name=comments rows=3 cols=40></TEXTAREA>\n";
print "<TR><TD colspan=2 align=center><INPUT type=submit value=בצע>\n";
print "</TABLE>\n";
print "</FORM>\n";

?>
</BODY>
</HTML>
