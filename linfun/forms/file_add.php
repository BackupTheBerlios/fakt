<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta content="text/html; charset=windows-1255"
 http-equiv="content-type">
  <title>Files linuXfun</title>
</head>
<body dir="rtl">
<br>
<div style="text-align: center;">העלאת קובץ<br>
</div>
<br>
<form action="php/art.php" method="post" id="artwork_form"
 target="_self">
  <div style="text-align: right;"> </div>
  <table style="width: 100%; text-align: left;" border="0"
 cellpadding="2" cellspacing="2">
    <tbody>
      <tr>
        <td style="vertical-align: top; text-align: right;">שם הקובץ:<br>
        </td>
        <td style="vertical-align: top; text-align: right;"><input
 name="art_name" size="45" maxlenght="45" type="text"></td>
      </tr>
      <tr>
        <td style="vertical-align: top; text-align: right; width: 10%;">תיאור:<br>
        </td>
        <td style="vertical-align: top; text-align: right;"><textarea
 name="art_desc" rows="3" cols="45"></textarea></td>
      </tr>
      <tr>
        <td style="vertical-align: top; text-align: right; width: 10%;">קובץ:<br>
        </td>
        <td style="vertical-align: top; text-align: right;"><input
 name="artfile" type="file"></td>
      </tr>
      <tr>
        <td style="vertical-align: top; text-align: right; width: 10%;">סוג:<br>
        </td>
        <td style="vertical-align: top; text-align: right;">
        <select name="art_type">
        <option>נא לבחור את סוג הקובץ</option>
        <option>בוטספלאשים</option>
        <option>מוזיקה</option>
        <option>קוד מקור</option>
        <option>רקעים</option>
        <option>רקעים לקיקר</option>
        </select>
        </td>
      </tr>
      <tr>
        <td style="vertical-align: top; text-align: right; width: 1%;"><br>
        </td>
        <td style="vertical-align: top;"><br>
        </td>
      </tr>
    </tbody>
  </table>
  <input name="othersource" type="checkbox"> הקובץ נמצא כבר במקור אחר: <input
 name="othersource_link" size="45" maxlenght="45" value="http://"
 dir="ltr" type="text"> <br>
  <input value="העלה קובץ" type="submit"> </form>
נא לקרוא בעיון את ההוראות לפני שמעלים קובץ:<br>
1. אין להעלות קבצים שאין להם קשר ללינוקס.<br>
2. אין להעלות קבצים שנלקחו ממקורות לא ישראליים.<br>
3. אין להעלות קבצים אשר קיימים כבר במקורות ישראליים אחרים, במקרה כזה יש
לציין רק קישור לאותו מקום.<br>
4. מי שייתפס מעלה קבצים באופן לא חוקי יקבל הרחקה מהאתר.<br>
5. את הקבצים יש להעלות בפורמט tar.bz2.<br>
6. אם הקובץ גדול מ350 קילו הוא יחכה לאישור ולא יעלה אוטומטית.
</body>
</html>
