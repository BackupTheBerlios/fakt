<?PHP
/*
 | Common functions for FAKT
 */

/*
 | Get email from login table for a given name
 */
function GetEmail($name) {
	$query = "SELECT email FROM login WHERE name='$name'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	$email = $line['email'];
	return $email;
}

function ErrorReport($errstr) {
	global $l10nstr;
	global $charset;

	if(!headers_sent()) {
		print "<HTML>\n";
		print "<HEAD>\n";
		print "<meta http-equiv=Content-Type content=\"text/html; charset=$charset\">\n";
		print "<TITLE>שגיאה</TITLE>\n";
		print "</HEAD>\n";
		print "<BODY>\n";
	}
	$err = explode("\n", $errstr);

	print "<CENTER>\n";
	$i = 0;
	foreach($err as $val) {
		if($i == 0)		/* first line */
			print "<H1>$val</H1>\n";
		else if($i == 1) {
			print "<TABLE border=0><TR><TD>\n";
			print "$val<BR>\n";
		}
		else
			print "$val<BR>\n";
		$i++;
	}
	if($i > 1)
		print "</TABLE>\n";
	print "<BR><H2>$l10nstr[105]</H2>";		/* click back to return and correct the error */
	exit;
}


/*
 | Replace special codes in messages
 */
function SpecialCodes($str) {
	global $emoticonsurl;

	$str = str_replace('<', '&lt;', $str);
	$str = str_replace('|÷??|', '<P align=left dir=LTR><FONT face=Courier>', $str);
	$str = str_replace('|code|', '<P align=left dir=LTR><FONT face=Courier>', $str);

	$str = str_replace('|?÷??|', '</FONT></P>', $str);
	$str = str_replace('|ecode|', '</FONT></P>', $str);
	$str = str_replace('|B|', '<B>', $str);
	$str = str_replace('|EB|', '</B>', $str);
	$str = str_replace("\n", "<BR>", $str);

	preg_match_all("/~[^\x20|^~]*~/", $str, $arr);
	foreach($arr[0] as $val) {
		$name = str_replace('~', '', $val);
		$str = str_replace($val, "<IMG SRC=$emoticonsurl/$name.gif border=0>", $str);
	}
	return $str;
}

function GetAlias($user) {
	global $l10nstr;
	
	if($user == 'admin')
		return "$l10nstr[106]";			/* forums admin */
	else
		return $user;
}

function ShowUserSex() {
	global $l10nstr;
	global $name;

	$query = "SELECT sex FROM login WHERE name='$name'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	if($line['sex'] == 'female')
		print "$l10nstr[17]: ";				/* you are connected as (female) */
	else
		print "$l10nstr[18]: ";				/* you are connected as (male) */
}

function GetTimeStr($hour, $min, $sec, $month, $day, $year) {
	global $timeoffset;

	
	$time = mktime($hour, $min, $sec, $month, $day, $year, 0);
	$time += ($timeoffset * 3600);	/* add time offset in seconds */
	
	$str = date("d/m/Y  H:i:s", $time);
	return $str;
}

?>
