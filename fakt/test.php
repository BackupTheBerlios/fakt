<HTML>
<HEAD>
<TITLE>Test of new messages</TITLE>
</HEAD>
<BODY>
<?PHP
include('config.inc');
include('glob.inc');
include('common.inc');


/* Test of getting new messages by comparing timestamp field of user track table to date and time fields
in the forum */
$name = $_GET['name'];
if(empty($name))
     $name = $_COOKIE['name'];
$name = str_replace("\'", '', $name);	/* prevent SQL injection */
$msgread = $_COOKIE[$table];
$last = $_COOKIE['last'];
$msgread = explode(',', $msgread);

$larr = explode(',', $last);

$link = mysql_connect($host, $user, $pswd) or die("Could not connect to host $host");
mysql_select_db($database) or die("Could not select database: $database");

if(empty($name)) {
	print "You are not logged in<BR>\n";
	exit;
}

function GetNewMessages($forum) {
	global $name;
	global $larr;

	foreach($larr as $val) {
		list($tbl, $t) = explode('=', $val);
		print "tbl: $tbl, t: $t<BR>\n";
		if($tbl == $forum) {
			list($d, $t) = explode(' ', $t);
			print "Date: $d, Time: $t<BR>\n";
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

$forum = $_GET['forum'];
if(empty($forum))
	$forum = 'demo';
$new = GetNewMessages($forum);

print "Forum: $forum<BR>\n";
print "New: $new<BR>\n";

print "Last: $last<BR>\n";