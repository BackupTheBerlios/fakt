<HTML>
<HEAD>
<?PHP
/*
  | Show guides script of YAFS guides system
  |
  | YAFS copyright: Helicon technologies LTD. 2003
  | YAFS is distributed under the GNU public license
  |
  | This file is part of YAFS (Yet Another Forums System).
  |
  | YAFS is free software; you can redistribute it and/or modify
  | it under the terms of the GNU General Public License as published by
  | the Free Software Foundation;
  |
  | YAFS is distributed in the hope that it will be useful,
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

print "<TITLE>$l10nstr[148]</TITLE>\n";		/* guides system */
print "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=$charset\">\n";

?>

<SCRIPT Language=JavaScript>
function Test(){
  alert("Got here");
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
<BODY >

<TABLE bgcolor=#5272A4 width=100% cellpadding=0 cellspacing=0><TR><TD><IMG SRC=fakt.jpg><BR>
<TD dir=RTL valign=center><FONT color=white>
<A HREF=showguide.php><IMG src=doc_open.gif border=0><FONT color=white><BIG>
<?PHP print "$l10nstr[149]</A>\n";		/* guides list */ ?>

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

/*
 | Replace links with HTML tags <A HREF...>
 | This code was copied from the PHP.NET web site i did not take
 | the time to fully understand it.
 */
function AddLinks($string) {
  $string = preg_replace("/(^|[^=\"\/])\b((\w+:\/\/|www\.)[^\s<]+)".
			 "((\W+|\b)([\s<]|$))/i", "$1<a href=\"$2\" target=_blank>$2</a>$4",
			 $string);
  return preg_replace("/href=\"www/i", "href=\"http://www", $string);
  //  $txt = preg_replace( "/(?<!<a href=\")((http|ftp)+(s)?:\/\/[^<>\s]+)/i", "<a href=\"\\0\">\\0</a>", $txt );
  //  return $txt;
}

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

$data = $_GET['data'];
if(empty($data))
     $data = $_COOKIE['data'];
$name = $_GET['name'];
if(empty($name))
     $name = $_COOKIE['name'];
$name = str_replace("\'", '', $name);	/* prevent SQL injection */

$action = $_GET['action'];
$id = $_GET['id'];

$link = mysql_connect($host, $user, $pswd) or die("Could not connect to host $host");
mysql_select_db($database) or die("Could not select database: $database");

/* Auxliary functions */

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
  print "<TABLE dir=RTL width=100%><TR><TD valign=top>\n";
  DisplayContents($contents);
  print "<BR>\n";

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
      print "<IMG SRC=$userfiles/$picture width=$w height=$h border=0>";
      print "</A><BR><BR>\n";
    }
    else {
      print "<TD><A HREF=$userfiles/$picture target=_blank>";
      print "$l10nstr[189] ";
      print "$ext</A><BR>\n";
    }

  }
  print "</TABLE>\n";

  print "</DIV>\n";
  ShowGuide($id, $num);
}

if($id == 0) {
  $showall = $_GET['showall'];

  /* No guide selected show guides list */
  /* The best way would be to do this using a template
     This is done this way becasue of lack of time.
     It will be changed I hope in later versions.
  */
  print "<TABLE dir=RTL width=100% cellpading=0 cellspacing=0><TR>";
  print "<TD bgcolor=white ><IMG src=fakt_girl.jpg>\n";
  print "<TD bgcolor=white valign=top>\n";
  print "<CENTER><H1>$l10nstr[161]</H1>\n";
  print "<P dir=$langdir>\n";

  print "<TABLE dir=$langdir width=100% border=0>\n";
  /* print table headers */
  print "<TR bgcolor=#f0f0f0>\n";
  print "<TD bgcolor=white>&nbsp;\n";
  print "<TD><B><FONT color=navy>$l10nstr[190]\n";
  print "<TD><B><FONT color=navy>$l10nstr[164]\n";			/* authors */
  print "<TD width=20%><B><FONT color=navy>$l10nstr[163]\n";		/* update date */

  $lastgrp = "";
  $query = "SELECT * FROM titles ORDER BY grp ASC, name ASC";
  $result = mysql_query($query);
  if(!$result) {
    echo mysql_error();
    exit;
  }
  while($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
    $num = $line['num'];
    $grp = $line['grp'];
    $guidename = $line['name'];
    $publish = $line['publish'];
    $editors = $line['editors'];
    $creation = $line['creation'];
    $modification = $line['modification'];

    $creation = FormatDate($creation);
    $modification = FormatDate($modification);

    if($publish || $showall) {
      if($grp != $lastgrp) {
	print "<TR><TD colspan=4><BR>\n";
	$lastgrp = $grp;
	print "<H2 dir=RTL>$grp<H2>\n";
      }
      print "<TR><TD>&nbsp;&nbsp;&nbsp;<IMG src=01.jpg>&nbsp;<TD dir=RTL>\n";
      print "<A HREF=showguide.php?id=$num>$guidename</A>\n";
      /*      $ea = explode(',', $editors);
      if(count($ea) > 1)
	print "ï¿½ï¿½ï¿½ ";
      else
	print "ï¿½ï¿½ "; */
      print "<TD>$editors\n";
      //      print "   ï¿½ï¿½ ï¿½ï¿½: ";
      print "<TD>$modification\n";
    }
  }
  print "</TABLE>\n"; /* end of titles table */

  print "<TD width=30% align=center bgcolor=white>\n";
  print "<BR><BR><BR><BR><BR><BR><BR><BR><BR>\n";
  print "<IMG src=pic3.jpg>\n";
  print "<BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR>\n";
  print "</TABLE>\n";
  print "<P dir=RTL><BR>\n";
  print "<table border=0 cellpadding=0 cellspacing=0 style=\"border-collapse: collapse\" bordercolor=#111111 width=100% bgcolor=#F0F0F0>";
  print "<TR><TD align=center>\n";
  print "$l10nstr[165]  \n";	/* FAKT guides system */
  print "$l10nstr[166]: ";		/* Programming */
  print "<A HREF=mailto:ori@helicontech.co.il>$l10nstr[167]</A> &nbsp; &nbsp;\n";		/* Ori Idan */
  print "$l10nstr[168]: ";
  print "<A HREF=mailto:nuritavi@012.net.il>$l10nstr[169]</A>\n";
  print "</TABLE>\n";
  print "</BODY>\n</HTML>\n";
  exit;
}
GetGuideName($id);  /* fill in global variables */
print "<P dir=RTL class=text3>\n";
print "<IMG src=01.jpg>\n";
$a = explode(',', $editors);
$c = count($a);
if($c > 1)
  print "$l10nstr[191]: ";
else
  print "$l10nstr[192]: ";
print "$editors<BR>\n";
print "<IMG src=01.jpg>\n";
print "$l10nstr[163]: ";		/* update date */
print "$moddate\n";

print "<H2 dir=RTL><IMG src=folder.jpg> &nbsp;&nbsp; $grp</H2>\n";
print "<CENTER><H1 dir=RTL>$title</H1></CENTER>\n";

print "<P dir=RTL>\n";
ShowGuide($id, 0);
?>
<P >
<HR>
<DIV DIR=RTL class=text2>
äîãøéê ðëúá áøùéåï: 
<A HREF=http://www.penguin.org.il/guides/gfdl_heb>GFDL</A><BR>
</BODY>
</HTML>
