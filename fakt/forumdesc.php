<?PHP
header('Content-type: text/html;charset=UTF-8');
?>
<HTML>
<HEAD>
<?PHP
/*
  | Show forum description script of Esc Forums system
  | This script will show forum description or user details if action=user
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
include('common.inc');
include('l10n.inc');
include('glob.inc');

function LastConTime($user) {
	$query = "SELECT DATE_FORMAT(last, '%d-%m-%y %H:%I') FROM usrtrack WHERE user='$user' ";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	$line = mysql_fetch_array($result, MYSQL_NUM);
	$last = $line[0];
	return $last;
}

function Formatmysqldate($datestr) {
	if(empty($datestr))
		return $datestr;

	list ($day, $month, $year) = split ('[/.-]', $datestr);
	if($year == 0)
		return $datestr;

	if(!checkdate($month, $day, $year)) {
		ErrorReport("$l10nstr[137]: $datestr");		/* invalid date */
		exit;
	}
	return "$year-$month-$day";
}

$link = mysql_connect($host, $user, $pswd) or die("Could not connect to host $host");
mysql_select_db($database) or die("Could not select database: $database");

$action = $_GET['action'];
if($action == 'user') {
	$title = "$l10nstr[138]";
	$data = $_GET['data'];
	if(empty($data))
		$data = $_COOKIE['data'];
	$name = $_GET['name'];
	if(empty($name))
		$name = $_COOKIE['name'];
	$query = "SELECT lastonline FROM login WHERE name='$name'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	if($line['lastonline'] != $data)
		unset($name);
}

$table = $_GET['forum'];

$query = "SELECT * FROM mainlist WHERE forum='$table'";
$result = mysql_query($query);
if(!$result) {
	echo mysql_error();
	exit;
}
$line = mysql_fetch_array($result, MYSQL_ASSOC);
if(!empty($line['pghead']))
	$pageheader = $line['pghead'];
$pgbottom = $line['pgbottom'];

print "<meta http-equiv=Content-Type content=\"text/html; charset=$charset\">\n";
print "<TITLE>$title</TITLE>\n";
print "<STYLE>\n";
print "body {margin:0 font-size:14px; font-family: arial, sans-serif; color=navy}\n";
print "</STYLE>\n";

print "</HEAD>\n";

if($action) {
	if(empty($pageheader))
		print "<BODY $bodyparam >\n";
	print "$pageheader\n";
}

if($action == 'update') {
	$user = $_POST['name'];
	$password = $_POST['password'];
	$passval = $_POST['passval'];
	$fullname = $_POST['fullname'];
	$email = $_POST['email'];
	$pubemail = $_POST['pubemail'];
	$birthdate = Formatmysqldate($_POST['birthdate']);
	$sex = $_POST['sex'];
	$martial = $_POST['martial'];
	$web = $_POST['web'];
	$messangernum = $_POST['messangernum'];
	$messangersoft = $_POST['messangersoft'];
	$occupation = $_POST['occupation'];
	$interest = $_POST['interest'];
	$signature = $_POST['signature'];
	$comments = $_POST['comments'];
	$query = "UPDATE login SET password='$password', fullname='$fullname', email='$email', pubemail='$pubemail', ";
	$query .= "web='$web', messangernum='$messangernum', messangersoft='$messangersoft', birthdate='$birthdate', sex='$sex', ";
	$query .= "martial='$martial', occupation='$occupation', interest='$interest', signature='$signature', comments='$comments' ";
	$query .= "WHERE name='$user' ";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	print "<CENTER><H1>$l10nstr[139]</H1>\n";

	print "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"2; URL=$descscript?action=user&usrname=$name\">\n";
}
if($action == 'picupdate') {
	$user = $_GET['name'];

	$size = (int)$_FILES['userfile']['size'];
	if($size > 0) {
  		$tmpname = $_FILES['userfile']['tmp_name'];
  		//$name = urlencode($_FILES['userfile']['name']);
		$picname = $_FILES['userfile']['name'];
  		$picname = urlencode($picname);
  		move_uploaded_file($tmpname, "$userfilesdir/$picname");
	}
	else {
		ErrorReport("$l10nstr[140]");	/* error getting picture, picture is probably too big */
		exit;
	}
	print "name: $picname<BR>\n";
	$query = "UPDATE login SET picture='$picname' WHERE name='$user'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	print "<CENTER><H1>$l10nstr[141]</H1>\n";		/* picture sent succesfully */
	print "<IMG SRC=$userfiles/$picname>";
	print "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"4; URL=$descscript?action=user&usrname=$name\">\n";
}

if($action == 'user') {
	$user = $_GET['usrname'];
	$query = "SELECT * FROM login WHERE name='$user'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	print "<CENTER><H1 dir=RTL>$l10nstr[138]: ";
	print "$user</H1>\n";
	print "<BR><P dir=RTL>\n";
	print "<TABLE dir=RTL border=8><TR><TD>\n";
	$fullname = $line['fullname'];
	$password = $line['password'];
	print "$l10nstr[83]: ";		/* full name */
	print "$fullname<BR>\n";
	$email = $line['email'];
	$pubemail = $line['pubemail'];
	if(!empty($pubemail)) {
		print "$l10nstr[84]: ";	/* email */
		print "<A href=mailto:$pubemail>$pubemail</A><BR>\n";
	}
	$birthdate = $line['birthdate'];
	list($year, $month, $day) = split('[/.-]', $birthdate);
	if(($day > 0) && ($year > 0)) {
		print "$l10nstr[86]: ";
		print "$day-$month-$year<BR>\n";
	}
	$sex = $line['sex'];
	if(!empty($sex)) {
		print "$l10nstr[87]: ";	/* sex */
		if($sex == 'female')
			print "$l10nstr[88]"; /* female */
		else
			print "$l10nstr[89]"; /* male */
		print "<BR>\n";
	}
	$martial = $line['martial'];
	if(!empty($martial)) {
		print "$l10nstr[90]: ";
		print "$martial<BR>\n";
	}
	$web = $line['web'];
	if($web != 'http://') {
		print "$l10nstr[99]: ";	/* web site */
		print "<A href=$web>$web</A><BR>\n";
	}
	$messangersoft = $line['messangersoft'];
	if(!empty($messangersoft)) {
		print "$l10nstr[101]: ";
		print "$messangersoft<BR>\n";
	}
	$messangernum = $line['messangernum'];
	if(!empty($messangernum)) {
		print "$l10nstr[100]: ";		/* instant messanger number */
		print "$messangernum<BR>\n";
	}
	if(!strcasecmp($messangersoft,  'ICQ'))
		print "<img src=http://web.icq.com/whitepages/online?icq=$messangernum&img=2><BR>\n";
	$occupation = $line['occupation'];
	if(!empty($occupation)) {
		print "$l10nstr[102]: ";			/* occupation */
		print "$occupation<BR>\n";
	}
	$interest = $line['interest'];
	if(!empty($interest)) {
		print "$l10nstr[103]: ";			/* interests */
		print "$interest<BR>\n";
	}
	$comments = $line['comments'];
	if(!empty($comments)) {
		$commentstr = str_replace("\n", "<BR>\n", $comments);
		print "<BR>$commentstr<BR>\n";
	}
	$signature = $line['signature'];
	if(!empty($signature)) {
		$signaturestr = str_replace("\n", "<BR>\n", $signature);
		print "<BR>$l10nstr[142]: <BR>\n";		/* signature */
		print "$signaturestr<BR>\n";
	}
	print "$l10nstr[143]: ";		/* last connection time */
	$last = LastConTime($user);
	print "$last<BR>\n";
	print "<TD>\n";
	$picture = $line['picture'];
//	print "$picture<BR>\n";
	if(!empty($picture)) {
		print "<BR><CENTER><IMG SRC=$userfiles/$picture width=100 height=115></CENTER><BR>\n";
	}
	print "</TABLE>\n";
	if($user != $name) { 
		print "<H3 dir=RTL><A HREF=$privatemsg?action=new&to=$user&forum=$table>שלח מסר</A></H3>\n";
	}
	if(($user == $name) || ($name == 'admin')) {	// edit user detailes
		if($name != 'admin') {
			print "<H3 dir=$langdir><A HREF=$privatemsg>$l10nstr[144]</A></H3>\n"; 		/* show private msg */
		}
		print "<BR><HR><BR>\n";
		print "<CENTER>\n";
		// enctype=\"multipart/form-data\"
		print "<FORM action=$descscript?action=update method=post>\n";
		print "<TABLE border=8 DIR=RTL>\n";
		print "<TR><TD colspan=2>\n";
		print "<CENTER>\n<BIG><B>$l10nstr[145]</B></BIG>\n</CENTER>\n";
		print "<SMALL>$l10nstr[81]</SMALL>\n";	/* fields marked with * are mandatory */
		print "<INPUT type=hidden name=name value=$user>\n";
		print "<TR><TD>\n";
		print "$l10nstr[78]:";	/* passowrd */
		print "\n<TD><INPUT type=password name=password value=$password> *\n";
		print "<TR><TD>$l10nstr[82]: \n";		/* retype password */
		print "<TD><INPUT type=password name=passval value=$password> *\n";
		print "<TR><TD>$l10nstr[83]: \n";
		$tmpfullname = htmlspecialchars($fullname);
		print "<TD><INPUT type=text name=fullname value=\"$tmpfullname\">\n";
		print "<TR><TD>$l10nstr[84]: \n";		/* email */
		print "<TD><INPUT type=text name=email size=30 value=$email> *\n";
		print "<TR><TD>$l10nstr[85]: \n";		/* display email */
		print "<TD><INPUT type=text name=pubemail size=30 value=\"$pubemail\">\n";
		print "<TR><TD>$l10nstr[86]: \n";		/* birth date */
		print "<TD><INPUT type=text name=birthdate value=$day-$month-$year>\n";
		print "<TR><TD>$l10nstr[87]: \n";		/* sex */
		print "<TD><SELECT name=sex>\n";
		if($sex == 'female') {
			print "<OPTION SELECTED value=female>$l10nstr[88]\n";		/* female */
			print "<OPTION value=male>$l10nstr[89]\n";							/* male */
		}
		else {
			print "<OPTION value=female>$l10nstr[88]\n";
			print "<OPTION SELECTED value=male>$l10nstr[89]\n";
		}
		print "</SELECT>\n";
		print "<TR><TD>$l10nstr[91]: \n";
		print "<TD><SELECT name=martial>\n";
		print "<OPTION value=\"\">$l10nstr[91]\n";
		print "<OPTION value=\"$l10nstr[92]\">$l10nstr[92]\n";			/* single */
		print "<OPTION value=\"$l10nstr[93]\">$l10nstr[93]\n";			/* married */
		print "<OPTION value=\"$l10nstr[94]\">$l10nstr[94]\n";			/* divorced */
		print "<OPTION value=\"$l10nstr[95]\">$l10nstr[95]\n";			/* seperated */
		print "<OPTION value=\"$l10nstr[96]\">$l10nstr[96]\n";			/* single parent */
		print "<OPTION value=\"$l10nstr[97]\">$l10nstr[97]\n";			/* married parent */
		print "<OPTION value=\"$l10nstr[98]\">$l10nstr[98]\n";			/* divorced parent */
		print "</SELECT>\n";
		print "<TR><TD>$l10nstr[99]: \n";		/* web site */
		print "<TD><INPUT type=text name=web size=30 value=\"$web\">\n";
		print "<TR><TD>$l10nstr[100]: \n";		/* instant messaging number */
		print "<TD><INPUT type=text name=messangernum value=\"$messangernum\">\n";
		print "<TR><TD>$l10nstr[101]: \n";		/* instant messaging software */

		print "<TD><INPUT type=text name=messangersoft value=\"$messangersoft\">\n";
		print "<TR><TD>$l10nstr[102]: \n";		/* occupation */
		print "<TD><INPUT type=text name=occupation value=\"$occupation\">\n";
		print "<TR><TD>$l10nstr[103]: \n";		/* intetests */
		print "<TD><INPUT type=text name=interest value=\"$interest\">\n";
		/* print "<TR><TD>\nï?œï?œ \n";
		print "<TD><input type=hidden name=\"MAX_FILE_SIZE\" value=\"40000\">\n";
		print "<INPUT type=file size=30 name=picture>\n"; */
		print "<TR><TD>$l10nstr[104]: \n";		/* comments */
		print "<TD><TEXTAREA name=comments cols=30 rows=5>$comments</TEXTAREA>\n";
		print "<TR><TD>$l10nstr[142]: \n";
		print "<TD><TEXTAREA name=signature cols=30 rows=5>$signature</TEXTAREA>\n";
		print "<TR><TD colspan=2 align=center>\n";
		print "<INPUT type=submit value=עדכן>\n</TABLE>\n</FORM>\n";
		print "<BR><BR>\n";
		print "<FORM enctype=multipart/form-data action=$descscript?action=picupdate&name=$user method=post>\n";
		print "<TABLE dir=RTL border=8><TR><TD colspan=2 align=center><BIG><B>\n";
		print "$l10nstr[146]";		/* update picture */
		print "\n<TR><TD>$l10nstr[147]: \n";
		print "<TD><input type=hidden name=\"MAX_FILE_SIZE\" value=100000>\n";
		print "<input type=file name=userfile size=40>\n";
		print "<TR><TD colspan=2 align=center>\n";
		print "<input type=submit value=\"$l10nstr[51]\">\n";
		print "</TABLE>\n</FORM>\n";
	}
	print "</BODY>\n</HTML>\n";
	exit;
}

$forum = $_GET['forum'];
$query = "SELECT forum_title, description FROM mainlist WHERE forum='$forum'";
$result = mysql_query($query);
if(!$result) {
	echo mysql_error();
	exit;
}
$line = mysql_fetch_array($result, MYSQL_ASSOC);
$description = $line['description'];
$forum_title = $line['forum_title'];
print "<FONT color=navy>\n";
print "<CENTER><H1 dir=$langdir>$forum_title</H1></CENTER>\n";
print "<P dir=$langdir>\n";
$description = str_replace("\n", "<BR>\n", $description);
print "$description\n";

print "<BR><CENTER>\n";
print "<INPUT type=button value=\"$l10nstr[74]\" onclick=javascript:window.close();>\n";

?>
</BODY>
</HTML>
