<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta content="text/html; charset=windows-1255"
 http-equiv="content-type">
  <title>Add download linuxfun</title>
</head>
<body dir="rtl">
<br>
<div style="text-align: center;">הוספת הורדה תוכנה<br>
</div>
<form action="php/dwn_app.php" method="post" id="dwn_app" target="_self">
  <div style="text-align: right;"> </div>
  <br>
  <table style="width: 100%; text-align: left;" border="0"
 cellpadding="2" cellspacing="2">
    <tbody>
      <tr>
        <td style="vertical-align: top; text-align: right;">שם ההורדה:<br>
        </td>
        <td style="vertical-align: top; text-align: right;"><input
 name="dwn_app_name" size="45" maxlenght="45"></td>
      </tr>
      <tr>
        <td style="vertical-align: top; text-align: right;">מספר תוכנה:<br>
        </td>
        <td style="vertical-align: top; text-align: right;"><input
 name="dwn_app_num" size="20" maxlenght="20"> <a href="apps_list.php"><font
 size="-1">רשימת תוכנות</font></a><br>
        </td>
      </tr>
      <tr>
        <td style="vertical-align: top; text-align: right;">סוג הורדה:<br>
        </td>
        <td style="vertical-align: top; text-align: right;">
        <select name="dwn_type">
        <option>נא לבחור את סוג ההורדה</option>
        <option> בינארי </option>
        <option> קוד מקור </option>
        </select>
        </td>
      </tr>
      <tr>
        <td style="vertical-align: top; text-align: right;">הפצה:<br>
        </td>
        <td style="vertical-align: top; text-align: right;"><input
 name="dwn_app_distro" size="45" maxlenght="45"></td>
      </tr>
      <tr>
        <td style="vertical-align: top; text-align: right; width: 10%;">הערות:<br>
        </td>
        <td style="vertical-align: top; text-align: right;"><textarea
 name="dwn_app_desc" rows="3" cols="45"></textarea></td>
      </tr>
      <tr>
        <td style="vertical-align: top; text-align: right;">מקור ההורדה:<br>
        </td>
        <td style="vertical-align: top; text-align: right;"><input
 name="dwn_app_location" size="45" maxlenght="45" value="http://"
 style="direction: ltr;"></td>
      </tr>
    </tbody>
  </table>
  <input value="שלח קישור" type="submit"> </form>
הערות:<br>
1. נא לבדוק שההורדה אינה קיימת כבר לפני שמוסיפים.<br>
</body>
</html>
