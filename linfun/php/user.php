<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta content="text/html; charset=windows-1255">
</head>
<body dir="rtl">

<? /* התחברות למערכת */ ?>

<form action="login.php" method="post" id="login"><span
 style="text-decoration: underline;"><font size="+3">
 התחברות למערכת:</span><br>
  <table style="text-align: left; width: 300px;" border="0"
 cellpadding="2" cellspacing="0">
    <tbody>
      <tr>
        <td style="vertical-align: top; text-align: right; width: 60px;"><font
 size="-1">כינוי:</font></td>
        <td style="vertical-align: top; text-align: right;"> <input
 name="login_name=" text="" example="" size="15" maxlength="15"
 dir="rtl"> </td>
        <td style="vertical-align: top;"><br>
        </td>
      </tr>
      <tr>
        <td style="vertical-align: top; text-align: right;"><font
 size="-1">סיסמא:</font></td>
        <td style="vertical-align: top; text-align: right;"><input
 name="login_password" text="" example="" size="15" maxlength="15"
 dir="rtl" type="password"></td>
        <td style="vertical-align: top; text-align: right;"><br>
        </td>
      </tr>
      <tr>
        <td style="vertical-align: top; text-align: right; width: 70px;"><font
 size="-1"><br>
        </font></td>
        <td style="vertical-align: top; text-align: left; width: 1%;"><input
 value="" name="login)remember" text="" example="" dir="rtl"
 type="checkbox"> <font size="-1">זכור אותי</font><br>
        </td>
        <td style="vertical-align: top;"><br>
        </td>
      </tr>
    </tbody>
  </table>
  <input value="התחברות" type="submit"> </form>
<hr> 

<? /* השבת סיסמא */ ?>

<form action="getpass.php" method="post" id="pass_retrive"> 
  <span style="text-decoration: underline;"><font size="+3"> שכחתי את הסיסמא:</span><br></font>
  <table style="text-align: left; width: 300px;" border="0"
 cellpadding="0" cellspacing="0">
    <tbody>
      <tr>
        <td
 style="text-align: right; width: 75px; vertical-align: middle;">אימייל:
        </td>
        <td
 style="text-align: right; width: 1%; vertical-align: middle;"><input
 name="retrive_password" text="" example="" size="25" maxlength="25"
 dir="rtl"></td>
        <td style="text-align: center; vertical-align: middle;"><input
 value="השב סיסמא" type="submit"> </td>
      </tr>
    </tbody>
  </table>
  
</form>
<hr>
<? /* הרשמה */ ?>
<form action="register.php" method="post" id="register">
  <span style="text-decoration: underline;"><font size="+3">הרשמה מהירה:</span></font><br>
  <font color="red">
<? echo $reg_nickname_msg;
   echo $password_msg; 
   echo $password2_msg;
   echo $email_msg;
   echo $pass_match_msg;
?>
 </font>
  <table style="text-align: left; width: 300px;" border="0"
 cellpadding="2" cellspacing="2">
    <tbody>
      <tr>

      <tr>
        <td style="vertical-align: top; text-align: right;"><font
 size="-1">כינוי<br>
        </font></td>
        <td style="vertical-align: top;"><input name="reg_nickname"
 text="" example="" size="25" maxlength="25" dir="rtl"></td>
      </tr>
      <tr>
        <td style="vertical-align: top; text-align: right;">סיסמא<br>
        </td>
        <td style="vertical-align: top;"><input name="reg_password"
 text="" example="" size="25" maxlength="25" dir="rtl" type="password"></td>
      </tr>
      <tr>
        <td style="vertical-align: top; text-align: right;">אימות סיסמא<br>
        </td>
        <td style="vertical-align: top;"><input name="reg_password2"
 text="" example="" size="25" maxlength="25" dir="rtl" type="password"></td>
      </tr>
      <tr>
        <td style="vertical-align: top; text-align: right;"><font
 size="-1">תיבת דואל<br>
        </font></td>
        <td style="vertical-align: top;"><input name="reg_email" text=""
 example="" size="25" maxlength="25" dir="rtl"></td>
      </tr>
      <tr>
        <td style="vertical-align: top; text-align: right;"><font
 size="-1">רשת מסרים מיידיים<br>
        </font></td>
        <td style="vertical-align: top; text-align: center;">
        <select name="reg_mess_net">
        <option value="ללא"> ללא </option>
        <option value="icq"> icq </option>
        <option value="msn"> msn </option>
        <option value="gadu"> gadu </option>
        <option value="jabber"> jabber </option>
        <option value="yahoo"> yahoo </option>
        </select>
        </td>
      </tr>
      <tr>
        <td style="vertical-align: top; text-align: right;"><font
 size="-1">זיהוי בתוכנת המסרים<br>
        </font></td>
        <td style="vertical-align: top;"><input name="reg_mess_id"
 text="" example="" size="25" maxlength="25" dir="rtl"></td>
      </tr>
      <tr>
        <td style="vertical-align: top; text-align: right;"><font
 size="-1">הפצה בשימוש כרגע</font><br>
        </td>
        <td style="vertical-align: top; text-align: center;">
        <select name="reg_distro">
        <option value="חסוי"> חסוי </option>
        <option value="nodist"> ללא </option>
        <option value="Debian"> Debian </option>
	<option value="Fedora"> Fedora </option>
        <option value="Gentoo"> Gentoo </option>
	<option value="Linspire"> Linspire </option>
	<option value="LiveCD"> LiveCD distro </option>
	<option value="Mandrake"> Mandrake </option>
        <option value="Red hat"> Red Hat </option>
        <option value="Slackware"> Slackware </option>
	<option value="Suse"> Suse </option>
	<option value="Turbo linux"> Turbo linux </option>
	<option value="אחרת"> אחרת </option>
	</select>
        </td>
      </tr>
      <tr>
        <td style="vertical-align: top; text-align: left;"><input
 value="אתחול" name="restart" text="" example="" dir="rtl" type="reset"></td>
        <td style="vertical-align: top; text-align: right;"><input
 value="אישור" name="reg_accept" text="" example="" dir="rtl"
 type="submit"></td>
      </tr>
    </tbody>
  </table>
</form>
<br>
<br>
</body>
</html>
