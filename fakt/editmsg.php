<?PHP
header('Content-type: text/html;charset=UTF-8');
?>
<HTML>
<HEAD>
<?PHP
/*
 | Edit message script of FAKT Forums system
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


/* Get command line and cookies paramters */
$action = $_GET['action'];
$num = $_GET['num'];

$data = $_GET['data'];
if(empty($data))
	$data = $_COOKIE['data'];
$name = $_GET['name'];
if(empty($name))
	$name = $_COOKIE['name'];
$name = str_replace("\'", '', $name);	/* prevent SQL injection */
$table = str_replace("\'", '', $_GET['forum']);
if(empty($table))
	$table = $deftable;
$ancestor = $_GET['ancestor'];

if(empty($num))
	$loginurl = "$editscript?forum=$table&action=$action&ancestor=$ancestor";
else
	$loginurl = "$editscript?forum=$table&action=$action&num=$num";
$loginurl = urlencode($loginurl);

$link = mysql_connect($host, $user, $pswd) or die("Could not connect to host $host");
mysql_select_db($database) or die("Could not select database: $database");

/* a little security check.... check if lastonline field of login table is equal to data */
if(empty($name)) {	// can't login with no name
	print "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=$loginscript?forum=$table&url=$loginurl\">\n";
	print "URL: $url<BR>\n";
	exit;
}
$query = "SELECT lastonline FROM login WHERE name='$name'";
$result = mysql_query($query);
if(!$result) {
	echo mysql_error();
	exit;
}
$line = mysql_fetch_array($result, MYSQL_ASSOC);
if($line['lastonline'] != $data) {
	print "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=$loginscript?forum=$table&url=$loginurl\">\n";
	print "URL: $url<BR>\n";
	exit;	/* user is not logged in so display login script */
}


/* Get forum title */
$query = "SELECT forum_title,pghead,pgbottom FROM mainlist WHERE forum='$table'";
$result = mysql_query($query);
if(!$result) {
	echo mysql_error();
	exit;
}
$line = mysql_fetch_array($result, MYSQL_ASSOC);
$forum_title = $line['forum_title'];
$pghead = $line['pghead'];
$pgbottom = $line['pgbottom'];

print "<meta http-equiv=Content-Type content=\"text/html; charset=$charset\">\n";
print "<TITLE>$forum_title</TITLE>\n";
print "<STYLE>\n";
print "body {margin:0 font-size:14px; font-family: arial, sans-serif; color=navy}\n";
print "</STYLE>\n";

if(!empty($base)) {
	print "<BASE HREF=$base>\n";
}
?>
<SCRIPT language=javascript>
function EmoticonsHelp() {
	//retval = window.showModalDialog("emoticons.php", '', 'dialogHeight:200px');
<?PHP
	print "window.open(\"$scriptsurl/emoticons.php\", \"\", \"height=230,width=350,resizable=yes,scrollbars=yes\");\n";
?>
}
</SCRIPT>
</HEAD>
<?PHP
if(!empty($pghead)) {
	print "$pghead\n";
}
else {
	print "<BODY $bodyparam>\n";
	print "$pageheader\n";
}
//print "<H1 dir=RTL>$title</H1>\n";
$title = "";
print "<CENTER><TABLE dir=RTL width=80% border=0 cellpadding=0 bgcolor=lightblue><TR><TD>\n";
ShowUserSex();
print "$name \n";

print "</TABLE></CENTER>\n";

/* Check if user is banned */
$query = "SELECT reason FROM ban WHERE user='$name' AND forum='$table'";
$result = mysql_query($query);
if(mysql_num_rows($result) > 0) {
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	$reason = str_replace('\n', '<BR>\n', $line['reason']);
	print "<CENTER><H1 dir=RTL>$l10nstr[33]</H1></CENTER>\n";		/* you have been banned */
	print "<P dir=$langdir>$l10nstr[34]:<BR>\n";		/* ban reason */
	print "$reason";
	print "<BR><BR><A HREF=$mainfile?forum=$table>$l10nstr[35]</A>\n"; 		/* back to forum */
	print "$pgbottom\n";
	exit;
}

/*
 | Find top of thread for a given message
 | used to display the given message
 */
function GetThreadTop($msgnum) {
	global $table;

	while($msgnum) {
		$query = "SELECT ancestor, num FROM $table WHERE num=$msgnum";
		$result = mysql_query($query);
		if(!$result) {
			echo mysql_error();
			exit;
		}
		$line = mysql_fetch_array($result, MYSQL_ASSOC);
		$msgnum = $line['ancestor'];
	}
	return $line['num'];
}

function RecursiveDelete($num) {
	global $table;

	$query = "SELECT num FROM $table WHERE ancestor='$num'";
	$result = mysql_query($query);
	if(mysql_num_rows($result) == 0)
		return;	/* end of recursive call */
	while($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$n = $line['num'];
		$query = "DELETE FROM $table WHERE num=$n";
		$newresult = mysql_query($query);
		RecursiveDelete($n);
	}
}

function GetForumTitle($table) {
	$query = "SELECT forum_title FROM mainlist WHERE forum='$table'";
	$result = mysql_query($query);
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	return $line['forum_title'];
}

function GetSignature($name) {

	$query = "SELECT signature FROM login WHERE name='$name'";
	$result = mysql_query($query);
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	return $line['signature'];
}

function ShowOriginalMsg($num) {
	global $langdir;
	global $table;
	global $editscript;

	$query = "SELECT * FROM $table WHERE num='$num'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	$title = $line['title'];
	$title = SpecialCodes($title);
	$date = $line['date'];
	$time = $line['time'];
	$datearr = explode('-', $date);
	$date = "$datearr[2]/$datearr[1]/$datearr[0]";
	$user = $line['name'];

	print "</CENTER><P dir=$langdir align=right>\n";
	print "<B><BIG>$title</BIG></B> - ";
	$alias = GetAlias($user);
	print " &nbsp; &nbsp; &rlm;$alias&rlm;";
	print "&nbsp; &nbsp; $time $date<BR><BR>\n";

	$contents = $line['contents'];
	$contents = SpecialCodes($contents);
	print "$contents<BR>\n";
	$url= $line['url1'];
	$urldesc = $line['urldesc1'];
	if(!empty($urldesc) && !empty($url))
		print "<A HREF=$url target=_blank>$urldesc</A><BR>\n";
	$url= $line['url2'];
	$urldesc = $line['urldesc2'];
	if(!empty($urldesc) && !empty($url))
		print "<A HREF=$url target=_blank>$urldesc</A><BR>\n";
	$url= $line['url3'];
	$urldesc = $line['urldesc3'];
	if(!empty($urldesc) && !empty($url))
		print "<A HREF=$url target=_blank>$urldesc</A><BR>\n";
	$inform = $line['flags'] & 1;		/* first bit is email inform */
	print "<BR>\n";
	print "<CENTER>\n";
	if($inform)
		return $user;
}

if($action == 'add') {
	$date = date("Y-m-d");
	$title = $_POST['title'];
	$contents = $_POST['contents'];
	$url1 = $_POST['url1'];
	$urldesc1 = $_POST['urldesc1'];
	$url2 = $_POST['url2'];
	$urldesc2 = $_POST['urldesc2'];
	$url3 = $_POST['url3'];
	$urldesc3 = $_POST['urldesc3'];
	if(empty($title)) {
		ErrorReport("$l10nstr[36]");			/* to title for message */
		exit;
	}
	$inf = $_POST['inform'];
	$sign = $_POST['sign'];
	$flags = 0;
	if($sign) {
		$contents .= "\n";
		$contents .= GetSignature($name);
		$flags |= 2;	/* second bit is add signature */
	}
	if($inf) {
		$flags |= 1;	/* first bit is email inform */
	}
	$query = "INSERT INTO $table (ancestor, title, date, time, lastmod, name, contents, url1, urldesc1, url2, urldesc2, url3, urldesc3, flags) ";
	$query .= " VALUES ('$ancestor', '$title', '$date', CURRENT_TIME(), NOW(), '$name', '$contents', '$url1', '$urldesc1', '$url2', '$urldesc2', '$url3', '$urldesc3', '$flags')";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}

	/*
	 | Message was added, now change last modification time of top of thread.
	 | For this we must first find top of thread, we start searching from current message ancestor.
	 */
	$num = GetThreadTop($ancestor);
	/* first check how many days past since thread started, we do not jump threads older then 3 days */
	$query = "SELECT TO_DAYS('$date') - TO_DAYS(date) FROM $table WHERE num='$num'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	$line = mysql_fetch_array($result, MYSQL_NUM);
	$dif = $line[0];

	if($dif < 10) {
		$query = "UPDATE $table SET lastmod=NOW() WHERE num='$num'";
		$result = mysql_query($query);
		if(!$result) {
			echo mysql_error();
			exit;
		}
	}

	print "<CENTER><BIG><H1 dir=$langdir>$l10nstr[37]</H1></BIG></CENTER>\n";
	print "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"2; URL=$mainfile?forum=$table\">\n";
	print "<P dir=RTL>\n";
	print "$l10nstr[38]";			/* in 2 seconds you will be returned to forum */
	/*
	 | Send mail notification
	 */
	$user = $_GET['msg'];
	if($user) {
		$to = GetEmail($user);
		$forum_title = GetForumTitle($table);
		$subject .= $forum_title;
		$num = mysql_insert_id();
		mail($to, $subject, "You have a replay for your message in forum: $forum_title\r\n"
			."To see the message go to: $mainfile?forum=$table&show=$num",
			"From: $adminmail\r\n"
			."Reply-To: $replymail\r\n"
			."X-Mailer: PHP/" . phpversion());
	}
	mysql_close($link);
	print "$pgbottom\n";
	exit;
}

if($action == 'update') {
	$date = date("Y-m-d");
	$title = $_POST['title'];
	$contents = $_POST['contents'];
	$url1 = $_POST['url1'];
	$urldesc1 = $_POST['urldesc1'];
	$url2 = $_POST['url2'];
	$urldesc2 = $_POST['urldesc2'];
	$url3 = $_POST['url3'];
	$urldesc3 = $_POST['urldesc3'];
	if(empty($title)) {
		ErrorReport("$l10nstr[36]");		/* no title for message */
		exit;
	}
	$inf = $_POST['inform'];
	if($inf)
		$flags = 1;
	$query = "UPDATE $table SET title='$title', date='$date', time=CURRENT_TIME(), name='$name', contents='$contents', ";
	$query .= "url1='$url1', urldesc1='$urldesc1', url2='$url2', urldesc2='$urldesc2', url3='$url3', urldesc3='$urldesc3' WHERE num='$num'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	print "<CENTER><BIG><H1 dir=$langdir>$l10nstr[37]</H1></BIG></CENTER>\n";
	print "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"2; URL=$mainfile?forum=$table\">\n";
	print "<P dir=$langdir>\n";
	print "$l10nstr[38]";		/* in 2 seconds you will be retuned to forum */
	mysql_close($link);
	print "$pgbottom\n";
	exit;
}

if($action == 'delete') {
	$query = "DELETE FROM $table WHERE num='$num'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	RecursiveDelete($num);
	print "<CENTER><BIG><H1>$l10nstr[39]</H1></BIG></CENTER>\n";
	print "<P dir=$langdir>\n";
	print "$l10nstr[38]";		/* in 2 seconds you will be returned to forum */
	print "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"2; URL=$mainfile?forum=$table\">\n";
	mysql_close($link);
	print "$pgbottom\n";
	exit;
}

if($action == 'edit') {
	$query = "SELECT * FROM $table WHERE num='$num'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	$title = $line['title'];
	$contents = $line['contents'];
	$url1 = $line['url1'];
	$urldesc1 = $line['urldesc1'];
	$url2 = $line['url2'];
	$urldesc2 = $line['urldesc2'];
	$url3 = $line['url3'];
	$urldesc3 = $line['urldesc3'];
}

if($action == 'forumedit') {
	$query = "SELECT * FROM mainlist WHERE forum='$table'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	$tagline = $line['tag_line'];
	$desc = $line['description'];
	$pghead = $line['pghead'];
	$pgbottom = $line['pgbottom'];
	$managers = $line['manager'];
	$members = $line['members'];
	$forum_title = $line['forum_title'];
	print "<CENTER>\n";
	print "<H1>$l10nstr[40]</H1>\n";		/* edit forum details */
	print "<BR><H1 dir=$langdir>$l10nstr[41]: ";	/* forum */
	print "$forum_title</H1>\n";
	print "<TABLE border=8 dir=$langdir>\n";
	print "<FORM action=$editscript?forum=$table&action=forumupdate method=post>\n";
	print "<TR><TD>$l10nstr[42]: \n";		/* title line */
	print "<TD><INPUT type=text name=tagline value=\"$tagline\" size=60>\n";
	print "<TR><TD>$l10nstr[43]: \n";	/* forum description */
	print "<TD><TEXTAREA name=description cols=60 rows=5>$desc</TEXTAREA>\n";
	if($name == 'admin') {	/* system administrator has right to edit more parameters */
		print "<TR><TD>$l10nstr[44]:\n";		/* forum managers */
		print "<TD><INPUT type=text name=manager value=\"$managers\" size=60>\n";
		print "<TR><TD>$l10nstr[49]:\n";	/* top of page code */
		print "<TD><TEXTAREA name=pghead cols=60 rows=5>$pghead</TEXTAREA>\n";
		print "<TR><TD>$l10nstr[50]:\n";
		print "<TD><TEXTAREA name=pgbottom cols=60 rows=5>$pgbottom</TEXTAREA>\n";
	}

	print "<TR><TD>$l10nstr[45]:\n";		/* forum members */
	print "<TD><TEXTAREA name=members cols=60 rows=5>$members</TEXTAREA><BR>\n";
	print "<BR>$l10nstr[46], ";	/* comma seperated member list */
	print "<BR>$l10nstr[47]";	/* * all are allowed but forum will not be shown in list */
	print "$l10nstr[48]";

	print "<TR><TD colspan=2 align=center><INPUT type=submit value=$l10nstr[51]>\n";	/* send */
	print "</FORM>\n";
	print "</TABLE>\n";
	print "$pgbottom\n";
	exit;
}
if($action == 'forumupdate') {
	$line = $_POST['tagline'];
	$desc = $_POST['description'];
	$members = $_POST['members'];
	if($name == 'admin') {
		$managers = $_POST['manager'];
		$pghead = $_POST['pghead'];
		$pgbottom = $_POST['pgbottom'];
		$query = "UPDATE mainlist SET tag_line='$line', description='$desc', manager='$managers', pghead='$pghead', pgbottom='$pgbottom', members='$members' WHERE forum='$table'";
	}
	else
		$query = "UPDATE mainlist SET tag_line='$line', description='$desc', members='$members' WHERE forum='$table'";
	//print "$query<BR>";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	print "<CENTER>\n";
	print "<H1>$l10nstr[52]</H1>\n";			/* forum details updated */
	print "<BR><A HREF=$mainfile?forum=$table>$l10nstr[35]</A>\n";		/* back to forum */
	print "$pgbottom\n";
	exit;
}

?>

<CENTER>
<?PHP
	if($action == 'edit') {
		print "<H1>$l10nstr[53]</H1>\n";		/* edit message */
		print "<FORM name=editmsg action=$editscript?forum=$table&action=update&num=$num&name=$name&ancestor=$ancestor method=post>\n";
	}
	else if($action == 'reply') {
		$user = ShowOriginalMsg($ancestor);
		print "<H1>$l10nstr[54]</H1>\n";		/* reply to message */
		print "<FORM name=reply action=$editscript?forum=$table&action=add&name=$name&ancestor=$ancestor&msg=$user method=post>\n";
	}
	else {
		print "<H1>$l10nstr[28]</H1>\n";		/* new message */
		print "<FORM name=newmsg action=$editscript?forum=$table&action=add&name=$name&ancestor=0 method=post>\n";
	}
	if(empty($url1))
		$url1 = 'http://';
	if(empty($url2))
		$url2 = 'http://';
	if(empty($url3))
		$url3 = 'http://';
?>
<TABLE border=8 dir=RTL>
<TR><TD>
<?PHP print "$l10nstr[55]: \n" ?>

<TD>
<?PHP
	print "<INPUT type=text name=title value=\"$title\" size=40>\n";
	print "<TR><TD valign=top>\n";
	print "$l10nstr[56]: \n";		/* contents */
?>
<TD>
<TEXTAREA name=contents cols=80 rows=15>
<?PHP print "$contents"; ?>
</TEXTAREA>
<?PHP print "<TR><TD>$l10nstr[57]: \n";	/* link */ ?>
<TD>
<?PHP
	print "<INPUT type=text name=urldesc1 size=30 value=\"$urldesc1\"> &nbsp;\n";
	print "<INPUT type=text name=url1 size=40 value=\"$url1\" dir=LTR> &nbsp;\n";
?>
<?PHP print "<TR><TD>$l10nstr[57]: \n";	/* link */ ?>
<TD>
<?PHP
	print "<INPUT type=text name=urldesc2 size=30 value=\"$urldesc2\"> &nbsp;\n";
	print "<INPUT type=text name=url2 size=40 value=\"$url2\" dir=LTR> &nbsp;\n";
?>
<?PHP print "<TR><TD>$l10nstr[57]: \n";	/* link */ ?>
<TD>
<?PHP
	print "<INPUT type=text name=urldesc3 size=30 value=\"$urldesc3\"> &nbsp;\n";
	print "<INPUT type=text name=url3 size=40 value=\"$url3\" dir=LTR> &nbsp;\n";
	print "<TR><TD colspan=2>\n";
	print "$l10nstr[58]: ";	/* send me email on reply to message */
	print "<INPUT type=checkbox name=inform>\n";
	if($action != 'edit') {
		print "&nbsp; &nbsp;\n";
		print "$l10nstr[59]: ";	/* add signature */
		print "<INPUT type=checkbox name=sign checked>\n";
	}
?>
<TR><TD colspan=2 align=center>
<?PHP print "<INPUT type=submit value=$l10nstr[51]>\n"; ?>
</TABLE>
<?PHP print "<A HREF=javascript:EmoticonsHelp()>$l10nstr[60]</A>\n"; ?>
</FORM>
<?PHP
print "$pbbottom\n";
?>
</BODY>
</HTML>
