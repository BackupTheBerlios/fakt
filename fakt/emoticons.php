<?PHP
header('Content-type: text/html;charset=UTF-8');
?>
<HTML>
<HEAD>
<?PHP
	include('l10n.inc');
	print "<meta http-equiv=Content-Type content=\"text/html; charset=$charset\">\n";
	print "<TITLE>$l10nstr[135]</TITLE>\n";
?>
<SCRIPT language=javascript>


function Test() {
	alert("Got here");
}


function ReturnValue(val) {
	alert(val);
	window.returnValue = val;
	window.close();
}
</SCRIPT>
</HEAD>
<BODY>
<!-- <A href=javascript:void(); onclick="window.returnValue=1;window.close();">1</A><BR>
<A href=javascript:void(); onclick="window.returnValue=1;window.close();">2</A><BR> -->
<CENTER>
<?PHP
print "<H1 dir=RTL>$l10nstr[136]</H1>\n";

include('config.inc.php');

$handle = opendir($emoticons);
$i = 0;
while($filename = readdir($handle)) {
	$files[$i] = $filename;
	$i++;
}
sort($files);
reset($files);
print "<TABLE border=0 cellpading=10>\n<TR>";
$i = 0;
foreach($files as $filename) {
	if($filename[0] == '.')
		continue;
	if($i == 8) {
		print "<TR>\n";
		$i = 0;
	}
	else
		$i++;
	list($name, $ext) = explode('.', $filename);
	print "<TD align=center><IMG SRC=$emoticonsurl/$filename border=0><BR>~$name~\n";
}
print "</TABLE>\n";
?>
</BODY>
</HTML>
