<html>
<head>
</head>
<body>
<?
$remod=$_GET['remod'];
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
$modify=$_GET['modify'];
// checks to see if they have pushed the "Modify" button
if (!$modify)
{
// checks to see if they have input a record number to view
// if (!$rec_num)
// {
$submitsearch=$_GET['submitsearch'];
$prog1=$_GET['prog1'];
$prog2=$_GET['prog2'];
$prog3=$_GET['prog3'];
$homet=$_GET['homet'];
$homes=$_GET['homes'];
$htclass=$_GET['htclass'];
$startyear=$_GET['startyear'];
$endyear=$_GET['endyear'];
$UVAspouse=$_GET['UVAspouse'];
$hsname=$_GET['hsname'];
$coedhs=$_GET['coedhs'];
$directfromhs=$_GET['directfromhs'];
$keyword=$_GET['keyword'];
// checks to see if they have pushed the button to submit a record number for a search
if (!$submitsearch)
{
$connection=mysql_connect("localhost", "leffler", "leffler");
mysql_select_db("leffler", $connection);
$prog1_query="select distinct school from iph order by school";
$prog2_query="select distinct school2 from iph order by school2";
// $prog3_query="select distinct school3 from iph order by school3";
$homestate_query="select distinct homestate from iph order by homestate";
$startyear_query="select distinct entereduva from iph order by entereduva";
$endyear_query="select distinct leftuva from iph order by leftuva";
$htclass_query="select distinct htclassification from iph order by htclassification";
$uvaspouse_query="select distinct spouseAtUVA from iph order by spouseAtUVA";
$highschool_query="select distinct highschool from iph order by highschool";
$coedhs_query="select distinct coedhighschool from iph order by coedhighschool";
$direcths_query="select distinct directlyFromHS from iph order by directlyFromHS";
?>
<form method="GET" action="test.php">
<center><h3>Input search values</h3></center>
<table>
<tr>
<td>
First UVA school/program
</td>
<td>
<select name="prog1">
<option selected value=""></option>
<?
$result_prog1 = mysql_query ($prog1_query, $connection);
while ($option_prog1 = mysql_fetch_row($result_prog1))
{
?>
<option value="<? echo $option_prog1[0]; ?>"><? echo $option_prog1[0]; ?></option>
<?
}
?>
</select>
</td>
<td>
Second UVA school/program
</td>
<td>
<select name="prog2">
<option selected value=""></option>
<?
$result_prog2 = mysql_query ($prog2_query, $connection);
while ($option_prog2 = mysql_fetch_row($result_prog2))
{
?>
<option value="<? echo $option_prog2[0]; ?>"><? echo $option_prog2[0]; ?></option>
<?
}
?>
</select>
</td>
<td>
Hometown
</td>
<td>
<input type="text" name="homet">
</td>
</tr>
<tr>
<td>
Home state
</td>
<td>
<select name="homes">
<option selected value=""></option>
<?
$result_homestate = mysql_query ($homestate_query, $connection);
while ($option_homestate = mysql_fetch_row($result_homestate))
{
?>
<option value="<? echo $option_homestate[0]; ?>"><? echo $option_homestate[0]; ?></option>
<?
}
?>
</select>
</td>
<td>
Hometown classification
</td>
<td>
<select name="htclass">
<option selected value=""></option>
<?
$result_htclass = mysql_query ($htclass_query, $connection);
while ($option_htclass = mysql_fetch_row($result_htclass))
{
?>
<option value="<? echo $option_htclass[0]; ?>"><? echo $option_htclass[0]; ?></option>
<?
}
?>
</select>
</td>
<td>
Year started at UVA
</td>
<td>
<select name="startyear">
<option selected value=""></option>
<?
$result_startyear = mysql_query ($startyear_query, $connection);
while ($option_startyear = mysql_fetch_row($result_startyear))
{
?>
<option value="<? echo $option_startyear[0]; ?>"><? echo $option_startyear[0]; ?></option>
<?
}
?>
</select>
</td>
</tr>
<tr>
<td>
Year left UVA
</td>
<td>
<select name="endyear">
<option selected value=""></option>
<?
$result_endyear = mysql_query ($endyear_query, $connection);
while ($option_endyear = mysql_fetch_row($result_endyear))
{
?>
<option value="<? echo $option_endyear[0]; ?>"><? echo $option_endyear[0]; ?></option>
<?
}
?>
</select>
</td>
<td>
Spouse at UVA
</td>
<td>
<select name="UVAspouse">
<option selected value=""></option>
<?
$result_uvaspouse = mysql_query ($uvaspouse_query, $connection);
while ($option_uvaspouse = mysql_fetch_row($result_uvaspouse))
{
?>
<option value="<? echo $option_uvaspouse[0]; ?>"><? echo $option_uvaspouse[0]; ?></option>
<?
}
?>
</select>
</td>
<td>
High school type
</td>
<td>
<select name="hsname">
<option selected value=""></option>
<?
$result_highschool = mysql_query ($highschool_query, $connection);
while ($option_highschool = mysql_fetch_row($result_highschool))
{
?>
<option value="<? echo $option_highschool[0]; ?>"><? echo $option_highschool[0]; ?></option>
<?
}
?>
</select>
</td>
</tr>
<tr>
<td>
Coed high school
</td>
<td>
<select name="coedhs">
<option selected value=""></option>
<?
$result_coedhs = mysql_query ($coedhs_query, $connection);
while ($option_coedhs = mysql_fetch_row($result_coedhs))
{
?>
<option value="<? echo $option_coedhs[0]; ?>"><? echo $option_coedhs[0]; ?></option>
<?
}
?>
</select>
</td>
<td>
Directly from high school
</td>
<td>
<select name="directfromhs">
<option selected value=""></option>
<?
$result_direcths = mysql_query ($direcths_query, $connection);
while ($option_direcths = mysql_fetch_row($result_direcths))
{
?>
<option value="<? echo $option_direcths[0]; ?>"><? echo $option_direcths[0]; ?></option>
<?
}
?>
</select>
</td>
</tr>
<tr>
<td>
Keyword search on all fields*
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
<input type="reset" name="reset button" value="Reset search criteria">
</center>
</td>
</tr>
</table>
*This is a boolean search.  It will default to an "or" search.<br>
That means that if you search on two words, it will return all rows that have <I>either</i> of the two words, not both.<br>
If you want to do an "and" search, type the + sign before each word.  So, typing "+africa +asia" would search<br>
for rows where the words "africa" and "asia" both appear somewhere in the row.  The search is not case sensitive.<br>
For more information on doing a boolean search, click <a href="http://www.mysql.org/doc/refman/4.1/en/fulltext-boolean.html">here</a>.
</form>
<?
}
// the bracket below ends the else statement that runs if they do want to modify more records
}
}
?>
</body>
</html>
