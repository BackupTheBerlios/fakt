<?PHP
header('Content-type: text/html;charset=UTF-8');
?>
<HTML>
<HEAD>
<?PHP
/*
  | FAKT edit script
  |
  | FAKT copyright: Ori Idan Helicon technologies LTD. 2003
  | FAKT is distributed under the GNU public license
  |
  | This file is part of FAKT (Free Authoring, Knowledge & Thinking).
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
include('config.inc');
include('glob.inc');
include('common.inc');

?>

<TITLE>מערכת מדריכים</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1255">

<SCRIPT Language=JavaScript>
function Test(){
  alert("Got here");
}

function AddCodeCommand() {
  document.editsection.sec_contents.value += "\r\n.Code\r\n\r\n.Code";
}

function StartBold() {
  document.editsection.sec_contents.value += "|B|";
}

function EndBold() {
  document.editsection.sec_contents.value += "|EB|";
}

function blocking(nr, t) {
  if (document.getElementById){
    current = (document.getElementById(nr).style.display == 'block') ? 'none' : 'block';
    document.getElementById(nr).style.display = current;
    document.getElementById(t).style.color='#ff0000';
  }
  else if (document.all) {
    current = (document.all[nr].style.display == 'block') ? 'none' : 'block';
    document.all[nr].style.display = current;
    document.all[title].style.color='#ff0000';
  }
  else if (document.layers) {
    var i = parseInt(nr.substr(nr.length-1,1));
    var replacing = heights[i-1];
    if (shown[i]) {
      shown[i] = false;
      replacing = -replacing;
      document.layers[nr].visibility = 'hide';
      document.layers[nr].top = safe;
    }
    else {
      shown[i] = true;
      document.layers[nr].visibility = 'show';
      var tempname = 'header' + i;
      document.layers[nr].top = document.layers[tempname].top + headerheight;
    }
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
  var val = document.details.group.value;

  if(val == "__NEW__") {
    ShowNewGroup('newgrp');
  }
  else {
    HideNewGroup('newgrp');
  }
}

</SCRIPT>

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

</HEAD>
<BODY>
<TABLE bgcolor=#5272A4 width=100% cellpadding=0 cellspacing=0><TR><TD><IMG SRC=fakt.jpg><BR>
<TD dir=RTL valign=center><FONT color=white>
<A HREF=guides.php><IMG src=doc_open.gif border=0><FONT color=white><BIG>
רשימת המדריכים</A>
</TABLE>

<?PHP
/*
 | global variables for guide title and guide group
 | These variables will be filled with values in the function
 | GetGuideName
 */
$title = "";
$grp = "";
$editors = "";
$moddate = "";
$last = 0; /* last section id */

/* Auxliary functions */

function AddPicForm($id, $num) {
  print "<CENTER><H1>הוספת קובץ</H1>\n";
  print "<FORM enctype=multipart/form-data action=editguide.php?action=submitfile&id=$id&num=$num method=post>\n";
  print "<TABLE border=8><TR>\n";
  print "<input type=hidden name=\"MAX_FILE_SIZE\" value=500000><TD>\n";
  print "<input type=file name=imgfile size=40>\n";
  print "<TR><TD colspan=2 align=center>\n";
  print "<input type=\"submit\" value=שלח>\n";
  print "</TABLE>\n</FORM>\n";
  
}

/*
 | UpdatePic:
 | Process the AddPic form, get the picture, copy it to a directory
 | in the system as specified by '$userfilesdir' and update the database
 | with the new picture name
 */
function UpdatePic() {
  global $id;
  global $userfilesdir;

  $num = (int)$_GET['num'];
  
  $size = (int)$_FILES['imgfile']['size'];
  print "size: $size<BR>\n";
  if($size > 0) {
    if($size > 100000) {
      ErrorReport("קובץ גדול מדי");
      exit;
    }
    $tmpname = $_FILES['imgfile']['tmp_name'];
    $name = $_FILES['imgfile']['name'];
    $carr = explode(".", $name);
    $n = count($carr) - 1;
    $ext = $carr[$n];
    $filename = "${table}_$num.$ext";
    move_uploaded_file($tmpname, "$userfilesdir/$filename");
    $query = "UPDATE guides SET picture='$filename' WHERE id='$id' AND num='$num'";
    $result = mysql_query($query);
    if(!$result) {
      echo mysql_error();
      exit;
    }
    print "<CENTER><H1>הקובץ נוסף להודעה</H1>\n";
    print "יש ללחוץ רענון על מנת לראות את הקובץ";
    print "בעוד מספר שניות תחזור למדריך, אם הקובץ לא נראה, לחץ רענון";
    print "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"2; URL=editguide.php?id=$id\">\n";
  }
  else {
    ErrorReport("שגיאה בטעינת קובץ, קובץ גדול מדי או מסוג לא חוקי");
    exit;
  }
  exit;
}

/*
 | AddLinks:
 | Replace URL's in string with links
 */
function AddLinks($string) {
  $string = preg_replace("/(^|[^=\"\/])\b((\w+:\/\/|www\.)[^\s<]+)".
			 "((\W+|\b)([\s<]|$))/i", "$1<a href=\"$2\" target=_blank>$2</a>$4",
			 $string);
  return preg_replace("/href=\"www/i", "href=\"http://www", $string);
  //  $txt = preg_replace( "/(?<!<a href=\")((http|ftp)+(s)?:\/\/[^<>\s]+)/i", "<a href=\"\\0\">\\0</a>", $txt );
  //  return $txt;
}
  
/*
 | DisplayContents:
 | Show a string of section body, parsing dot commands
 | currently only one command is supported 
 | .C that will start or stop code section
 */
function DisplayContents($contents) {
  $incode = 0;

  $a = explode('<BR>', $contents);
  foreach($a as $val) {
    if($val[0] == '.') { /* this is a command */
      if($val[1] == 'C') {
	if(!$incode) {
	  print "<CENTER>\n";
	  print "<TABLE width=90% border=0 cellspacing=1 cellpadding=0 bgcolor=000000>";
	  print "<TR><TD>\n";
	  print "<table width=100% cellspacing=0 cellpadding=0 bgcolor=#EEEEEE><tr><td dir=ltr>";
	  $incode++;
	}
	else {
	  print "</TABLE>\n";
	  print "</TABLE>\n";
	  print "</CENTER>\n";
	  $incode--;
	}
      }
    }
    else {
      $val = AddLinks($val);
      print "$val<BR>\n";
    }
  }
  if($incode) {
    while($incode) {
      print "</TABLE>\n";
      print "</TABLE>\n";
      print "</CENTER>\n";
      $incode--;
    }
  }
}

function FormatDate($mysqldate) {
  list($yy, $mm, $dd) = explode('-', $mysqldate);
  return "$dd/$mm/$yy";
}

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

/*
 | CheckAllowed:
 | Check if user given in $name is either admin user or part of
 | $members so he is allowed to edit the guide
 */
function CheckAllowed($name, $members) {
  if($name == 'admin')
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

/* Auxliary functions */
function ShowUserSex() {
  global $name;

  $query = "SELECT sex FROM login WHERE name='$name'";
  $result = mysql_query($query);
  if(!$result) {
    echo mysql_error();
    exit;
  }
  $line = mysql_fetch_array($result, MYSQL_ASSOC);
  if($line['sex'] == 'female')
    print "את מחוברת כעת כ: ";
  else
    print "אתה מחובר כרגע כ: ";
}

function ShowGroupsRing($default) {
  $query = "SELECT grp FROM titles GROUP BY grp";
  $result = mysql_query($query);
  if(!$result) {
    echo mysql_error();
    exit;
  }
  print "<SELECT name=group onchange=ChangeGrp()>\n";
  print "<OPTION value=__NULL__ ";
  if($default == "__NULL__") {
    print "SELECTED";
  }
  print ">-- בחר תחום --\n";
  while($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
    $grp = $line['grp'];
    print "<OPTION value=\"$grp\"";
    if($grp == $default) {
      print "SELECTED";
    }
    print ">$grp\n";
  }
  print "<OPTION value=__NEW__>-- תחום חדש --\n";
  print "</SELECT>\n";
}
   
function GetGuideName($id) {
  global $title;
  global $grp;
  global $editors;
  global $moddate;

  $query = "SELECT * FROM titles WHERE num=$id";
  $result = mysql_query($query);
  if(!$result) {
    echo mysql_error();
    exit;
  }

  $line = mysql_fetch_array($result, MYSQL_ASSOC);
  $title = $line['name'];
  $grp = $line['grp'];
  $editors = $line['editors'];
  $moddate = $line['modification'];
  $moddate = FormatDate($moddate);
}

function EditDetails($id) {
  $query = "SELECT * from titles WHERE num='$id'";
  $result = mysql_query($query);
  if(!$result) {
    echo mysql_error();
    exit;
  }

  $line = mysql_fetch_array($result, MYSQL_ASSOC);
  $grp = $line['grp'];
  $name = $line['name'];
  $editors = $line['editors'];
  $publish = $line['publish'];

  print "<CENTER><H2>עריכת פרטי מדריך</H2>\n";
  print "<FORM name=details action=editguide.php?action=submitdetails&id=$id method=post>\n";
  print "<TABLE dir=RTL border=8><TR>\n";
  print "<TD>תחום:\n";
  print "<TD>";

  ShowGroupsRing($grp);
  print "<TR><TD colspan=2>\n";
  print "<DIV class=para id=newgrp>\n";
  print "<TABLE border=1 width=100%><TR>\n";
  print "<TD>תחום חדש: ";
  print "<TD><INPUT type=text size=60 name=new_grp>\n";
  print "</TABLE>\n";
  print "</DIV>\n";

  print "<TR>\n";
  print "<TD>שם מדריך:\n";
  print "<TD><INPUT type=text size=80 name=guide_name value=\"$name\">\n";
  print "<TR>\n";
  print "<TD>עורכים:\n";
  print "<TD><INPUT type=text size=80 name=editors value=\"$editors\">\n";
  print "<TR>\n";
  print "<TD>סטטוס:\n";
  print "<TD><SELECT name=status>\n";
  print "publish: $publish<BR>\n";
  if($publish == 0) {
    print "<OPTION value=0 SELECTED>בעריכה\n";
    print "<OPTION value=1>ניתן לקריאה\n";
  }
  else {
    print "<OPTION value=0>בעריכה\n";
    print "<OPTION value=1 SELECTED>ניתן לקריאה\n";
  }
  print "</SELECT>\n";
  print "<TR>\n";
  print "<TD colspan=2 align=center><INPUT type=submit value=שלח>\n";
  print "</TABLE>\n</FORM>\n";
}

function EditSection($id, $last, $edit) {
  $header = "";
  $contents = "";
  
  if($edit) {
    $query = "SELECT * from guides WHERE id=$id AND num=$last";
    $result = mysql_query($query);
    if(!$result) {
      echo mysql_error();
      exit;
    }
    $line = mysql_fetch_array($result, MYSQL_ASSOC);
    $num = $line['num'];
    $header = $line['header'];
    $contents = $line['contents'];
    $picture = $line['picture'];
    
    print "<CENTER><H2>עריכת קטע</H2>\n";
    print "<FORM name=editsection action=editguide.php?action=submitedit&id=$id&num=$num method=post>\n";
  }
  else {
    print "<CENTER><H2>הוספת קטע</H2>\n";
    print "<FORM name=editsection action=editguide.php?action=submit&id=$id&last=$last method=post>\n";
  }
  print "<TABLE dir=RTL border=8>\n";
  print "<TR><TD>כותרת:\n";
  print "<TD><INPUT type=text size=70 name=sec_name value=\"$header\">\n";
  print "<TR><TD>תוכן:\n";
  print "<TD align=right><TEXTAREA cols=60 rows=20 name=sec_contents>$contents</TEXTAREA>\n";
  print "<BR><A HREF=javascript:AddCodeCommand()>הוסף קוד</A>\n";
  print "&nbsp;&nbsp;<A HREF=javascript:StartBold()>התחל הדגשה</A>\n";
  print "&nbsp;&nbsp;<A HREF=javascript:EndBold()>סיים הדגשה</A>\n";
  print "<TR><TD colspan=2 align=center><INPUT type=submit value=שלח>\n";
  print "</TABLE>\n";
  print "</FORM>\n";
}

/*
 | Recursively go over all sections of that guide and return
 | number of last section 
 | the number that no other section has as it's ancestor
 */
function GetLastSection($id, $ancestor) {
  $query = "SELECT * from guides WHERE id=$id AND ancestor=$ancestor";
  $result = mysql_query($query);
  if(!$result) {
    echo mysql_error();
    exit;
  }
  if(mysql_num_rows($result) == 0) {
    return $ancestor;	/* end of recursive call */
  }
  $line = mysql_fetch_array($result, MYSQL_ASSOC);
  $num = $line['num'];
  return GetLastSection($id, $num);
}
  
/*
 | Recursive function to show all sections in guide
 */
function ShowGuide($id, $ancestor) {
  global $last;
  global $name;
  global $editors;
  global $userfiles;

  $query = "SELECT * from guides WHERE id=$id AND ancestor=$ancestor";
  $result = mysql_query($query);
  if(!$result) {
    echo mysql_error();
    exit;
  }
  if(mysql_num_rows($result) == 0) {
    print "</TABLE>\n";
    return 0;	/* end of recursive call */
  }
  $line = mysql_fetch_array($result, MYSQL_ASSOC);
  $num = $line['num'];
  $last = $num;
  $header = $line['header'];
  $contents = $line['contents'];
  $picture = $line['picture'];
  $header = SpecialCodes($header);
  $contents = SpecialCodes($contents);

  if($ancestor == 0) { /* this is first time so start table */
    print "<TABLE dir=RTL border=0 width=100%>\n";
  }
  print "<TR><TD>\n";
  print "<A HREF=javascript:void() onclick=\"blocking('s$num', 'h$num')\">$header</A><BR>\n";

  print "<TR><TD>\n";

  print "<DIV class=para id=s$num>\n";
  print "<TABLE dir=RTL width=100% bgcolor=#FFFFFF><TR><TD valign=top>\n";
  DisplayContents($contents);
  print "<BR>\n";
  //  print "$contents<BR>\n";
  if(!empty($picture)) {
    print "<TD valign=center align=center>\n";
    $ext = GetExt($picture);
    if(IsImg($ext)) {
      $s = getimagesize("$userfiles/$picture");
      $w = $s[0];
      $ow = $w + 35;
      $h = $s[1];
      $oh = $h + 35;
      if($w > 100) {
	$ar = $h/$w;
	$h = 200*$ar;
	$w = 200;
      }
      print "<A HREF=\"#\" onclick=\"javascript:window.open(";
      print "'$userfiles/$picture', 'Picture', 'height=$oh, width=$ow scrollbars=yes resizable=yes')\">";
      //      print "<A HREF=$userfiles/$picture target=_blank>";
      print "<IMG SRC=$userfiles/$picture width=$w height=$h border=0>";
      print "</A><BR><BR>\n";
    }
    else {
      print "<TD><A HREF=$userfiles/$picture target=_blank>";
      print "מצורף קובץ ";
      print "$ext</A><BR>\n";
    }
    
  }
  if(CheckAllowed($name, $editors)) {
    print "<TR><TD align=right dir=RTL>\n";
    print "<A HREF=editguide.php?action=editsection&id=$id&num=$num>ערוך קטע</A>\n";
    print "&nbsp;&nbsp;&nbsp;";
    print "<A HREF=editguide.php?action=delsection&id=$id&num=$num>מחק קטע</A>\n";
    print "&nbsp;&nbsp;&nbsp;";
    print "<A HREF=editguide.php?action=addpic&id=$id&num=$num>הוסף תמונה</A>\n";
  }
  print "</TABLE>\n";
  print "<BR><BR>\n";
  print "</DIV>\n";
  ShowGuide($id, $num);
}

/* Get command line and cookies parameters */
$data = $_GET['data'];
if(empty($data))
     $data = $_COOKIE['data'];
$name = $_GET['name'];
if(empty($name))
     $name = $_COOKIE['name'];
$name = str_replace("\'", '', $name);	/* prevent SQL injection */
$data = str_replace("\'", '', $data);

$action = $_GET['action'];
$id = (int)$_GET['id'];

/* connect to mySQL database */
$link = mysql_connect($host, $user, $pswd) or die("Could not connect to host $host");
mysql_select_db($database) or die("Could not select database: $database");


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
}
if(empty($name)) {  /* you can not edit or create a guide without being registered user */
  print "<CENTER><H1>לא ניתן לכתוב מדריכים מבלי להרשם למערכת</H1></CENTER>\n";
  exit;
}
print "<P dir=RTL class=text3>\n";
ShowUserSex();
print "$name<BR>\n";

/* Respond to action parameter */
if($action == 'new') {
  print "<CENTER><H1>מערכת מדריכים</H1>\n";
  print "<BR><FORM name=details action=editguide.php?action=edit&id=0 method=post>\n";
  print "<TABLE border=8 dir=RTL>\n";
  print "<TR><TD align=right dir=RTL colspan=2>יצירת מדריך חדש:<BR><BR>\n";
  print "<TR><TD>תחום:\n";
  print "<TD>";
  ShowGroupsRing("__NULL__"); /* new guide so no default group */
  print "<TR><TD colspan=2>\n";
  print "<DIV class=para id=newgrp>\n";
  print "<TABLE border=1 width=100%><TR>\n";
  print "<TD>תחום חדש: ";
  print "<TD><INPUT type=text size=60 name=new_grp>\n";
  print "</TABLE>\n";
  print "</DIV>\n";
  print "<TR><TD>שם מדריך:\n";
  print "<TD><INPUT type=text size=80 name=guide_name>\n";
  print "<TR><TD colspan=2 align=center>\n";
  print "<INPUT type=submit value=בצע>\n";
  print "</TABLE>\n";
  print "</BODY>\n</HTML>\n";
  exit;
}
if($action == 'delete') {
  $query = "DELETE from titles WHERE num='$id'";
  $result = mysql_query($query);
  if(!$result) {
    echo mysql_error();
    exit;
  }
  print "<CENTER><H1>המדריך נמחק</H1>\n";
  print "<A HREF=guides.php>לחץ כאן לחזרה למערכת</A><BR>\n";
  print "</BODY>\n</HTML>\n";
  exit;
}
if($action == 'details') {
  EditDetails($id);
  print "</BODY>\n</HTML>\n";
  exit;
}
/* If we got here, we either have no action parameter or unknow action */
if($id == 0) {  /* we are creating a new guide we get here after action=new */
  $title = $_POST['guide_name'];
  $grp = $_POST['group'];
  if($grp == "__NEW__") {
    $grp = $_POST['new_grp'];
  }
  if(!empty($grp) && !empty($title)) {
    $query = "INSERT INTO titles ";
    $query .= "VALUES(NULL, '$grp', '$title', 0, '$name', CURDATE(), CURDATE())";
    $result = mysql_query($query);
    if(!$result) {
      echo mysql_error();
      exit;
    }
    print "<CENTER><H1>המדריך החדש נוצר</H1>\n";
    print "<A HREF=guides.php>לחץ כאן לחזרה למערכת</A><BR>\n";
  }
  else {
    /* this will be shown if someone calls editguide.php not as a response
       to a form so no POST parameters found */
    print "<CENTER><H1>קריאה לא חוקית למערכת</H1>\n";
  }
  print "</BODY>\n</HTML>\n";
  exit;
}

/* If we got here, we have a guide id number and no action so display guide */ 
GetGuideName($id);  /* fill in global variables */
print "<P dir=RTL class=text3>\n";
print "<IMG src=01.jpg>\n";
$a = explode(',', $editors);
$c = count($a);
if($c > 1)
  print "עורכים:\n";
else
  print "נערך על ידי: ";
print "$editors<BR>\n";
print "<IMG src=01.jpg>\n";
print "עודכן לאחרונה: \n";
print "$moddate\n";

print "<H2 dir=RTL><IMG src=folder.jpg> &nbsp;&nbsp; $grp</H2>\n";
print "<CENTER><H1 dir=RTL>$title</H1></CENTER>\n";

print "<P dir=RTL>\n";

/* Section handling actions */
if($action == 'addsection') {
  $last = $_GET['last'];
  if($last == 0) {
    $last = GetLastSection($id, 0);
  }
  EditSection($id, $last, 0);  /* add a new section so last argument is 0 */
  print "</BODY>\n</HTML>\n";
  exit;
}
if($action == 'editsection') {
  $num = $_GET['num'];

  EditSection($id, $num, 1);
  print "</BODY>\n</HTML>\n";
  exit;
}
if($action == 'submit') {
  $last = $_GET['last'];
  $header = $_POST['sec_name'];
  $contents = $_POST['sec_contents'];
  $query = "INSERT INTO guides ";
  $query .= "(id, ancestor, header, contents) ";
  $query .= "VALUES ('$id', '$last', '$header', '$contents') ";
  $result = mysql_query($query);
  if(!$result) {
    echo mysql_error();
    exit;
  }
  $query = "UPDATE titles SET modification=CURDATE() WHERE num='$id'";
  $result = mysql_query($query);
  if(!$result) {
    echo mysql_error();
    exit;
  }
}
if($action == 'submitedit') {
  $num = $_GET['num'];
  $header = $_POST['sec_name'];
  $contents = $_POST['sec_contents'];
  $query = "UPDATE guides SET ";
  $query .= "header='$header', contents='$contents' ";
  $query .= "WHERE id='$id' AND num='$num'";

  $result = mysql_query($query);
  if(!$result) {
    echo mysql_error();
    exit;
  }
  $query = "UPDATE titles SET modification=CURDATE() WHERE num='$id'";
  $result = mysql_query($query);
  if(!$result) {
    echo mysql_error();
    exit;
  }
}
if($action == 'delsection') {
  $num = $_GET['num'];

  /* first get ancestor of deleted record */
  $query = "SELECT ancestor FROM guides WHERE id='$id' AND num='$num'";
  $result = mysql_query($query);
  if(!$result) {
    echo mysql_error();
    exit;
  }
  $line = mysql_fetch_array($result, MYSQL_ASSOC);
  $ancestor = $line['ancestor'];

  /* delete the record */
  $query = "DELETE FROM guides WHERE id='$id' AND num='$num'";
  $result = mysql_query($query);
  if(!$result) {
    echo mysql_error();
    exit;
  }
  
  $query = "SELECT num FROM guides WHERE id='$id' AND ancestor='$num'";
  $result = mysql_query($query);
  if(!$result) {
    echo mysql_error();
    exit;
  }
  if(mysql_num_rows($result)) {
    $line = mysql_fetch_array($result, MYSQL_ASSOC);
    $num = $line['num'];
    $query = "UPDATE guides SET ancestor='$ancestor' WHERE id='$id' AND num='$num'";
    $result = mysql_query($query);
    if(!$result) {
      echo mysql_error();
      exit;
    }
  }
  $query = "UPDATE titles SET modification=CURDATE() WHERE num='$id'";
  $result = mysql_query($query);
  if(!$result) {
    echo mysql_error();
    exit;
  }
}
if($action == 'submitdetails') {
  $grp = $_POST['group'];
  if($grp == "__NEW__") {
    $grp = $_POST['new_grp'];
  }
  $title = $_POST['guide_name'];
  $editors = $_POST['editors'];
  $status = $_POST['status'];
  
  $query = "UPDATE titles SET ";
  $query .= "grp='$grp', ";
  $query .= "name='$title', ";
  $query .= "publish='$status',";
  $query .= "editors='$editors' ";
  $query .= "WHERE num='$id'";
  $result = mysql_query($query);
  if(!$result) {
    echo mysql_error();
    exit;
  }
  print "<CENTER><H2>פרטי המדריך עודכנו</H2>\n";
  print "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"2; URL=editguide.php?id=$id\">\n";
  print "תוך מספר שניות תחזור לעריכת המדריך<BR>\n";
  exit;

}
if($action == 'addpic') {
  $num = $_GET['num'];
  AddPicForm($id, $num);
  exit;
}
if($action == 'submitfile') {
  UpdatePic();
  exit;
}

ShowGuide($id, 0);
print "<BR>\n";
if(CheckAllowed($name, $editors)) {
  print "<A HREF=editguide.php?action=addsection&id=$id&last=$last>";
  print "הוסף קטע";
  print "</A>&nbsp;&nbsp;&nbsp;\n";
  $a = explode(',', $editors);
  if(($name == $a[0]) || ($name == 'admin'))
    print "<A HREF=editguide.php?action=details&id=$id>ערוך פרטי מדריך</A>\n";
}


?>

</BODY>
</HTML>
