<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta content="text/html; charset=windows-1255"
 http-equiv="content-type">
</head>
<body dir="rtl">
<br>
<div style="text-align: center;">הוספת תוכנה<br>
</div>
<form action="php/art.php" method="post" id="artwork_form"
 target="_self">
  <div style="text-align: right;"> </div>
  <br>
  <table style="width: 100%; text-align: left;" border="0"
 cellpadding="2" cellspacing="2">
    <tbody>
      <tr>
        <td style="vertical-align: top; text-align: right;">שם התוכנה:<br>
        </td>
        <td style="vertical-align: top; text-align: right;"><input
 name="app_name" size="45" maxlenght="45"></td>
      </tr>
      <tr>
        <td style="vertical-align: top; text-align: right;">מטרה:<br>
        </td>
        <td style="vertical-align: top; text-align: right;">
        <select name="art_type">
        <option>נא לבחור את מטרת התוכנה </option>
        <option> להנות מוידאו\מולטימדיה</option>
        <option>לערוך קבצי וידאו</option>
        <option>להנות ממוזיקה</option>
        <option>לערוך קבצי ממוזיקה</option>
        <option>לבנות מוזיקה </option>
        <option>לתקלט </option>
        <option>להקליט למחשב </option>
        <option>להפעיל מערכת דימוי להפעלת משחקי קונסולה וחלונות </option>
        <option>להנות ברשת </option>
        <option>להשתמש בתוכנת עריכה גרפית </option>
        </select>
        </td>
      </tr>
      <tr>
        <td style="vertical-align: top; text-align: right; width: 10%;">תיאור:<br>
        </td>
        <td style="vertical-align: top; text-align: right;"><textarea
 name="app_desc" rows="3" cols="45"></textarea></td>
      </tr>
      <tr>
        <td style="vertical-align: top; text-align: right;">תמונה:<br>
        </td>
        <td style="vertical-align: top; text-align: right;"> <input
 name="app_pic" type="file"></td>
      </tr>
      <tr>
        <td style="vertical-align: top; text-align: right;">אתר הבית:<br>
        </td>
        <td style="vertical-align: top; text-align: right;"><input
 name="app_home" size="45" maxlenght="45" value="http://"
 style="direction: ltr;"></td>
      </tr>
    </tbody>
  </table>
  <input value="שלח קישור" type="submit"> </form>
הערות:<br>
1. לא ניתן לעלות תמונת תוכנה הגדולה מ10 קילו.<br>
3. נא לבדוק שהתוכנה אינה קיימת במאגר לפני שמוסיפים אותה.<br>
3. להוספת הורדות למשחק, נא ללכת אל "הורדה לתוכנה" ב"הוספה מהירה".<br>
</body>
</html>
