<?PHP
header('Content-type: text/html;charset=UTF-8');
?>
<HTML>
<HEAD>
<?PHP
/*
  | Main file of FAKT forums system formerly: YAFS (Yet Another Forums System)
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
include('glob.inc');
include('l10n.inc');
include('common.inc');

print "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=$charset\">\n";

$Pages[0] = 0;
$table = $_GET['forum'];
$table = str_replace("\'", '', $table);	/* prevent SQL injection */

?>

<SCRIPT language=javascript>
var The_Win;

function winOp(){

<?PHP print "The_Win=window.open(\"$descscript?forum=$table\",\"Description\",\"height=230,width=350,resizable=yes,scrollbars=yes\");\n";
?>
}

function openRulesWin() {
<?PHP
	print "The_win=window.open(\"$scriptsurl/rules.html\",\"$l10nstr[0]\",\"height=230,width=350,resizable=yes,scrollbars=yes\");\n";
?>
}

function AddFile(addfilescript){
	The_Win=window.open(addfilescript,"Description","height=150,width=470,resizable=yes,scrollbars=yes");
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
	setCookie('visited', t, 1);

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
body {margin:0 font-size:14px; font-family: arial, sans-serif; }
</STYLE>

<?PHP

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

if($table) {
	$query = "SELECT * FROM mainlist WHERE forum='$table'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	$members = $line['members'];
	if(!CheckAllowed($name, $members)) {
		print "<CENTER><H1>$l10nstr[1]</H1></CENTER>\n";
		print "<BR><BR><P dir=RTL>\n";
		print "<A HREF=$mainfile>$l10n[2]<BR><BR>\n";		/* to forum list */
		print "<BR><BR><P dir=RTL>\n";
		ShowUserSex();
		print "<A HREF=$descscript?action=user&usrname=$name target=_blank>$name</A><BR> \n";
		print "<A HREF=$loginscript?forum=$table&url=$mainfile?forum=$table>$l10nstr[3]</A><BR>\n"; 	/* connect as another user */
		exit;
	}

	$pagehead = $line['pghead'];
	$pgbottom = $line['pgbottom'];

	$description = $line['description'];
	$forum_title = $line['forum_title'];
	$tag_line = $line['tag_line'];
	$manager = $line['manager'];

	print "<TITLE>$forum_title</TITLE>\n";
	print "</HEAD>\n";
}
else {
	print "<meta http-equiv=Content-Type content=\"text/html; charset=$charset\">\n";
	print "<TITLE>$title</TITLE>\n";
	print "</HEAD>\n";
}
if(empty($pagehead)) {
	print "<BODY $bodyparam >\n";
	print "$pageheader\n";
}
else
	print "$pagehead";

print "<DIV dir=RTL><A HREF=javascript:openRulesWin()>$l10nstr[4]</A></DIV>\n";		/* forum rules */
print "<iframe name=trce src=$nulfrm?forum=$table frameborder=no scrolling=no height=2 width=10></iframe>\n";


$data = $_GET['data'];
if(empty($data))
     $data = $_COOKIE['data'];
$name = $_GET['name'];
if(empty($name))
     $name = $_COOKIE['name'];
$name = str_replace("\'", '', $name);	/* prevent SQL injection */
$msgread = $_COOKIE[$table];
$last = $_COOKIE['last'];
$msgread = explode(',', $msgread);

$larr = explode(',', $last);

//print "Name: $name<BR>\n";

$action = $_GET['action'];

function GetNewMessages($forum) {
	global $name;
	global $larr;

	foreach($larr as $val) {
		list($tbl, $t) = explode('=', $val);
		if($tbl == $forum) {
			list($d, $t) = explode(' ', $t);
			$query = "SELECT num FROM $forum WHERE date>'$d' OR (time>'$t' AND date='$d')";
			$result = mysql_query($query);
			if(!$result) {
				echo mysql_error();
				exit;
			}
			$n = mysql_num_rows($result);
			return $n;
		}
	}
	/* if we got here, we have no cookie for this forum */
	$query = "SELECT num FROM $forum";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	$n = mysql_num_rows($result);
	return $n;
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
	$data = $_GET['data'];
}

if(empty($table)) {	/* we do not have a table name so print forum selection... */
	$query = "SELECT forum, category, forum_title,manager,members FROM mainlist ORDER BY category ASC";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	print "<CENTER><H1 DIR=RTL>$title</H1>\n";
	print "<H1>$l10nstr[5]</H1>\n";		/* choose forum */
	print "<P dir=$langdir>\n";
	print "<TABLE dir=RTL border=1 cellpading=5><TR>\n";
	print "<TD><BIG><B>$l10nstr[6]<TD><BIG><B>$l10nstr[7]<TD><BIG>$l10nstr[8]<TD><BIG>$l10nstr[9]\n"; /* category,   forum, manager, new messages  */
	while($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$forum = $line['forum'];
		$category = $line['category'];
		$forum_title = $line['forum_title'];
		$manager = $line['manager'];
		$members = $line['members'];
		if(!empty($members) && ($name != 'admin'))
			continue;
		print "<TR>\n";
		print "<TD>$category\n";
		print "<TD><A HREF=$mainfile?forum=$forum&name=$name&data=$data>$forum_title</A>\n";
		$alias = GetAlias($manager);
		print "<TD>$alias\n";
		$new = GetNewMessages($forum);
		print "<TD>$new\n";
	}
	print "</TABLE>\n";
	print "</CENTER>\n";
	if(empty($name)) {
		print "<BR><BR><P dir=RTL>\n";
		print "<A HREF=$loginscript?forum=$table&url=$mainfile>$l10nstr[10]</A><BR>\n";	/* system registration */
		print "<BR>$l10nstr[11]\n";		/* must register to system in order to write messages */
		print "$l10nstr[12]\n";				/* reading is possible without registering */
	}
	else {
		print "<BR><BR><P dir=RTL>\n";
		ShowUserSex();
		print "<A HREF=$descscript?action=user&usrname=$name target=_blank>$name</A><BR> \n";
		print "<A HREF=$loginscript?forum=$table&url=$mainfile>$l10nstr[3]</A><BR>\n";		/* connect as other user */
	}
	print "</BODY>\n</HTML>\n";
	exit;
}

/* Get forum name from mainlist table */
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

function IsForumManager($user) {
	global $managers;

	if($user == 'admin')
		return 1;
	foreach($managers as $val) {
		$val = trim($val);
		if($val == $user)
			return 1;
	}
	return 0;
}

print "<CENTER><H1 dir=RTL>";
print "<A HREF=javascript:void(0) onclick=\"winOp();\">";
print "$forum_title</A></H1>\n";
if(!$pagehead) {
	print "</CENTER><P dir=RTL align=right><A HREF=$mainfile>$l10nstr[2]</A><CENTER>\n";		/* to forum list */
}
print "<TABLE dir=RTL width=80% border=0 cellpadding=0 bgcolor=lightblue><TR><TD>\n";
print "$l10nstr[13]: ";		/* managed by */

$alias = GetAlias($manager);
print "<A HREF=$descscript?action=user&usrname=$manager target=_blank>$alias</A>";
if($IsManager) {
	print "<TD><A HREF=$editscript?forum=$table&action=forumedit>$l10nstr[14]</A>\n";		/* edit forum details */
 	print "<TD><A HREF=$banscript?action=showlist&forum=$table>$l10nstr[15]</A>\n";		/* banned users */
	print "<TD><A HREF=$chainscript?forum=$table>$l10nstr[16]</A>\n";								/* message chain change */
}
print "</TABLE></CENTER><BR>\n";

print "<CENTER><DIV DIR=RTL>$tag_line</DIV></CENTER>\n";

function CheckPrivateMessages() {
	global $l10nstr;
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
function editable($user) {
	global $name;
	global $IsManager;

	if($name == 'admin')
		return 1;

	if($user == $name)
		return 1;

	return 0;
}

function deleteable($user, $num) {
	global $name;
	global $table;
	global $IsManager;

	if($name == 'admin')
		return 1;

	if($IsManager)
	  return 1;

	if($user != $name)
		return 0;	/* this is sure this user can't delete the message... */
	$query = "SELECT num FROM $table WHERE ancestor='$num'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	if(mysql_num_rows($result) == 0)
		return 1;	/* no responses to this message so it can be deleted */

	return 0;
}

function SetPages() {
	global $table;
	global $MsgPerPage;
	global $Pages;
	global $PageCounter;

	unset($Pages);
	$Pages[0] = 0;
	$query = "SELECT num FROM $table WHERE ancestor=0 ORDER BY date DESC, time DESC";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	$i = 0;
	$PageCounter = 1;
	while($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		if($i >= $MsgPerPage) {
			$Pages[$PageCounter] = $line['num'];
			$i = 0;
			$PageCounter++;
		}
		else
			$i++;
	}
	return $Pages;
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

function PrintMsg($f, $level) {
	global $l10nstr;
	global $table;
	global $FirstMsg;
	global $ThreadTop;
	global $ShowMsg;
	global $mainfile;
	global $editscript;
	global $descscript;
	global $banscript;
	global $scriptsurl;
	global $addfilescript;
	global $nulfrm;
	global $userfiles;
	global $MsgPerPage;
	global $msgread;
	global $IsManager;
	global $name;

	$query = "SELECT * FROM $table WHERE ancestor=$f";
	if(($f == 0) && ($FirstMsg > 0)) {
		$query .= " AND num<=$FirstMsg";
	}
	if($f == 0) {
		if($ThreadTop)
			$query .= " AND num=$ThreadTop";
		$query .= " ORDER BY lastmod DESC, date DESC, time DESC";
		$query .= " LIMIT $MsgPerPage";
		//print "$query<BR>\n";
	}
	else
		$query .= " ORDER BY date DESC, time DESC";

	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	if(mysql_num_rows($result) == 0)
		return;	/* end of recursive call */

	while($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$msgnum = $line['num'];
		$title = $line['title'];
		$user = $line['name'];
		$title = SpecialCodes($title);
		//print "<LI type=disc dir=RTL><BIG><A id=t$msgnum HREF=\"javascript:void(0)\" onclick=\"blocking('msg$msgnum', 't$msgnum')\">$title</A></BIG> - ";
		// search if message was read...
	/*	if($level == 0)
			print "<BR>\n";
		print "<LI type=disc dir=RTL>"; */
		if($level == 0)
			print "<TABLE width=100% height=$3px><TR><TD></TABLE>\n";

		print "<TABLE dir=RTL border=0 width=100% cellpadding=0 cellspacing=0>\n";
		print "<TR>\n";
		print "<TD width=$i%>\n";
		print "<TD dir=RTL>\n";
		if($level == 0)
			print "<UL><LI type=disk>";
		else
			print "<UL><LI type=circle>";
		if(array_search($msgnum, $msgread)) {
			print "<A id=t$msgnum HREF=$nulfrm?msg=$msgnum&forum=$table target=trce onclick=\"blocking('msg$msgnum', 't$msgnum')\"><FONT color=#ff0000>$title</FONT></A>";
		}
		else {
			print "<A id=t$msgnum HREF=$nulfrm?msg=$msgnum&forum=$table target=trce onclick=\"blocking('msg$msgnum', 't$msgnum')\">$title</A>";
		}
		print "<TD width=20%>\n";
		print "<A HREF=$descscript?action=user&usrname=$user&forum=$table target=_blank>";
		$alias = GetAlias($user);
		if(IsForumManager($user))
			print "<BIG><B><FONT color=blue>$alias</FONT></B></BIG></A>\n";
		else if($user == $name)
			print "<BIG><B><FONT color=black>$alias</FONT></B></BIG></A>\n";
		else {
			print "$alias</A>\n";
		}
		$date = $line['date'];
		$time = $line['time'];
		$datearr = explode('-', $date);
		$timearr = explode(':', $time);
		$datetimestr = GetTimeStr($timearr[0], $timearr[1], $timearr[2], $datearr[1], $datearr[2], $datearr[0]);
		
		// $date = "$datearr[2]/$datearr[1]/$datearr[0]";
		print "<TD width=25%>\n";
		print "<FONT dir=LTR>";
		print "$datetimestr</FONT></LI>\n";
		/* Put message contents as DIV that will be displayed when clicking on message */
		$contents = $line['contents'];
		$contents = SpecialCodes($contents);
		print "<TR>\n";
		$i = $level * 2;
		print "<TD width=$i%>\n";
		print "<TD bgcolor=#fff8ff colspan=3>\n";
		if($ShowMsg == $msgnum)
			print "<DIV id=msg$msgnum>";
		else
			print "<DIV class=para id=msg$msgnum>";
		print "$contents<BR>\n";
		$pic = $line['picture'];
		if(!empty($pic)) {
			$ext = GetExt($pic);
			if(IsImg($ext)) {
				$s = getimagesize("$userfiles/$pic");
				$w = $s[0];
				$h = $s[1];
				if($w > 100) {
					$ar = $h/$w;
					$h = 100*$ar;
					$w = 100;
				}
				print "<A HREF=$userfiles/$pic><IMG SRC=$userfiles/$pic width=$w height=$h border=0></A><BR><BR>\n";
			}
			else {
				print "<A HREF=$userfiles/$pic>";
				print "מצורף קובץ: ";
				print "$ext</A><BR>\n";
			}
		}
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

		print "<BR><A href=$editscript?forum=$table&action=reply&ancestor=$msgnum>$l10nstr[22]</A>\n";   /* response */
		if(editable($user)) {
			print "&nbsp; &nbsp;\n";
			print "<A href=$editscript?forum=$table&action=edit&num=$msgnum>$l10nstr[23]</A>\n";				/* edit message */
			print "&nbsp; &nbsp;\n";
			print "<A HREF=\"javascript:void(0)\" onclick=\"javascript:AddFile('$addfilescript?forum=$table&num=$msgnum');\">";
			print  "$l10nstr[24]";		/* add file to message */
			print "</A>\n";
		}
		if(deleteable($user, $msgnum)) {
			print "&nbsp; &nbsp;\n";
			print "<A href=$editscript?forum=$table&action=delete&num=$msgnum>$l10nstr[25]</A>\n";    /* delete message */
		}

		if($IsManager) {
			print "&nbsp; &nbsp;<A HREF=$banscript?forum=$table&user=$user>$l10nstr[26]</A>\n";		/* ban a user */
			print "&nbsp; &nbsp; &nbsp; $msgnum\n";
		}
		print "<BR><BR></DIV>\n";
		print "</TABLE>\n";
		/* spacing table */
		print "<TABLE width=100% height=$3px><TR><TD></TABLE>\n";
		/* recursive call */
		PrintMsg($msgnum, $level + 1);
	}
}


print "<P dir=RTL align=right><CENTER>\n";
print "<TABLE dir=RTL width=80% border=0 cellpadding=0 bgcolor=lightblue><TR><TD>\n";
if(!empty($name)) {
	ShowUserSex();

	print "<A HREF=$descscript?action=user&usrname=$name target=_blank>$name</A> \n";
	print "<TD>\n";
	print "<A HREF=$loginscript?forum=$table&url=$mainfile?forum=$table>$l10nstr[3]</A>\n";					/* connect as different user */
	print "<TD>\n";
	print "<A HREF=$privatemsg>";
	CheckPrivateMessages();
	print "<A>\n";
}
else {
	print "<A HREF=$loginscript?forum=$table&url=$mainfile?forum=$table>$l10nstr[27]</A>\n";			/* connect to system */
}
print "</TABLE></CENTER>\n";
print "<P dir=RTL align=right>\n";
if(!empty($name)) {
	print "<A HREF=$editscript?forum=$table&ancestor=0>$l10nstr[28]</A></BR>\n";			/* new message */
}

$FirstMsg = $_GET['start'];
$ShowMsg = $_GET['show'];
if($ShowMsg)	/* we have to show a specific message */
	$ThreadTop = GetThreadTop($ShowMsg);

PrintMsg(0, 0);

$Pages = SetPages();
$pgnum = array_search($FirstMsg, $Pages);

print "<BR><CENTER>";
print "<TABLE dir=RTL width=80% border=0 cellpadding=0 bgcolor=lightblue><TR>\n";
if($ShowMsg) {
	print "<TD align center><A HREF=$mainfile?forum=$table</A>$l10nstr[29]</A>\n";			/* general display */
}
else {
	if($pgnum > 0) {
		$prev = $Pages[$pgnum - 1];
		print "<TD><A HREF=$mainfile?forum=$table&strart=$prev>&lt;&lt; $l10nstr[30]</A>\n";		/* previouse page */
	}
	$i = 1;
	foreach($Pages as $page) {
		if($page == 0)
			continue;	/* skip page 0 */
		print "<TD>";
		if($i == $pgnum)
			print "<BIG>";
		print "<A HREF=$mainfile?forum=$table&start=$page>$i</A>\n";
		$i++;
	}
	$next = $Pages[$pgnum + 1];
	if($next)
		print "<TD><A HREF=$mainfile?forum=$table&start=$next>&gt;&gt; $l10nstr[31]</A>\n";		/* next page */
}
print "</TABLE>\n";

if(!empty($pgbottom))
	print $pgbottom;
else {
	print "<HR>\n<DIV dir=RTL>\n";
	print "$l10nstr[32]";
	print "<A HREF=http://www.helicontech.co.il/fakt.html>FAKT</A>\n";
	print "<P dir=LTR>$VerId\n";
	print "</DIV>\n";
	print "</BODY>\n</HTML>\n";
}
?>
