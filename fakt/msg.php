<?PHP
header('Content-type: text/html;charset=UTF-8');
?>
<?PHP
/*
  | Private messaging system script of FAKT Forums system
  | This script will show forum description or user details if action=user
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

print "<HTML>\n<HEAD>\n";
print "<meta http-equiv=Content-Type content=\"text/html; charset=$charset\">\n";
print "<TITLE>$l10nstr[170]</TITLE>\n";		/* private messaging system */

?>

<SCRIPT language=javascript>
var The_Win;

function winOp(){

<?PHP print "The_Win=window.open(\"$descscript?forum=$table\",\"Description\",\"height=230,width=350,resizable=yes\");\n";
?>
}

function Test() {
	alert("Got here");
}

function setCookie(name, value, days) {
	var dc = document.cookie;

	if (!days) days = 1; // default to 1 day if empty

	var expdate = new Date();
	expdate.setTime(expdate.getTime() + days*24*60*60*1000);

	document.cookie += "; " + name + "=" + escape(value) + "; expires=" + expdate.toGMTString();
}

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
		document.all[t].style.color = '#ff0000';
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
<STYLE>
.para {display: none;}
body {margin:0 font-size:14px; font-family: arial, sans-serif; color=navy}
</STYLE>

</HEAD>
<?PHP
$link = mysql_connect($host, $user, $pswd) or die("Could not connect to host $host");
mysql_select_db($database) or die("Could not select database: $database");

$table = $_GET['forum'];
if(!empty($table)) {
	$query = "SELECT pghead,pgbottom FROM mainlist WHERE forum='$table'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	$pghead = $line['pghead'];
	$pgbottom = $line['pgbottom'];
}

if(!empty($pghead)) {
	print "$pghead\n";
}
else {
	print "<BODY $bodyparam>\n";
	print "$pageheader\n";
}
print "<iframe name=nul src=$nulfrm?forum=$table frameborder=no scrolling=no height=1 width=1></iframe>\n";
print "<CENTER><H1>$l10nstr[170]</H1></CENTER>\n";			/* private messaging system */
print "<P dir=$langdir>\n";

$data = $_GET['data'];
if(empty($data))
     $data = $_COOKIE['data'];
$name = $_GET['name'];
if(empty($name))
     $name = $_COOKIE['name'];
$action = $_GET['action'];
$to = $_GET['to'];

/* a little security check.... check if lastonline field of login table is equal to data */
$name = str_replace("\'", '', $name);	/* prevent SQL injection */
$query = "SELECT lastonline, email, fullname FROM login WHERE name='$name'";
$result = mysql_query($query);
if(!$result) {
	echo mysql_error();
	exit;
}
$line = mysql_fetch_array($result, MYSQL_ASSOC);
$lastonline = $line['lastonline'];

if(empty($data))
	$data = "none";

if(empty($name) || ($lastonline != $data)) {
	print "<P dir=RTL>\n";
	print "$l10nstr[171]";		/* to enter messaging system you must connect to the system */
	print "<BR>\n";

	print "<A HREF=$loginscript?url=$privatemsg?action=$action";
	if(!empty($to))
		print "&to=$to";
	print ">";
	print "$l10nstr[121]";		/* press here */
	print "</A>\n";
	exit;
}
print "<P dir=$langdir align=right><CENTER>\n";
print "<TABLE dir=$langdir width=80% border=0 cellpadding=0 bgcolor=lightblue><TR><TD>\n";
ShowUserSex();
print "<A HREF=$descscript?action=user&usrname=$name target=_blank dir=$langdir>$name</A> \n";
print "<TD width=30%>\n";
print "<TD>\n";
print "<A HREF=$loginscript?url=$privatemsg>$l10nstr[3]</A>";		/* connect as other user */
print "</TABLE></CENTER>\n";
print "<P dir=$langdir align=right>\n";

if($action == 'new') {
	print "$l10nstr[28]";		/* new message */
	print "<BR><BR>\n";
	print "<CENTER><TABLE border=8 dir=$langdir><TR>\n";
	print "<FORM action=$privatemsg?action=send method=post>\n";
	print "<INPUT type=hidden name=from value=$name>\n";
	print "<TD>$l10nstr[172]:\n";		/* to */
	print "<TD><INPUT type=text name=to value=$to>\n";
	print "<TR>\n";
	print "<TD>$l10nstr[173]:\n";		/* subject */
	print "<TD><INPUT type=text name=subject size=50>\n";
	print "<TR>\n";
	print "<TD>$l10nstr[56]:\n";		/* contents */
	print "<TD><TEXTAREA name=contents rows=10 cols=60></TEXTAREA>\n";
	print "<TR>\n";
	print "<TD colspan=2 align=center><INPUT type=submit value=$l10nstr[51]>\n";
	print "</FORM>\n";
	print "</TABLE>\n";

	exit;
}
if($action == 'reply') {
	$num = (int)$_GET['msg'];
	$query = "SELECT * FROM msg WHERE num='$num'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	$to = $line['sender'];	/* we reply to sender */
	$subject = "$l10nstr[174]: ";
	$subject .= $line['subject'];
	$contents = "\n\n$l10nstr[175]\n";	/* original message */
	$contents .= $line['contents'];
	print "$l10nstr[176]";		/* answer to message */
	print "<BR><BR>\n";
	print "<CENTER><TABLE border=8 dir=$langdir><TR>\n";
	print "<FORM action=$privatemsg?action=sendr&msg=$num method=post>\n";
	print "<INPUT type=hidden name=from value=$name>\n";
	print "<TD>$l10nstr[172]:\n";		/* to */
	print "<TD><INPUT type=text name=to value=$to>\n";
	print "<TR>\n";
	print "<TD>$l10nstr[173]:\n";		/* subject */
	print "<TD><INPUT type=text name=subject value=\"$subject\" size=50>\n";
	print "<TR>\n";
	print "<TD>$l10n[56]: \n";
	print "<TD><TEXTAREA name=contents rows=10 cols=60>$contents</TEXTAREA>\n";
	print "<TR>\n";
	print "<TD colspan=2 align=center><INPUT type=submit value=$l10nstr[51]>\n";
	print "</FORM>\n";
	print "</TABLE>\n";

}
if($action == 'sendr') { /* send reply */
	/* Now mark message as replyed... */
	$num = (int)$_GET['msg'];
	$query = "UPDATE msg SET flags=2 WHERE num='$num'";
	//print "$query<BR>\n";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}

	$action = 'send';	/* now continue for normal send */
}
if($action == 'send') {
	$sender = $_POST['from'];
	$receiver = $_POST['to'];
	$subject = $_POST['subject'];
	$contents = $_POST['contents'];
	$query = "INSERT INTO msg VALUES (";
	$query .= "NULL, '$sender', '$receiver', 0, NOW(), '$subject', '$contents')";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	print "<CENTER><H1>$l10nstr[177]</H1></CENTER>\n";	/* your message has been sent */
	exit;
}
if($action == 'del') {
	$num = (int)$_GET['msg'];
	$query = "DELETE FROM msg WHERE num='$num'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
}

/* print all messages for user */
if($action == 'sent') {
	print "<H1 dir=$langdir>$l10nstr[178]</H1>";		/* messages sent */
	$query = "SELECT * FROM msg WHERE sender='$name'";
}
else {
	print "<H1 dir=$lagndir>$l10nstr[179]</H1>";
	$query = "SELECT * FROM msg WHERE receiver='$name'";
}
$result = mysql_query($query);
if(!$result) {
	echo mysql_error();
	exit;
}
print "<CENTER>\n";
print "<TABLE dir=RTL border=8 cellpadding=0 cellspacing=0>\n";
if($action == 'sent')
	print "<TR><TD><BIG><B>$l10nstr[172]<TD><BIG><B>$l10nstr[173]<TD><BIG><B>$l10nstr[180]<TD><BIG><B>$l10nstr[181]\n"; /* to, subject, time, status */
else
	print "<TR><TD><BIG><B>$l10nstr[182]<TD><BIG><B>$l10nstr[173]<TD><BIG><B>$l10nstr[180]\n";		/* from, time */
while($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	print "<TR>\n";
	$num = $line['num'];
	if($action == 'sent')
		$user = $line['receiver'];
	else
		$user = $line['sender'];
	$alias = GetAlias($user);
	$flags = $line['flags'];
	$t = $line['time'];
	sscanf($t, "%d-%d-%d %d:%d:%d", &$year, &$month, &$day, &$hour, &$min, &$sec);
	$subject = $line['subject'];
	$contents = $line['contents'];
	//print "<TABLE dir=RTL border=0>\n";
	print "<TD><A HREF=$descscript?action=user&usrname=$user>$alias</A>\n";
	if($action == 'sent')
		print "<TD><A id=t$num HREF=javascript:void() onclick=\"blocking('msg$num', 't$num')\">$subject</A>\n";
	else {
		print "<TD><A id=t$num HREF=$nulfrm?msg=$num&forum=msg target=nul onclick=\"blocking('msg$num', 't$num')\">";
		if($flags != 0)
			print "<FONT color=#ff0000>$subject</FONT></A>";
		else
			print "$subject</A>\n";
	}
	print " <TD dir=$langdir>$day/$month/$year ";
	if($hour < 10)
		print "0$hour:";
	else
		print "$hour:";
	if($min < 10)
		print "0$min\n";
	else
		print "$min\n";
	if($action == 'sent') {
		if($flags == 0)
			print "<TD>$l10str[183]\n";		/* not read */
		else if($flags == 1)
			print "<TD>$l10nstr[184]\n";
		else if($flags == 2)
			print "<TD>$l10nstr[185]\n";
	}
	//print "</TABLE>\n";
	print "<TR><TD colspan=4>\n";
	print "<TABLE class=para id=msg$num dir=$langdir border=0>\n";
	//print "<DIV class=para id=msg$num>\n";
	print "<TR><TD>\n";
	$contents = SpecialCodes($contents);
	print "$contents<BR><BR>\n";
	if($action != 'sent') {
		print "<A HREF=$privatemsg?action=reply&msg=$num>$l10nstr[186]</A>&nbsp; :: &nbsp;\n";		/* respond to message */
		print "<A HREF=$privatemsg?action=del&msg=$num>$l10nstr[154]</A>\n";		/* delete */
	}
	print "</TABLE>\n";
	//print "</DIV>\n";
}
print "</TABLE>\n";
print "</CENTER>\n";
if($action != 'sent')
	print "<H2 dir=RTL><A HREF=$privatemsg?action=sent>$l10nstr[187]</A></H2>\n";		/* press here for sent messages */
else
	print "<H2 dir=$lagndir><A HREF=$privatemsg>$l10nstr[188]</A></H2>\n"; /* Press here for received messages */

?>
</BODY>
</HTML>


