<?
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
if (!$submitsearch)
{
?>
<html>
<head>
</head>
<body>
<form action="iph_search.php" method="POST">
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
else
{
$scount = 0;
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
$query = "select accessionNumber from iph where ";
for ($i = 0; $i < count($search); $i++)
{
$query .= $searchstr[$i];
$query .= " = ";
$query .= "'" . $search[$i] . "'";
if ($i != count($search) - 1)
{
$query .= " or ";
}
}
echo $query;
$connection=mysql_connect("localhost", "leffler", "leffler");
mysql_select_db("leffler", $connection);
$result = mysql_query($query, $connection);
for ($i = 0; $i < mysql_num_rows ($result); $i++)
{
$fin_result[$i] = mysql_result ($result, $i, 0);
}
?>
<center><h3>Choose an Accession Number to edit</h3></center>
<form action="iph_modify.php" method="POST">
<table border="1" cellpadding="2">
<tr>
<?
for ($i=0; $i < count($fin_result); $i++)
{
if (($i+1) % 12 != 0)
{
?>
<td>
<input type="radio" name="ANumber" value="<? echo $fin_result[$i]; ?>"> <? echo $fin_result[$i]; ?>
</td>
<?
}
else
{
?>
<td>
<input type="radio" name="ANumber" value="<? echo $fin_result[$i]; ?>"> <? echo $fin_result[$i]; ?>
</td>
</tr>
<tr>
<?
}
}
?>
</tr>
</table>
<center>
<input type="submit" name="ANumbersearch" value="Edit this Accession Number"></center>
</form>
<?
}
?>