<?PHP
header('Content-type: text/html;charset=UTF-8');
?>
<?PHP
/*
  | Login script of FAKT system formerly YAFS (Yet Another Forums System)
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
include('l10n.inc');
include('config.inc.php');
include('glob.inc');
include('common.inc');

$ilegalchars = array(0, 32, 34, 39, 96);

/*
 | For some unknown reason I cound not find if a name contains ',' or ' '
 | in a string using functions strstr and strpos
 | so I wrote this function
 */
function LegalUserName($name) {
	global $ilegalchars;

	$len = strlen($name);

	for($i = 0; $i < $len; $i++) {
		$n = ord($name[$i]);
		$r = array_search($n, $ilegalchars);
		if($r) {
			print "r: $r<BR>\n";
			return 0;
		}
	}
	return 1;
}

function ValidEmail($email) {

	$r = strpos($email, '@');
	if(!$r)
		return 0;
	$r = strpos($email, '.');
	if(!$r)
		return 0;
	return 1;
}

function Formatmysqldate($datestr) {
	if(empty($datestr))
		return $datestr;

	list ($day, $month, $year) = split ('[/.-]', $datestr);

	if(!checkdate($month, $day, $year)) {
		ErrorReport("Invalid date: $datestr");
		exit;
	}
	return "$year-$month-$day";
}

function PageHeader() {
	global $base;
	global $l10nstr;
	global $charset;

	if(!headers_sent()) {
		print "<HTML>\n<HEAD>\n";
		print "<meta http-equiv=Content-Type content=\"text/html; charset=$charset\">\n";
    	print "<TITLE>$l10nstr[61]</TITLE>\n";
		print "<STYLE>\n";
		print "body {margin:0 font-size:14px; font-family: arial, sans-serif; }\n";
		print "</STYLE>\n";

		if(!empty($base)) {
			print "<BASE HREF=$base>\n";
		}
		print "<SCRIPT language=javascript>\n";
		print "var The_Win\n";

		print "function winOp(url){\n";

		print "\tThe_Win=window.open(url,\"Description\",\"height=130,width=350,resizable=no\");\n";
		print "}\n";
		print "</SCRIPT>\n";
	}
}

/*
 | Get forum specific page header
 */
function GetPageHeader($forum) {
	$query = "SELECT pghead FROM mainlist WHERE forum='$forum'";
	$result = mysql_query($query);
	if(!$result) {
		ErrorReport("$l10nstr[62]");		/* error in database query */
		echo mysql_error();
		mysql_close($link);
		exit;
	}
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	return $line['pghead'];
}

function GetPageBottom($forum) {
	$query = "SELECT pgbottom FROM mainlist WHERE forum='$forum'";
	$result = mysql_query($query);
	if(!$result) {
		ErrorReport("$l10nstr[62]");		/* error in database query */
		echo mysql_error();
		mysql_close($link);
		exit;
	}
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	return $line['pgbottom'];
}

$link = mysql_connect($host, $user, $pswd) or die("Could not connect to host $host");
mysql_select_db($database) or die("Could not select database: $database");

$action = $_GET['action'];
$forum = $_GET['forum'];

if($action == 'login') {
	$newname = $_POST['name'];
	$newpassword = $_POST['password'];

	if(LegalUserName($newname) == 0) {
		ErrorReport("$l10nstr[62]");		/* user name may not contain punctuation and spaces */
		exit;
	}

	$query = "SELECT name, password FROM login WHERE name='$newname'";
	$result = mysql_query($query);
	if(!$result) {
		ErrorReport("$l10nstr[62]");		/* error in database query */
		echo mysql_error();
		mysql_close($link);
		exit;
	}
	$num = mysql_num_rows($result);
	if(!$num) {
		ErrorReport("$l10nstr[64]");		/* name does not exist in system */
		mysql_close($link);
		exit;
	}
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	if($line['password'] != $newpassword) {
		ErrorReport("$l10nstr[65]");
		mysql_close($link);
		exit;
	}
	/* if we got here, we found the right name */
	/* first update the current time in lastonline column */
	$query = "UPDATE login SET lastonline=now() WHERE name='$newname'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		mysql_close($link);
		exit;
	}
	/* now get this time back */
	$query = "SELECT lastonline FROM login WHERE name='$newname'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		mysql_close($link);
		exit;
	}
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	$lastonline = $line['lastonline'];
	$location = $_GET['url'];
	/* first delete the cookies if we have one */
	//setcookie('name', "", time() - 3600);
	//setcookie('data', "", time() - 3600);
	$r = setcookie('name', $newname, time() + 60*60*24*30*12);	/* expire in 1 year */
	if(!$r) {
		print "<BR>Set cookie failed<BR>\n";
		exit;
	}
	setcookie('data', $lastonline, time() + 60*60*24*30*12);
	PageHeader();

	$n = strpos($location, "?");
	if($n)
		$switch = '&';
	else
		$switch = '?';

	print "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=$location${switch}name=$newname&data=$lastonline\">\n";
	print "</HEAD>\n<BODY>\n";
	print "<P dir=RTL>\n";
	print "<BIG><B>$l10nstr[66]\n<BR>";
	print "$l10nstr[67]";
	//print "<BR>URL: $url<BR>\n";
	print "<BR><BR>\n";

//	header("Location: $location&name=$newname&data=$lastonline");
	exit;
}

PageHeader();

if(!empty($forum)) {
	$pghead = GetPageHeader($forum);
}

print "</HEAD>\n";
if($pghead)
	print "$pghead\n";
else
	print "<BODY $bodyparam>\n";

if($action == 'new') {
	$name = $_POST['name'];
	$fullname = $_POST['fullname'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$passval = $_POST['passval'];
	if(empty($password) || empty($name)) {
		ErrorReport("$l10nstr[68]");		/* must fill name and password fields */
		exit;
	}
	if(LegalUserName($name) == 0) {
		ErrorReport("$l10nstr[62]");
		exit;
	}
	if($password != $passval) {
		ErrorReport("$l10nstr[69]");		/* passwords are not equal */
		exit;
	}
	if(!ValidEmail($email)) {
		ErrorReport("$l10nstr[70]");		/* invalid email */
		exit;
	}

	$query = "SELECT name FROM login WHERE name='$name'";
	$result = mysql_query($query);

	if(!$result) {
		ErrorReport("$l10nstr[63]");
		mysql_close($link);
		exit;
	}
	$result = mysql_num_rows($result);
	if($result) {
		ErrorReport("$l10nstr[71]");
		mysql_close($link);
		exit;
	}

	$pubemail = $_POST['pubemail'];
	$web = $_POST['web'];
	$messangernum = $_POST['messangernum'];
	$messangersoft = $_POST['messangersoft'];
	$birthdate = Formatmysqldate($_POST['birthdate']);
	$sex = $_POST['sex'];
	$martial = $_POST['martial'];
	$occupation = $_POST['occupation'];
	$ineterest = $_POST['interest'];
	$signature = $_POST['signature'];
	$comments = $_POST['comments'];

	// $picture = '';
	$query = "INSERT INTO login VALUES('$name', '$fullname', '$email', '$password', NULL, ";
	$query .= " '$pubemail', '$web', '$messangernum', '$messangersoft', '$birthdate', '$sex', '$martial', '$occupation', ";
	$query .= " '$interest', '$signature', '$comments', '$picture')";

	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error() . "<BR>";
		mysql_close($link);
		exit();
	}
	mysql_close($link);
}

if($action == 'forgot') {

	print "</HEAD>\n";
	print "<BODY>\n";

	print "<P dir=RTL>\n";
	print "<CENTER>\n";
	print "$l10nstr[72]";			/* pleae fill your email */
	print "\n";
	print "<FORM action=$loginscript?action=sendpasswd method=post>\n";
	print "<INPUT name=email size=40><BR>\n";
	print "<INPUT type=submit value=\"$l10nstr[51]\">\n";		/* send */
	exit;
}

if($action == 'sendpasswd') {
	$email = $_POST['email'];

	print "</HEAD>\n";
	print "<BODY>\n";

	print "<P dir=RTL>\n";
	print "<CENTER>\n";

	$query = "SELECT name,password FROM login WHERE email='$email'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	if(mysql_num_rows($result) == 0) {
		print "$l10nstr[73]";
		print "\n<BR><BR>\n";
		print "<INPUT type= button value=\"$l10nstr[74]\", onclick=javascript:window.close();>\n";
		exit;
	}
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	$name = $line['name'];
	$passwd = $line['password'];
	$contents = "Name: $name\nPassword: $passwd\n";
	mail($email, "FAKT forums system password", $contents,
		"From: $webmasteremail\r\n"
		."X-Mailer: PHP/" . phpversion());
	print "$l10nstr[75]";			/* name and password sent to email */
	print "\n<BR><BR>\n";
	print "<INPUT type= button value=\"$l10nstr[74]\", onclick=javascript:window.close();>\n";		/* close window */
	exit;
}
?>
<!-- In case no option is given, print forms -->
<P DIR=RTL>
<?PHP
if(!$pghead) {
	print "<H1 dir=RTL>";
	print "מערכת FAKT";
	print "</H1><BR>\n";
}
?>

<CENTER>
<FONT color=navy>
במידה ואינך רשומ\ה לפורום, אנא גלול למטה והכנס את הפרטים בחלקו התחתון של הדף.<BR>
לאחר ההרשמה, יוצג דף זה פעם נוספת, עם השם והסיסמה מלאים, יש ללחוץ על שלח לכניסה.<BR>
</FONT>
<?PHP
print "<H1>$l10nstr[61]</H1>\n";		/* login to system */

$location = $_GET['url'];
print "<FORM name=existing action=$loginscript?action=login&url=$location method=post>\n";
?>
<TABLE border=8 DIR=RTL>
<TR><TD colspan=2 align=center>
<?PHP print "<BIG><B>$l10nstr[76]</B></BIG>\n";		/* existing user */	 ?>
<TR><TD>
<?PHP print "$l10nstr[77]: ";		/* name */ ?>
<TD>
<?PHP
	print "<INPUT type=text name=name value=$name>\n";
?>
<TR><TD>
<?PHP print "$l10nstr[78]: ";		/* password */ ?>
<TD>
<?PHP
	print "<INPUT type=password name=password value=$password>\n";
?>
<TR><TD colspan=2 align=center>
<?PHP print "<INPUT type=submit value=$l10nstr[51]>\n";		/* send */  ?>
</TABLE>
</FORM>
<?PHP
	print "<BR><A HREF=javascript:void(0) onclick=javascript:winOp(\"$loginscript?action=forgot\")>";
	print "$l10nstr[79]";
	print "</A><BR>\n";
?>
<BR><HR><BR>
<?PHP
	print "<FORM name=new action=$loginscript?action=new&url=$location method=post>\n";
?>
<TABLE border=8 DIR=RTL>
<TR><TD colspan=2>
<CENTER>
<?PHP print "<BIG><B>$l10nstr[80]</B></BIG>\n"; ?>
</CENTER>
<?PHP print "<SMALL>$l10nstr[81]</SMALL>\n"; ?>
<TR><TD>
<?PHP print "$l10nstr[77]: ";		/* name */ ?>
<TD>
<INPUT type=text name=name> *
<TR><TD>
<?PHP print "$l10nstr[78]: ";		/* password */ ?>
<TD>
<INPUT type=password name=password> *
<TR><TD>
<?PHP print "$l10nstr[82]: ";		/* retype password */ ?>
<TD>
<INPUT type=password name=passval> *
<TR><TD>
<?PHP print "$l10nstr[83]: \n";		/* full name */ ?>
<TD>
<INPUT type=text name=fullname>
<TR><TD>
<?PHP print "$l10nstr[84]: \n";		/* email */ ?>

<TD>
<INPUT type=text name=email size=30> *
<TR><TD>
<?PHP print "$l10nstr[85]: \n";		/* display email */ ?>

<TD>
<INPUT type=text name=pubemail size=30>
<TR><TD>
<?PHP print "$l10nstr[86]: \n";	?>

<TD><INPUT type=text name=birthdate>
<TR><TD>
<?PHP print "$l10nstr[87]: \n";	/* sex */ ?>

<TD><SELECT name=sex>
<?PHP
	print "<OPTION value=female>$l10nstr[88]\n";		/* female */
	print "<OPTION value=male>$l10nstr[89]\n";		/* male */
	print "</SELECT>\n";
	print "<TR><TD>\n";
	print "$l10nstr[90]: \n";				/* family status */
	print "<TD><SELECT name=martial>\n";
	print "<OPTION value=\"\">$l10nstr[91]\n";			/* choose martial status */
	print "<OPTION value=\"$l10nstr[92]\">$l10nstr[92]\n";			/* single */
	print "<OPTION value=\"$l10nstr[93]\">$l10nstr[93]\n";			/* married */
	print "<OPTION value=\"$l10nstr[94]\">$l10nstr[94]\n";			/* divorced */
	print "<OPTION value=\"$l10nstr[95]\">$l10nstr[95]\n";			/* seperated */
	print "<OPTION value=\"$l10nstr[96]\">$l10nstr[96]\n";			/* single parent */
	print "<OPTION value=\"$l10nstr[97]\">$l10nstr[97]\n";			/* married parent */
	print "<OPTION value=\"$l10nstr[98]\">$l10nstr[98]\n";			/* divorced parent */
	print "</SELECT>\n";
	print "<TR><TD>\n";
	print "$l10nstr[99]: \n";		/* web site */
	print "<TD><INPUT type=text name=web size=30 value=http://>\n";
	print "<TR><TD>\n";
	print "$l10nstr[100]\n";		/* instant messaging number */
	print "<TD><INPUT type=text name=messangernum>\n";
	print "<TR><TD>\n";
	print "$l10nstr[101]: ";			/* instant messaging software */
	print "<TD><INPUT type=text name=messangersoft>\n";
	print "<TR><TD>\n";
	print "$l10nstr[102]: \n";		/* profession */
	print "<TD><INPUT type=text name=occupation>\n";
	print "<TR><TD>\n";
	print "$l10nstr[103]: \n";		/* intetests */
	print "<TD><INPUT type=text name=interest>\n";
	print "<TR><TD>$l10nstr[104]: \n";
	print "<TD><TEXTAREA name=comments cols=30 rows=5></TEXTAREA>\n";
	print "<TR><TD colspan=2 align=center>\n";
	print "<INPUT type=submit value=$l10nstr[51]>\n";		/* send */
?>
</TABLE>
</FORM>
<?PHP
if($forum) {
	$pgbottom = GetPageBottom($forum);
}
if($pgbottom)
	print "$pgbottom\n";
else
	print "</BODY>\n</HTML>\n";
?>
