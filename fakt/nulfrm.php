<?PHP
/*
 | Null frame for forum messages
 | mark messages as read
 | for standard messages, send a cookie, for private messages, set flag
 | update user record in user tracking table
 */
include('config.inc.php');
include('glob.inc');

$link = mysql_connect($host, $user, $pswd) or die("Could not connect to host $host");
mysql_select_db($database) or die("Could not select database: $database");

$msg = (int)$_GET['msg'];
$forum = $_GET['forum'];
$read = $_COOKIE[$forum];
$name = $_COOKIE['name'];
$last = $_COOKIE['last'];

$action = $_GET['action'];

if($action == 'delcookie') {
	setcookie($forum, '', time() + 60*60*24*30);
	setcookie('last', '', time() + 60*60*24*30);
	exit;
}

if($forum == 'msg') { /* this is not a forum, this is private messaging system */
	$query = "UPDATE msg SET flags=1 WHERE num='$msg'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	exit;
}
if($msg) {
	$done = 0;
	$r = explode(',', $read);
	foreach($r as $v) {
		if($v == $msg)
			$done = 1;
	}
	if(!$done)
		$read .= ",$msg";
	setcookie($forum, $read, time() + 60*60*24*30);	/* one month */

	/* update last action time for this forum */
	$result = mysql_query("SELECT NOW()");	/* get mysql time now */
	$n = mysql_fetch_array($result, MYSQL_NUM);
	$n = $n[0];
	$done = 0;
	if(empty($last))
		$last = "$forum=$n,";
	else {
		$larr = explode(',', $last);
		$last = "";
		foreach($larr as $val) {
			$v = explode('=', $val);
			if($v[0] == $forum) {
				$last .= "$forum=$n";
				$done = 1;
			}
			else
				$last .= "$val,";
		}
		if(!$done) {
			$last .= "$forum=$n";
		}
	}
	setcookie('last', $last, time() + 60*60*24*30);
	print "$last<BR>\n";
}

if(!empty($name)) {	/* update record in usrtrack table */
	$name = str_replace("\'", '', $name);	/* prevent SQL injection */

	/* first search if a record for this user exists */
	$query = "SELECT * FROM usrtrack WHERE user='$name'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	$n = mysql_num_rows($result);
	if($n) {	/* this user already exists so update record */
		$query = "UPDATE usrtrack SET forum='$forum', last=NULL WHERE user='$name'";
		$result = mysql_query($query);
		if(!$result) {
			echo mysql_error();
			exit;
		}
	}
	else {
		$query = "INSERT INTO usrtrack (forum, user) VALUES ('$forum', '$name')";
		$result = mysql_query($query);
		if(!$result) {
			echo mysql_error();
			exit;
		}
	}
}
print "$read<BR>\n";
?>
