<HTML>
<HEAD>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
<TITLE>מערכת בלוגים - עריכת קישורים</TITLE>
<STYLE>
.para {display: none;}
table { font-size: 14px; font-family: arial, sans-serif}
body {margin:0; font-size: 12px, font-family: arial, sans-serif}
a:visited {color:blue}
a:link {color:navy; font-family:arial, sans-serif }
a:hover {color:red}
.text1 { font-size:10px; font-family: arial, sans-serif}
.text2 { font-size:11px; font-family: arial, sans-serif}
.text3 { font-size:14px; font-family: arial, sans-serif}
h1 {font-size: 24; font-weight:bold; font-family: arial, sans-serif; color: navy}
h2 {font-size: 18; font-weight:bold; font-family: arial, sans-serif; color: navy}
</STYLE>
<SCRIPT Language=JavaScript>

function ShowNewGroup(nr) {
  if (document.getElementById){
    current = document.getElementById(nr).style.display = 'block';
    document.getElementById(nr).style.display = current;
  }
  else if (document.all) {
    current = document.all[nr].style.display = 'block';
    document.all[nr].style.display = current;
  }
  else if (document.layers) {
    var i = parseInt(nr.substr(nr.length-1,1));
    var replacing = heights[i-1];
    shown[i] = true;
    document.layers[nr].visibility = 'show';
    var tempname = 'header' + i;
    document.layers[nr].top = document.layers[tempname].top + headerheight;

    for (j=(i+1);j<=max;j++) {
      name1 = 'header' + j;
      document.layers[name1].top += replacing;
      if (shown[j]) {
	name2 = 'number' + j;
	document.layers[name2].top += replacing;
      }
    }
  }
  else alert ('This link does not work in your browser.');
}

function HideNewGroup(nr) {
  if (document.getElementById){
    current = document.getElementById(nr).style.display = 'none';
    document.getElementById(nr).style.display = current;
  }
  else if (document.all) {
    current = document.all[nr].style.display = 'none';
    document.all[nr].style.display = current;
  }
  else if (document.layers) {
    var i = parseInt(nr.substr(nr.length-1,1));
    var replacing = heights[i-1];
    shown[i] = false;
    document.layers[nr].visibility = 'hide';
    var tempname = 'header' + i;
    document.layers[nr].top = document.layers[tempname].top + headerheight;

    for (j=(i+1);j<=max;j++) {
      name1 = 'header' + j;
      document.layers[name1].top += replacing;
      if (shown[j]) {
	name2 = 'number' + j;
	document.layers[name2].top += replacing;
      }
    }
  }
  else alert ('This link does not work in your browser.');
}

function ChangeGrp() {
  var val = document.linkadmin.group.value;

  if(val == "__NEW__") {
    ShowNewGroup('newgrp');
  }
  else {
    HideNewGroup('newgrp');
  }
}
</SCRIPT>

<?PHP
include('blogs.cfg.php');
include('common.inc');

function PrintGroups($default) {
  $query = "SELECT grp FROM links GROUP BY grp";
  $result = mysql_query($query);
  if(!$result) {
    echo mysql_error();
    exit;
  }
  print "<SELECT name=group onchange=ChangeGrp()>\n";
  print "<OPTION value=__NULL__ ";
  if(!$default || ($default == "__NULL__")) {
    print "SELECTED";
  }
  print ">";
  print "-- בחר קבוצה --";
  print "\n";
  while($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
    $grp = $line['grp'];
    print "<OPTION value=\"$grp\"";
    if($grp == $default) {
      print "SELECTED";
    }
    print ">$grp\n";
  }
  print "<OPTION value=\"__NEW__\">-- קבוצה חדשה --\n";
  print "</SELECT>\n";
}

$link = mysql_connect($host, $user, $pswd) or die("Could not connect to host $host");
mysql_select_db($database) or die("Could not select database: $database");

$data = $_GET['data'];
if(empty($data))
     $data = $_COOKIE['data'];
$name = $_GET['name'];
if(empty($name))
     $name = $_COOKIE['name'];
$name = str_replace("\'", '', $name);	/* prevent SQL injection */

$blognum = $_GET['blog'];
$action = $_GET['action'];

if($blognum) {			/* we have a blog number so we extract page information from it */
	$query = "SELECT pageheader,name FROM bloglist WHERE blognum='$blognum'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	$pghead = $line['pageheader'];
	$bloguser = $line['name'];
}

if($pghead) {
	print "$pghead\n";
}
else {
	print "<BODY>\n";
	print "$pageheader\n";
}

function GetBlogName($num) {
	$query = "SELECT blog_name FROM bloglist WHERE blognum='$num'";
	$result = mysql_query($query);
	if(!$result) {
    	echo mysql_error();
    	exit;
  	}
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	$title = $line['blog_name'];
	return $title;
}

$action = $_GET['action'];
$blognum = $_GET['blog'];

if($action == "delete") {
	$url = $_GET['url'];
	
	$query = "DELETE FROM links WHERE url='$url'";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	print "\n<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=linkedit.php?blog=$blognum\">\n";

}
if($action == "add") {
	$url = $_POST['url'];
	$desc = $_POST['desc'];
	$group = $_POST['group'];
	if($group == "__NEW__")
		$group = $_POST['new_grp'];
	
	$query = "INSERT INTO links (blognum, grp, url, description) ";
	$query .= "VALUES ('$blognum', '$group', '$url', '$desc')";
	$result = mysql_query($query);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	print "\n<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=linkedit.php?blog=$blognum\">\n";

}

/* Display table of links */
?>
<CENTER>
<TABLE dir=RTL border=8>
<TR>
<TD><B>קבוצה
<TD><B>קישור
<TD><B>תיאור
</TR>
<?
$query = "SELECT * FROM links WHERE blognum=$blognum";
$result = mysql_query($query);
if(!$result) {
	echo mysql_error();
	exit;
}
while($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$group = $line['grp'];
	$url = $line['url'];
	$desc = $line['description'];
	
	print "<TR>\n";
	print "<TD>$group\n";
	print "<TD><A HREF=$url>$url</A>\n";
	print "<TD><A HREF=linkedit.php?blog=$blognum&url=$url&action=delete>$desc</A>\n";
	print "</TR>\n";
}
print "</TABLE>\n";
print "<BR><BR>\n";
print "<H1>הוסף קישור</H1>\n";
print "<BR>\n";
print "<FORM name=linkadmin action=linkedit.php?blog=$blognum&action=add method=post>\n";
print "<TABLE dir=RTL border=8>\n";
print "<TR>\n";
print "<TD>קבוצה:\n";
print "<TD>\n";
print "<TABLE border=0><TR><TD>\n";
PrintGroups($group);
print "<TD>\n";
print "<DIV class=para id=newgrp><INPUT type=text name=new_grp></DIV>\n";
print "</TABLE>\n";

print "<TR>\n";
print "<TD>קישור:\n";
print "<TD><INPUT type=text name=url size=60>\n";

print "<TR>\n";
print "<TD>תיאור:\n";
print "<TD><INPUT type=text name=desc size=60>\n";

print "<TR><TD colspan=2 align=center><INPUT type=submit value=הוסף>\n";
print "</TABLE>\n";
print "</FORM>\n";

?>
</BODY>
</HTML>
