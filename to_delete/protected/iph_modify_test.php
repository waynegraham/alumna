<html>
<head>
</head>
<body>
<?
$remod=$_POST['remod'];
$rec_num=$_GET['rec_num'];
// if $remod is "NO" that means they don't want to edit any more records
if ($remod=="NO")
{
?>
<center>
<h1>THANK YOU</h1>
</center>
<?
}
// this else runs if they do want to modify more records
else
{
$modify=$_POST['modify'];
// checks to see if they have pushed the "Modify" button
if (!$modify)
{
// checks to see if they have input a record number to view
if (!$rec_num)
{
$submitsearch=$_POST['submitsearch'];
$prog1=$_POST['prog1'];
$prog2=$_POST['prog2'];
$prog3=$_POST['prog3'];
$homet=$_POST['homet'];
$homes=$_POST['homes'];
$htclass=$_POST['htclass'];
$startyear=$_POST['startyear'];
$endyear=$_POST['endyear'];
$UVAspouse=$_POST['UVAspouse'];
$hsname=$_POST['hsname'];
$coedhs=$_POST['coedhs'];
$directfromhs=$_POST['directfromhs'];
$keyword=$_POST['keyword'];
// checks to see if they have pushed the button to submit a record number for a search
if (!$submitsearch)
{
?>
<html>
<head>
</head>
<body>
<form action="iph_modify_test.php" method="POST">
<center><h3>Input search values</h3></center>
<table>
<tr>
<td>
First UVA school/program
</td>
<td>
<input type="text" name="prog1">
</td>
<td>
Second UVA school/program
</td>
<td>
<input type="text" name="prog2">
</td>
<td>
Third UVA school/program
</td>
<td>
<input type="text" name="prog3">
</td>
</tr>
<tr>
<td>
Hometown
</td>
<td>
<input type="text" name="homet">
</td>
<td>
Home state
</td>
<td>
<input type="text" name="homes">
</td>
<td>
Hometown classification
</td>
<td>
<input type="text" name="htclass">
</td>
</tr>
<tr>
<td>
Year started at UVA
</td>
<td>
<input type="text" name="startyear">
</td>
<td>
Year left UVA
</td>
<td>
<input type="text" name="endyear">
</td>
<td>
Spouse at UVA
</td>
<td>
<input type="text" name="UVAspouse">
</td>
</tr>
<tr>
<td>
High school name
</td>
<td>
<input type="text" name="hsname">
</td>
<td>
Coed high school
</td>
<td>
<select name="coedhs">
<option selected value=""></option>
<option value="yes">Yes</option>
<option value="no">No</option>
</select>
</td>
<td>
Directly from high school
</td>
<td>
<select name="directfromhs">
<option selected value=""></option>
<option value="yes">Yes</option>
<option value="no">No</option>
</select>
</td>
</tr>
<tr>
<td>
Keyword search
</td>
<td colspan="3">
<input type="text" name="keyword" size=60>
</td>
<td>
</td>
<td>
</td>
</tr>
<tr>
<td colspan=6>
</td>
</tr>
<tr>
<td colspan="6">
<center>
<input type="submit" name="submitsearch" value="Search">
</center>
</td>
</tr>
</table>
</form>
</body>
</html>
<?
}
// this else runs if they have input search information
else
{
$scount = 0;
// these if statements run only if there was information input for the specific field
if ($prog1)
{
$search[$scount] = $prog1;
$searchstr[$scount] = "school";
$scount++;
}
if ($prog2)
{
$search[$scount] = $prog2;
$searchstr[$scount] = "school2";
$scount++;
}
if ($prog3)
{
$search[$scount] = $prog3;
$searchstr[$scount] = "school3";
$scount++;
}
if ($homet)
{
$search[$scount] = $homet;
$searchstr[$scount] = "hometown";
$scount++;
}
if ($homes)
{
$search[$scount] = $homes;
$searchstr[$scount] = "homestate";
$scount++;
}
if ($htclass)
{
$search[$scount] = $htclass;
$searchstr[$scount] = "htclassification";
$scount++;
}
if ($startyear)
{
$search[$scount] = $startyear;
$searchstr[$scount] = "entereduva";
$scount++;
}
if ($endyear)
{
$search[$scount] = $endyear;
$searchstr[$scount] = "leftuva";
$scount++;
}
if ($UVAspouse)
{
$search[$scount] = $UVAspouse;
$searchstr[$scount] = "spouseAtUVA";
$scount++;
}
if ($hsname)
{
$search[$scount] = $hsname;
$searchstr[$scount] = "highschool";
$scount++;
}
if ($coedhs)
{
$search[$scount] = $coedhs;
$searchstr[$scount] = "coedhighschool";
$scount++;
}
if ($directfromhs)
{
$search[$scount] = $directfromhs;
$searchstr[$scount] = "directlyFromHS";
$scount++;
}
if ($keyword)
{
$search[$scount] = $keyword;
$searchstr[$scount] = "keyword";
$scount++;
}
// this builds the query from the information entered
$query = "select * from iph where ";
for ($i = 0; $i < count($search); $i++)
{
$query .= $searchstr[$i];
$query .= " = ";
$query .= "'" . $search[$i] . "'";
// this if statement doesn't add an 'or' to the query if the last search variable has already been entered
if ($i != count($search) - 1)
{
$query .= " or ";
}
// the bracket below ends the for ($i = 0; $i < count($search); $i++)
}
// HERE STARTS THE DISPLAY OF THE MATCHING RECORDS
$next_page=$_POST['next_page'];
// This if checks to see if the next_page button has been pushed
// if it hasn't, then the query has not yet been run
if (!$next_page)
{
$connection=mysql_connect("localhost", "leffler", "leffler");
mysql_select_db("leffler", $connection);
$result = mysql_query($query, $connection);
// $row_page is the number of rows to be displayed per page
$rows_page = 20;
// $num_page keeps track of the page number
$num_page = 1;
// $dis_field contains the fields to be viewed for each record
$dis_field = array("school", "school2", "school3", "hometown", "homestate", "entereduva", "leftuva","htclassification", "highschool", "coedhighschool", "directlyFromHS", "spouseAtUVA");
// what follows builds the first page of results
// builds table of the matching rows
?>
<form action="iph_modify_test.php" method="POST">
<table border="1" cellpadding="2">
<tr><td colspan="<? echo count($dis_field) + 2; ?>">
<center><h3>Choose a record to edit</h3></center>
</td>
</tr>
<?
// this for loop builds each row
// $i_page is the row number
for ($i_page = (($num_page - 1) * 20) + 1; $i_page <= $num_page * $rows_page; $i_page++)
{
$fin_result = mysql_fetch_assoc($result);
?>
<tr>
<td>
<? echo $i_page; ?></td>
<td>
<a href="http://francesca.lib.virginia.edu/mam3tc/leffler/iph_modify_test.php?rec_num=<? echo $fin_result[accessionNumber]; ?>"><? echo $fin_result[accessionNumber]; ?></a>
</td>
<?
// this for loop builds each cell
for ($i_row = 1; $i_row <= count($dis_field); $i_row++)
{
?>
<td>
<? 
//$display_result = $dis_field[$i_row];
//echo $fin_result[$display_result]; 
?>
</td>
<?
}
?>
</tr>
<?
}
?>
<tr><td colspan="<? echo count($dis_field) + 2; ?>">
<input type="submit" name="next_page" value="View next <? echo $rows_page; ?> summaries">
</td></tr>
</table>
</form>
<?
$num_page++;
}
/* 
// this else runs if the next_page button has been pushed
// if it has, then the query has been run and this is at least the second page of results
else
{
// builds second page and beyond of the table of the matching rows
?>
<center><h3>Choose a record to edit</h3></center>
<form action="iph_modify_test.php" method="POST">
<table border="1" cellpadding="2">
<tr>
<?
// this for loop builds each row
// $i_page is the row number
for ($i_page = (($num_page - 1) * 20) + 1; $i_page <= $num_page * $rows_page; $i_page++)
{
$fin_result = mysql_fetch_assoc($result);
?>
<td>
<? echo $i_page; ?></td>
<td>
<a href="http://francesca.lib.virginia.edu/mam3tc/leffler/iph_modify_test.php?rec_num=<? echo $fin_result[accessionNumber]; ?>"><? echo $fin_result[accessionNumber]; ?></a>
</td>
<?
// this for loop builds each cell
for ($i_row = 1; $i_row <= count($dis_field); $i_row++)
{
?>
<td>
<? 
$display_result = $dis_field[$i_row];
echo $fin_result[$display_result];
?>
</td>
<?
}
?>
</tr>
<?
}
?>
<tr><td colspan="<? echo count($dis_field) + 2; ?>">
<input type="submit" name="next_page" value="View next <? echo $rows_page; ?> summaries">
</td></tr>
</table>
</form>
<?
$num_page++;
// the bracket below ends the else statement to build the pages after the first
}*/
// the bracket below ends the else statement which runs if they have input search information
}
// the bracket below ends the if statement which runs if they have not input a record number to search
}
// this else runs if they have put in a record number to search
else
{
$connection=mysql_connect("localhost", "leffler", "leffler");
mysql_select_db("leffler", $connection);
$query1="select * from iph where accessionNumber = " . $rec_num;
$result1 = mysql_query($query1, $connection);
$fin_result1 = mysql_fetch_array($result1, MYSQL_NUM);
?>
<table>
<tr>
<td colspan="8">
<center>
<h3>Record Accession Number: <? echo $fin_result1[0]; ?></h3>
</center>
</td>
</tr>
<form action="iph_modify_test.php" method="POST">
<tr>
<?
// this for loop builds the table with the results for single records
for ($i = 9; $i <= 128; $i++)
{
$field[$i] = mysql_field_name($result1, $i);
// this if statement runs if it is not the last cell in a row
if (($i-8) % 3 != 0)
{
?>
<td width="40">
<? echo $field[$i]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo $field[$i]; ?>" rows=<? echo $rows; ?> cols=20>
<? echo $fin_result1[$i]; ?>
</textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo $field[$i]; ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
}
// this else runs if it is the last cell in a row
else
{
?>
<td width="40">
<? echo $field[$i]; ?>
</td>
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo $field[$i]; ?>" rows=<? echo $rows; ?> cols=20>
<? echo $fin_result1[$i]; ?>
</textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo $field[$i]; ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
</tr>
<tr>
<?
// the bracket below ends the else that runs for the last cell in a row
}
// the bracket below ends the for loop that builds the table for a single record
}
?>
<tr>
<td colspan="8">
<center>
<input type="hidden" name="rec_num" value="<? echo $fin_result1[0]; ?>">
<input type="submit" name="modify" value="Modify this record">
</center>
</td>
</tr>
</form>
</table>
<?
// the bracket below ends the else statement that runs if they have put in a record number to view
}
// the bracket below ends the if statement that runs if they have not pushed the modify button
}
// the else below runs if the modify button has been pushed
else
{
$connection=mysql_connect("localhost", "leffler", "leffler");
mysql_select_db("leffler", $connection);
$query1="select * from iph where accessionNumber = " . $rec_num;
$result1 = mysql_query($query1, $connection);
// $fin_result1 = mysql_fetch_array($result1, MYSQL_NUM);
$i1=1;
while ($i1 <= mysql_num_fields($result1))
{
$field[$i1] = mysql_field_name($result1, $i1);
$fin_result[$i1] = $_POST[$field[$i1]];
$i1++;
}
$i = 1;
while ($i <= mysql_num_fields($result1))
{
$mod_query = "update iph set " . $field[$i] . " = '" . $fin_result[$i] . "' where 
accessionNumber = " . $rec_num;
$result2 = mysql_query($mod_query, $connection);
$i++;
}
?>
<form action="iph_modify_test.php" method="POST">
<center>
<h3>Would you like to modify another record?</h3><br>
<input type="submit" name="remod" value="YES">  <input type="submit" name="remod" value="NO">
<center>
</form>
<?
// the bracket below ends the else statement that runs if they have pushed the modify button
}
// the bracket below ends the else statement that runs if they do want to modify more records
}
?>
</body>
</html>