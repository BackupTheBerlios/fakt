<HTML>
<HEAD>
<TITLE>Login names trasfer utility</TITLE>
</HEAD>
<BODY>
<?PHP
 /*
  | Login name transfer utility for FAKT forums system
  | Written by Ori Idan November 2003
  */
  /* General definitions */
  $sourceserver = 'muscat.grapesys.com';
  $sourceuser = 'gserver';
  $sourcepwd = 'grape630';
  $sourcedb = 'escforum';

  $targetserver = 'localhost';
  $targetuser = 'fakt';
  $targetpwd = 'forums';

$source_link = mysql_connect($sourceserver, $sourceuser, $sourcepwd) or die("Could not connect to host $sourceserver");
mysql_select_db($sourcedb) or die("Could not select database: $sourcedb");

$target_link = mysql_connect($targetserver, $targetuser, $targetpwd) or die("Could not connect to host $targetserver");
mysql_select_db($targetdb) or die("Could not select database: $targetdb");

print "Connected to servers.<BR>\n";

$srcquery = "SELECT * FROM login";
$srcresult = mysql_query($srcquery, $source_link);
if(!$srcresult) {
	echo mysql_error();
	exit;
}
print "Reading names from source login table.<BR>\n";

while($line = mysql_fetch_array($srcresult, MYSQL_ASSOC)) {
	$name = $line['name'];
	print "Checking name: $name...  &nbsp;&nbsp;\n";
	$dstquery = "SELECT name FROM login WHERE name='$name'";
	$dstresult = mysql_query($dstquery, $target_link);
	$n = mysql_num_rows($dstresult);
	if($n) {	/* This name exists, so do not append */
		print " Exists<BR>\n";
	}
	else {
		print " Added<BR>\n";
		$fullname = $line['fullname'];
		$email = $line['fullname'];
		$password = $line['password'];
		$lastonline = $line['lastonline'];
		$pubemail = $line['pubemail'];
		$web = $line['web'];
		$messangernum = $line['messangernum'];
		$messangersoft = $line['messangersoft'];
		$birthdate = $line['birthdate'];
		$sex = $line['sex'];
		$line['martial'];
		$occupation = $line['occupation'];
		$interest = $line['interest'];
		$signature = $line['signature'];
		$comments = $line['comments'];
		$picture = $line['picture'];

		$dstquery = "INSERT INTO login VALUES ";
		$dstquery .= "('$name', ";
		$dstquery .= " '$fullname', '$email', '$password', '$lastonline', ";
		$dstquery .= "'$pubemail', '$web', '$messangernum', '$messangersoft', '$birthdate', '$sex', ";
		$dstquery .= "'$martial', '$occupation', '$interest', '$signature', '$comments', '$picture') ";
		$dstresult = mysql_query($dstquery, $target_link);
		if(!$dstresult) {
			echo mysql_error();
			exit;
		}
	}
}
