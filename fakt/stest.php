<HTML>
<HEAD>
<TITLE>DIV JavaScript test</TITLE>
<SCRIPT Language=JavaScript>
function Test() {
	alert("Got here");
}

function OpenModal() {
	//retval = window.showModalDialog("emoticons.php", '', 'dialogHeight:200px');
	retval = window.open("emoticons.php", "Test", "height=230,width=350,resizable=yes");
	document.frmMessage.txtMessage.value = retval;
}

function cursorchange(t) {
	//document.getElementById(t).style.cursor='hand';
	document.getElementById(t).style.color='#ff0000';
	//alert(t);
}

function blocking(nr, t) {
	if (document.getElementById)
	{
		current = (document.getElementById(nr).style.display == 'block') ? 'none' : 'block';
		document.getElementById(nr).style.display = current;
		document.getElementById(t).style.color='#ff0000';
	}
	else if (document.all)
	{
		current = (document.all[nr].style.display == 'block') ? 'none' : 'block'
		document.all[nr].style.display = current;
		document.all[title].style.color='#ff0000';
	}
	else if (document.layers)
	{
		var i = parseInt(nr.substr(nr.length-1,1));
		var replacing = heights[i-1];
		if (shown[i])
		{
			shown[i] = false;
			replacing = -replacing;
			document.layers[nr].visibility = 'hide';
			document.layers[nr].top = safe;
		}
		else
		{
			shown[i] = true;
			document.layers[nr].visibility = 'show';
			var tempname = 'header' + i;
			document.layers[nr].top = document.layers[tempname].top + headerheight;
		}
		for (j=(i+1);j<=max;j++)
		{
			name1 = 'header' + j;
			document.layers[name1].top += replacing;
			if (shown[j])
			{
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
</STYLE>

</HEAD>
<BODY>
<DIV id=test><A HREF="#" onclick="Test()">Test div</A></DIV>
<DIV id=title1 onclick="blocking('text1', 'title1');">Title 1</DIV>
<DIV class=para id=text1>This is text of title 1</DIV>
<A id=title2 HREF="javascript:blocking('text2', 'title2')">Title 2</A><BR>
<DIV class=para id=text2>This is text of title 2</DIV>

<DIV id=colorchange onMouseOver="this.style.cursor='hand'">test of color change</DIV><BR>
<DIV class=lnk id=curchange onMouseOver="cursorchange('curchange');">test of mouse over</DIV>

<BR><BR>
This is a test
<BR>
<BR>Regular expressions test<BR>
<?PHP
$original = "~female~ and ~12~ and ~female2~ ~34~~11~";
print "Original $original<BR>\n";
preg_match_all("/~[^\x20|^~]*~/", $original, $arr);
print_r($arr);
print "<BR>";
foreach($arr[0] as $val) {
	print "$val ";
	$str = str_replace('~', '', $val);
	print "$str<BR>\n";
	$new = "<IMG SRC=emoticons/$str.gif>";
	$original = str_replace($val, "<IMG SRC=emoticons/$str.gif>", $original);
}
print "New original: $original<BR>\n";
$document_root = $_SERVER['DOCUMENT_ROOT'];
print "Document root: $document_root<BR>\n";
?>
<BR>End of test<BR>
<FORM name=frmMessage >
<INPUT type=text name=txtMessage size=60>
<INPUT type=button value=ModalOpen onclick="OpenModal()">
</FORM>

</BODY>
</HTML>
