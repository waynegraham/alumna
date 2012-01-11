<?php

include("header.html") ?>

<?

// echo "hello";
$rec_num=$_GET['rec_num'];
$rec_num2=$_GET['rec_num2'];
$remod=$_GET['remod'];
if ($remod)
{
$rec_num = 0;
$rec_num2 = 0;
}
$submitsearch=$_GET['submitsearch'];

$next_page=$_GET['next_page'];
$prev_page=$_GET['prev_page'];
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
$num_page=$_GET['num_page'];
//this if runs if they have not clicked on a record number to modify
if (!$rec_num)

{

// this if runs if they have input search information

if ($submitsearch)
{
$scount = 0;
// echo "Hello";
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
$query = "(";
// echo "It works";
}
// this builds the query from the information entered
$query .= "select accessionNumber from iph where ";
for ($i = 0; $i < count($search); $i++)
{
if ($searchstr[$i] != "keyword")
{
$query .= $searchstr[$i];
$query .= " = ";
$query .= "'" . $search[$i] . "'";

}
else
{
$query .= "match (lastName, firstName, address, city, state, zip, phoneNumber, email, canwecontact, contact2, school, school2, school3, hometown, homestate, htclassification, highschool, coedhighschool, directlyFromHS, ifNotComment, activities, married, spouseAtUVA, mothersDegree1, mothersSchool1, mothersSchool2, mothersDegree2, mothersSchool3, mothersDegree3, fathersDegree1, fathersschool1, fathersschool2, fathersdegree2, fathersschool3, fathersdegree3, comments10, sisterschooling1, sisterschooling2, sisterschooling3, brotherschooling1, brotherschooling2, brotherschooling3, comments11, familyatuva, mothersoccupation1, mothersoccupation2, mothersoccupation3, fathersoccupation1, fathersoccupation2, fathersoccupation3, comments13, othercollege1, otherconcentration1, otherdegree1, otherdate1, othercollege2, otherconcentration2, otherdegree2, otherdate2, othercollege3, otherconcentration3, otherdegree3, otherdate3, othercollege4, otherconcentration4, otherdegree4, otherdate4, othercollege5, otherconcentration5, otherdegree5, otherdate5, comments14, whychooseuva, howfinanceuva, whychooseFOC, classroomexp, interactionprofs, greatestimpact, mostremember, weekendacts, Xtracurrorgs, issues, interestsexpressed, housing, likedislikehousing, commenton27, meals, interactinCville, interactdetail, Cvillememory, partofUVAcomm, partofcomm, specincidents, vividmems, marstatusinfluence, culturalbackgroundinf, educationprepared, preparedpostUVA, commentonprepared, mostsigeventsince, impactonprofessional, otherfactors, position1, dates1, position2, dates2, position3, dates3, commentson39, volunteer1, volunteerdates1, volunteer2, volunteerdates2, volunteer3, volunteerdates3, commentson40, careerwoman, commentson41, workbarriers, commentson42, currmarry, agerange, employstatus, commenton46, addcomments) against (";
$query .= "'" . $search[$i] . "'";
$query .= " in boolean mode)) ";
$query .= "union (select distinct accessionNumber from openresponses where match (Response) against (";
$query .= "'" . $search[$i] . "'";
$query .= " in boolean mode)) order by accessionNumber";
}
// this if statement doesn't add an 'and' to the query if the last search variable has already been entered
if ($i != count($search) - 1)
{
$query .= " and ";
}
// the bracket below ends the for ($i = 0; $i < count($search); $i++)
}
// echo $query;

// HERE THE DISPLAY OF THE MATCHING RECORDS STARTS

// This if checks to see if the next_page button has been pushed

// if it hasn't, then the query has not yet been run
$connection=mysql_connect("localhost", "alumnaAdmin", "alumna%adm%orion");
mysql_select_db("eos8d_alumna", $connection);
$result_accNum = mysql_query($query, $connection);
// $result2 = mysql_query($query2, $connection);
$query2 = "select * from iph where accessionNumber = ";
$i_acc_num = 1;
while ($acc_num = mysql_fetch_array($result_accNum))
{
$query2 .= $acc_num[accessionNumber];
if ($i_acc_num < mysql_num_rows($result_accNum))
{
$query2 .= " or accessionNumber = ";
}
$i_acc_num++;
}
// echo $query2;
$result = mysql_query($query2, $connection);

// $row_page is the number of rows to be displayed per page
$rows_page = 20;

// $num_page keeps track of the page number

if (!$num_page)

{

$num_page = 1;

}

if ($next_page)
{
$num_page++;
}
elseif ($prev_page)
{
$num_page--;
}
// $dis_field contains the fields to be viewed for each record
$dis_field = array("school", "school2", "school3", "hometown", "homestate", "entereduva", "leftuva","htclassification", "highschool", "coedhighschool", "directlyFromHS", "spouseAtUVA");
$dis_field2 = array("First UVA program", "Second UVA program", "Third UVA program", "Home town", "Home state", "Year entered UVA", "Year left UVA","Home town classification", "High school type", "Coed High school?", "Directly from high school", "Spouse at UVA");
// what follows builds the first page of results

// builds table of the matching rows

?>

<form method="GET" action="ROdisplay.php">
<table border="1" cellpadding="2">

<tr>
<td colspan="<? echo count($dis_field) + 2; ?>">
<?
if (!$result)
{
?>
<center><h3>Your query returned 0 records</h3></center>
<?
}
else
{
?>
<center><h3>Your query returned <? echo mysql_num_rows($result); ?> records<br>Choose a record to view</h3></center>
<?
}
?>
</td>

</tr>

<tr>

<td>#</td>

<td>Accession<br>Number</td>
<?

// this for loop builds the top row

for ($row1 = 0; $row1 < count($dis_field); $row1++)

{

?>

<td>

<? 
echo $dis_field2[$row1]; 
?>

</td>

<?

}

?>

</tr>

<?

// this for loop builds each row

// $i_page is the row number
for ($i_page = (($num_page - 1) * 20); $i_page < $num_page * $rows_page && $i_page < mysql_num_rows($result); $i_page++)
{
?>
<tr>
<td>
<? echo $i_page + 1; ?></td>
<td>
<a href="ROdisplay.php?rec_num=<? echo mysql_result($result, $i_page, "accessionNumber") . "&submitsearch=" . $submitsearch . "&prog1=" . $prog1 . "&prog2=" . $prog2 . "&prog3=" . $prog3 . "&homet=" . $homet . "&homes=" . $homes . "&htclass=" . $htclass . "&startyear=" . $startyear . "&endyear=" . $endyear . "&UVAspouse=" . $UVAspouse . "&hsname=" . $hsname . "&coedhs=" . $coedhs . "&directfromhs=" . $directfromhs . "&keyword=" . $keyword . "&num_page=" . $num_page; ?>">
<? echo mysql_result($result, $i_page, "accessionNumber"); ?></a>
</td>
<?
// this for loop builds each cell
for ($i_row = 0; $i_row < count($dis_field); $i_row++)
{
 
?>
<td>
<?
$fin_result = mysql_result($result, $i_page, $dis_field[$i_row]);
echo $fin_result; 
 ?>
</td>
<?  
}
 
?>
</tr>
<?
}

?>

<input type="hidden" name="submitsearch" value="<? echo $submitsearch; ?>">

<input type="hidden" name="prog1" value="<? echo $prog1; ?>">

<input type="hidden" name="prog2" value="<? echo $prog2; ?>">

<input type="hidden" name="prog3" value="<? echo $prog3; ?>">

<input type="hidden" name="homet" value="<? echo $homet; ?>">

<input type="hidden" name="homes" value="<? echo $homes; ?>">

<input type="hidden" name="htclass" value="<? echo $htclass; ?>">

<input type="hidden" name="startyear" value="<? echo $startyear; ?>">

<input type="hidden" name="endyear" value="<? echo $endyear; ?>">

<input type="hidden" name="UVAspouse" value="<? echo $UVAspouse; ?>">

<input type="hidden" name="hsname" value="<? echo $hsname; ?>">

<input type="hidden" name="coedhs" value="<? echo $coedhs; ?>">

<input type="hidden" name="directfromhs" value="<? echo $directfromhs; ?>">

<input type="hidden" name="keyword" value="<? echo $keyword; ?>">

<input type="hidden" name="num_page" value=<? echo $num_page; ?>>

<input type="hidden" name="rec_num" value=<? echo $rec_num; ?>>

<tr>
<td colspan="<? echo count($dis_field) + 2; ?>">

<center>
<?
if ($num_page > 1 || mysql_num_rows($result) > 20 && mysql_num_rows($result) > $i_page)
{
if ($num_page > 1)
{
?>
<input type="submit" name="prev_page" value="View previous <? echo $rows_page; ?> summaries">
<?
}
if (mysql_num_rows($result) > 20 && mysql_num_rows($result) > $i_page)
{
?>
<input type="submit" name="next_page" value="View next <? echo $rows_page; ?> summaries">
<?
}
?>
<br>OR<br>
<?
}
?>
<a href="ROiph.php">Input new search criteria</a>
</center>
</td>
</tr>

</table>

</form>

<?

// the bracket below ends the else statement which runs if they have input search information

}

// the bracket below ends the if statement which runs if they have not input a record number to search
}

// this else runs if they have put in a record number to search
elseif ($rec_num)
{
$connection=mysql_connect("localhost", "alumnaAdmin", "alumna%adm%orion");
mysql_select_db("eos8d_alumna", $connection);
$query1="select * from iph where accessionNumber = " . $rec_num;
$result1 = mysql_query($query1, $connection);
$fin_result1 = mysql_fetch_array($result1, MYSQL_NUM);
?>
<table border="1">
<tr>
<td colspan="8">
<center>
<h3>Record Accession Number: <? echo $fin_result1[0]; ?></h3>
</center>
</td>
</tr>
<form method="GET" action="ROdisplay.php">
<tr>
<?
 
$comments[school]="First UVA program";
$comments[school2]="Second UVA program";
$comments[school3]="Third UVA program";
$comments[c4]="Comments on UVA programs";
$comments[hometown]="Home town";
$comments[homestate]="Home state";
$comments[c7]="Comments on Home town and state";
$comments[htclassification]="Home town classification";
$comments[c9]="Comments on Home town classification";
$comments[highschool]="High school classification";
$comments[c11]="Comments on High school classification";
$comments[coedhighschool]="Was your high school co-ed?";
$comments[c13]="Comments on Co-ed high school";
$comments[directlyFromHS]="Did you come directly from high school to UVA?";
$comments[c15]="Comments if not directly from high school";
$comments[activities]="Important activities before coming to UVA";
$comments[c17]="Comments on important activities";
$comments[married]="If married, was it before, during, or after UVA?";
$comments[c19]="Comments on time of marriage";
$comments[spouseAtUVA]="Was your spouse associated with UVA?";
$comments[c21]="Comments on spouses association with UVA";
$comments[mothersDegree1]="Mother's first degree";
$comments[mothersSchool1]="Mother's first college";
$comments[mothersSchool2]="Mother's second college";
$comments[mothersDegree2]="Mother's second degree";
$comments[mothersSchool3]="Mother's third college";
$comments[mothersDegree3]="Mother's third degree";
$comments[fathersDegree1]="Father's first degree";
$comments[fathersschool1]="Father's first college";
$comments[fathersschool2]="Father's second college";
$comments[fathersdegree2]="Father's second degree";
$comments[fathersschool3]="Father's third school";
$comments[fathersdegree3]="Father's third degree";
$comments[c34]="Comments on parents' schooling";
$comments[sisterschooling1]="First sister's level of schooling";
$comments[sisterschooling2]="Second sister's level of schooling";
$comments[sisterschooling3]="Third sister's level of schooling";
$comments[brotherschooling1]="First brother's level of schooling";
$comments[brotherschooling2]="Second brother's level of schooling";
$comments[brotherschooling3]="Third brother's level of schooling";
$comments[c40]="Comments on siblings' schooling";
$comments[familyatuva]="Family members at UVA";
$comments[c42]="Comments on family members at UVA";
$comments[mothersoccupation1]="Mother's first occupation";
$comments[mothersoccupation2]="Mother's second occupation";
$comments[mothersoccupation3]="Mother's third occupation";
$comments[fathersoccupation1]="Father's first occupation";
$comments[fathersoccupation2]="Father's second occupation";
$comments[fathersoccupation3]="Father's third occupation";
$comments[c48]="Comments on parents' occupations";
$comments[entereduva]="Date entered UVA";
$comments[leftuva]="Date left UVA";
$comments[othercollege1]="First other college";
$comments[otherconcentration1]="First other concentration";
$comments[otherdegree1]="First other degree";
$comments[otherdate1]="First other dates of attendance";
$comments[othercollege2]="Second other college";
$comments[otherconcentration2]="Second other concentration";
$comments[otherdegree2]="Second other degree";
$comments[otherdate2]="Second other dates of attendance";
$comments[othercollege3]="Third other college";
$comments[otherconcentration3]="Third other concentration";
$comments[otherdegree3]="Third other degree";
$comments[otherdate3]="Third other dates of attendance";
$comments[othercollege4]="Fourth other college";
$comments[otherconcentration4]="Fourth other concentration";
$comments[otherdegree4]="Fourth other degree";
$comments[otherdate4]="Fourth other dates of attendance";
$comments[othercollege5]="Fifth other college";
$comments[otherconcentration5]="Fifth other concentration";
$comments[otherdegree5]="Fifth other degree";
$comments[otherdate5]="Fifth other dates of attendance";
$comments[c71]="Comments on college experiences";
$comments[whychooseuva]="Why did you choose UVA?";
$comments[c73]="Comments on choice to attend UVA";
$comments[howfinanceuva]="How did you finance UVA?";
$comments[c75]="Comments on financing";
$comments[whychooseFOC]="Why did you choose your UVA concentration(s)?";
$comments[c77]="Comments on choice of concentration";
$comments[classroomexp]="Description of UVA classroom experiences";
$comments[c79]="Comments on classroom experiences";
$comments[interactionprofs]="Description of UVA professor interactions";
$comments[c81]="Comments on professor interactions";
$comments[greatestimpact]="Who impacted your life at UVA?";
$comments[c83]="Comments on life impact";
$comments[mostremember]="Most memorable UVA educational experience";
$comments[c85]="Comments on memorable educational experience";
$comments[weekendacts]="UVA weekend activities";
$comments[c87]="Comments on weekend activities";
$comments[Xtracurrorgs]="UVA extra-curricular organizations";
$comments[c89]="Comments on extra-curricular organizations";
$comments[issues]="Important local, state, national, and international issues while at UVA";
$comments[c91]="Comments on important issues";
$comments[interestsexpressed]="How interest in issues was expressed";
$comments[c93]="Comments on how interest was expressed";
$comments[housing]="UVA housing ";
$comments[c95]="Comments on housing";
$comments[likedislikehousing]="Likes and dislikes about housing";
$comments[c97]="Comments on likes and dislikes";
$comments[meals]="Where were meals eaten";
$comments[c99]="Comments on meal location";
$comments[interactinCville]="Did you interact outside of UVA?";
$comments[c101]="Interaction detail";
$comments[Cvillememory]="Memories of Charlottesville";
$comments[c103]="Comments on Charlottesville memories";
$comments[partofUVAcomm]="Did you feel part of UVA community?";
$comments[partofcomm]="Did you feel part of community?";
$comments[c106]="Incidents that caused these community feelings";
$comments[vividmems]="Vivid UVA memories";
$comments[c108]="Comments on vivid memories";
$comments[marstatusinfluence]="Did marital status affect UVA experiences";
$comments[c110]="Comments on influence of marital status";
$comments[culturalbackgroundinf]="Did cultural background affect UVA experiences";
$comments[c112]="Comments on influence of cultural background";
$comments[preparedpostUVA]="Did UVA prepare you for post-UVA life?";
$comments[c114]="Comments on preparedness";
$comments[mostsigeventsince]="Most significant post-UVA events";
$comments[c116]="Comments on most significant event";
$comments[impactonprofessional]="Personal factors that affected professional life";
$comments[c118]="Comments on personal factors";
$comments[otherfactors]="Other factors affecting life after the university";
$comments[c120]="Comments on other factors";
$comments[position1]="First post-UVA job";
$comments[dates1]="First job dates";
$comments[position2]="Second post-UVA job";
$comments[dates2]="Second job dates";
$comments[position3]="Third post-UVA job";
$comments[dates3]="Third job dates";
$comments[c127]="Comments on post-UVA jobs";
$comments[volunteer1]="First post-UVA volunteer position";
$comments[volunteerdates1]="First volunteer dates";
$comments[volunteer2]="Second post-UVA volunteer position";
$comments[volunteerdates2]="Second volunteer dates";
$comments[volunteer3]="Third post-UVA volunteer position";
$comments[volunteerdates3]="Third volunteer dates";
$comments[c134]="Comments on volunteering";
$comments[careerwoman]="Do you think of yourself as a career woman?";
$comments[c136]="Comments on career woman question";
$comments[workbarriers]="Barriers faced in work and volunteering";
$comments[c138]="Comments on barriers question";
$comments[currmarry]="Current marital status";
$comments[c140]="Comments on current marital status";
$comments[agerange]="Current age range";
$comments[c142]="Comments on current age range";
$comments[numchildren]="Number of children";
$comments[c144]="Comments on number of children";
$comments[employstatus]="Current employment status";
$comments[c146]="Comments on employment status";
$comments[c147]="Additional comments";
// $comments[c148]="Comments on willingness to be contacted";
// I took this last one out because it contains a bit of personal information
// For question 1
$i=11;
?>
<tr>
<td width="60">
<? echo $comments[school]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?  
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="60">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<? 
}
$i++;
?>
<td width="60">
<? echo $comments[school2]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="60">
<input type="text" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
$i++;
?>
</tr>
<tr>
<td width="60">
<? echo $comments[c4]; ?>
</td>
<?
$query2 = "select * from openresponses where accessionNumber = " . $rec_num . " and QuestionNumber = 1";
$result2 = mysql_query($query2, $connection);
$response = mysql_result($result2, 0, 'Response');
// this if statement runs for long string values
if (strlen($response) > 60)
{
$rows = floor(strlen($response)/60);
?>
<td colspan="3">
<textarea name="response1" rows=<? echo $rows; ?> cols=62><? echo $response; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td colspan="3">
<input type="text" size=78 name="response1" value="<? echo $response; ?>">
</td>
<?
}
?>
</tr>
<tr>
<td width="60">
<? echo $comments[hometown]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="60">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
<td width="60">
<? echo $comments[homestate]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="60">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="60">
<? echo $comments[c7]; ?>
</td>
<?
$query2 = "select * from openresponses where accessionNumber = " . $rec_num . " and QuestionNumber = 2";
$result2 = mysql_query($query2, $connection);
$response = mysql_result($result2, 0, 'Response');
// this if statement runs for long string values
if (strlen($response) > 60)
{
$rows = floor(strlen($response)/60);
?>
<td colspan="3">
<textarea name="response2" rows=<? echo $rows; ?> cols=62><? echo $response; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td colspan="3">
<input type="text" size=78 name="response2" value="<? echo $response; ?>">
</td>
<?
}
?>
</tr>
<tr>
<td width="40">
<? echo $comments[htclassification]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[c9]; ?>
</td>
<?
$query2 = "select * from openresponses where accessionNumber = " . $rec_num . " and QuestionNumber = 3";
$result2 = mysql_query($query2, $connection);
$response = mysql_result($result2, 0, 'Response');
// this if statement runs for long string values
if (strlen($response) > 60)
{
$rows = floor(strlen($response)/60);
?>
<td colspan="3">
<textarea name="response3" rows=<? echo $rows; ?> cols=62><? echo $response; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td colspan="3">
<input type="text" size=78 name="response3" value="<? echo $response; ?>">
</td>
<?
}
?>
</tr>
<tr>
<td width="40">
<? echo $comments[highschool]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[c11]; ?>
</td>
<?
$query2 = "select * from openresponses where accessionNumber = " . $rec_num . " and QuestionNumber = 4";
$result2 = mysql_query($query2, $connection);
$response = mysql_result($result2, 0, 'Response');
// this if statement runs for long string values
if (strlen($response) > 60)
{
$rows = floor(strlen($response)/60);
?>
<td colspan="3">
<textarea name="response4" rows=<? echo $rows; ?> cols=62><? echo $response; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td colspan="3">
<input type="text" size=78 name="response4" value="<? echo $response; ?>">
</td>
<?
}
?>
</tr>
<tr>
<td width="40">
<? echo $comments[coedhighschool]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[c13]; ?>
</td>
<?
$query2 = "select * from openresponses where accessionNumber = " . $rec_num . " and QuestionNumber = 5";
$result2 = mysql_query($query2, $connection);
$response = mysql_result($result2, 0, 'Response');
// this if statement runs for long string values
if (strlen($response) > 60)
{
$rows = floor(strlen($response)/60);
?>
<td colspan="3">
<textarea name="response5" rows=<? echo $rows; ?> cols=62><? echo $response; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td colspan="3">
<input type="text" size=78 name="response5" value="<? echo $response; ?>">
</td>
<?
}
?>
</tr>
<tr>
<td width="40">
<? echo $comments[directlyFromHS]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[c15]; ?>
</td>
<?
$query2 = "select * from openresponses where accessionNumber = " . $rec_num . " and QuestionNumber = 6";
$result2 = mysql_query($query2, $connection);
$response = mysql_result($result2, 0, 'Response');
// this if statement runs for long string values
if (strlen($response) > 60)
{
$rows = floor(strlen($response)/60);
?>
<td colspan="3">
<textarea name="response6" rows=<? echo $rows; ?> cols=62><? echo $response; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td colspan="3">
<input type="text" size=78 name="response6" value="<? echo $response; ?>">
</td>
<?
}
?>
</tr>
<tr>
<td width="40">
<? echo $comments[activities]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[c17]; ?>
</td>
<?
$query2 = "select * from openresponses where accessionNumber = " . $rec_num . " and QuestionNumber = 7";
$result2 = mysql_query($query2, $connection);
$response = mysql_result($result2, 0, 'Response');
// this if statement runs for long string values
if (strlen($response) > 60)
{
$rows = floor(strlen($response)/60);
?>
<td colspan="3">
<textarea name="response7" rows=<? echo $rows; ?> cols=62><? echo $response; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td colspan="3">
<input type="text" size=78 name="response7" value="<? echo $response; ?>">
</td>
<?
}
?>
</tr>
<tr>
<td width="40">
<? echo $comments[married]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[c19]; ?>
</td>
<?
$query2 = "select * from openresponses where accessionNumber = " . $rec_num . " and QuestionNumber = 8";
$result2 = mysql_query($query2, $connection);
$response = mysql_result($result2, 0, 'Response');
// this if statement runs for long string values
if (strlen($response) > 60)
{
$rows = floor(strlen($response)/60);
?>
<td colspan="3">
<textarea name="response8" rows=<? echo $rows; ?> cols=62><? echo $response; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td colspan="3">
<input type="text" size=78 name="response8" value="<? echo $response; ?>">
</td>
<?
}
?>
</tr>
<tr>
<td width="40">
<? echo $comments[spouseAtUVA]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[c21]; ?>
</td>
<?
$query2 = "select * from openresponses where accessionNumber = " . $rec_num . " and QuestionNumber = 9";
$result2 = mysql_query($query2, $connection);
$response = mysql_result($result2, 0, 'Response');
// this if statement runs for long string values
if (strlen($response) > 60)
{
$rows = floor(strlen($response)/60);
?>
<td colspan="3">
<textarea name="response9" rows=<? echo $rows; ?> cols=62><? echo $response; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td colspan="3">
<input type="text" size=78 name="response9" value="<? echo $response; ?>">
</td>
<?
}
?>
</tr>
<tr>
<td width="40">
<? echo $comments[mothersDegree1]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
<td width="40">
<? echo $comments[mothersSchool1]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[mothersSchool2]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
<td width="40">
<? echo $comments[mothersDegree2]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[mothersSchool3]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
<td width="40">
<? echo $comments[mothersDegree3]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[fathersDegree1]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
<td width="40">
<? echo $comments[fathersschool1]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[fathersschool2]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
<td width="40">
<? echo $comments[fathersdegree2]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[fathersschool3]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
<td width="40">
<? echo $comments[fathersdegree3]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[c34]; ?>
</td>
<?
$query2 = "select * from openresponses where accessionNumber = " . $rec_num . " and QuestionNumber = 10";
$result2 = mysql_query($query2, $connection);
$response = mysql_result($result2, 0, 'Response');
// this if statement runs for long string values
if (strlen($response) > 60)
{
$rows = floor(strlen($response)/60);
?>
<td colspan="3">
<textarea name="response10" rows=<? echo $rows; ?> cols=62><? echo $response; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td colspan="3">
<input type="text" size=78 name="response10" value="<? echo $response; ?>">
</td>
<?
}
?>
</tr>
<tr>
<td width="40">
<? echo $comments[sisterschooling1]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
<td width="40">
<? echo $comments[sisterschooling2]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[sisterschooling3]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
<td width="40">
<? echo $comments[brotherschooling1]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[brotherschooling2]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
<td width="40">
<? echo $comments[brotherschooling3]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[c40]; ?>
</td>
<?
$query2 = "select * from openresponses where accessionNumber = " . $rec_num . " and QuestionNumber = 11";
$result2 = mysql_query($query2, $connection);
$response = mysql_result($result2, 0, 'Response');
// this if statement runs for long string values
if (strlen($response) > 60)
{
$rows = floor(strlen($response)/60);
?>
<td colspan="3">
<textarea name="response11" rows=<? echo $rows; ?> cols=62><? echo $response; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td colspan="3">
<input type="text" size=78 name="response11" value="<? echo $response; ?>">
</td>
<?
}
?>
</tr>
<tr>
<td width="40">
<? echo $comments[familyatuva]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[c42]; ?>
</td>
<?
$query2 = "select * from openresponses where accessionNumber = " . $rec_num . " and QuestionNumber = 12";
$result2 = mysql_query($query2, $connection);
$response = mysql_result($result2, 0, 'Response');
// this if statement runs for long string values
if (strlen($response) > 60)
{
$rows = floor(strlen($response)/60);
?>
<td colspan="3">
<textarea name="response12" rows=<? echo $rows; ?> cols=62><? echo $response; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td colspan="3">
<input type="text" size=78 name="response12" value="<? echo $response; ?>">
</td>
<?
}
?>
</tr>
<tr>
<td width="40">
<? echo $comments[mothersoccupation1]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
<td width="40">
<? echo $comments[mothersoccupation2]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[mothersoccupation3]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
<td width="40">
<? echo $comments[fathersoccupation1]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[fathersoccupation2]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
<td width="40">
<? echo $comments[fathersoccupation3]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[c48]; ?>
</td>
<?
$query2 = "select * from openresponses where accessionNumber = " . $rec_num . " and QuestionNumber = 13";
$result2 = mysql_query($query2, $connection);
$response = mysql_result($result2, 0, 'Response');
// this if statement runs for long string values
if (strlen($response) > 60)
{
$rows = floor(strlen($response)/60);
?>
<td colspan="3">
<textarea name="response13" rows=<? echo $rows; ?> cols=62><? echo $response; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td colspan="3">
<input type="text" size=78 name="response13" value="<? echo $response; ?>">
</td>
<?
}
?>
</tr>
<tr>
<td width="40">
<? echo $comments[entereduva]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
<td width="40">
<? echo $comments[leftuva]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[othercollege1]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
<td width="40">
<? echo $comments[otherconcentration1]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[otherdegree1]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
<td width="40">
<? echo $comments[otherdate1]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>

</tr>
<tr>
<td width="40">
<? echo $comments[othercollege2]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
<td width="40">
<? echo $comments[otherconcentration2]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[otherdegree2]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
<td width="40">
<? echo $comments[otherdate2]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>

</tr>
<tr>
<td width="40">
<? echo $comments[othercollege3]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
<td width="40">
<? echo $comments[otherconcentration3]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[otherdegree3]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
<td width="40">
<? echo $comments[otherdate3]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>

</tr>
<tr>
<td width="40">
<? echo $comments[othercollege4]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
<td width="40">
<? echo $comments[otherconcentration4]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[otherdegree4]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
<td width="40">
<? echo $comments[otherdate4]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>

</tr>
<tr>
<td width="40">
<? echo $comments[othercollege5]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
<td width="40">
<? echo $comments[otherconcentration5]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[otherdegree5]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
<td width="40">
<? echo $comments[otherdate5]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[c71]; ?>
</td>
<?
$query2 = "select * from openresponses where accessionNumber = " . $rec_num . " and QuestionNumber = 14";
$result2 = mysql_query($query2, $connection);
$response = mysql_result($result2, 0, 'Response');
// this if statement runs for long string values
if (strlen($response) > 60)
{
$rows = floor(strlen($response)/60);
?>
<td colspan="3">
<textarea name="response14" rows=<? echo $rows; ?> cols=62><? echo $response; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td colspan="3">
<input type="text" size=78 name="response14" value="<? echo $response; ?>">
</td>
<?
}
?>
</tr>
<tr>
<td width="40">
<? echo $comments[whychooseuva]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[c73]; ?>
</td>
<?
$query2 = "select * from openresponses where accessionNumber = " . $rec_num . " and QuestionNumber = 15";
$result2 = mysql_query($query2, $connection);
$response = mysql_result($result2, 0, 'Response');
// this if statement runs for long string values
if (strlen($response) > 60)
{
$rows = floor(strlen($response)/60);
?>
<td colspan="3">
<textarea name="response15" rows=<? echo $rows; ?> cols=62><? echo $response; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td colspan="3">
<input type="text" size=78 name="response15" value="<? echo $response; ?>">
</td>
<?
}
?>
</tr>
<tr>
<td width="40">
<? echo $comments[howfinanceuva]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[c75]; ?>
</td>
<?
$query2 = "select * from openresponses where accessionNumber = " . $rec_num . " and QuestionNumber = 16";
$result2 = mysql_query($query2, $connection);
$response = mysql_result($result2, 0, 'Response');
// this if statement runs for long string values
if (strlen($response) > 60)
{
$rows = floor(strlen($response)/60);
?>
<td colspan="3">
<textarea name="response16" rows=<? echo $rows; ?> cols=62><? echo $response; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td colspan="3">
<input type="text" size=78 name="response16" value="<? echo $response; ?>">
</td>
<?
}
?>
</tr>
<tr>
<td width="40">
<? echo $comments[whychooseFOC]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[c77]; ?>
</td>
<?
$query2 = "select * from openresponses where accessionNumber = " . $rec_num . " and QuestionNumber = 17";
$result2 = mysql_query($query2, $connection);
$response = mysql_result($result2, 0, 'Response');
// this if statement runs for long string values
if (strlen($response) > 60)
{
$rows = floor(strlen($response)/60);
?>
<td colspan="3">
<textarea name="response17" rows=<? echo $rows; ?> cols=62><? echo $response; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td colspan="3">
<input type="text" size=78 name="response17" value="<? echo $response; ?>">
</td>
<?
}
?>
</tr>
<tr>
<td width="40">
<? echo $comments[classroomexp]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[c79]; ?>
</td>
<?
$query2 = "select * from openresponses where accessionNumber = " . $rec_num . " and QuestionNumber = 18";
$result2 = mysql_query($query2, $connection);
$response = mysql_result($result2, 0, 'Response');
// this if statement runs for long string values
if (strlen($response) > 60)
{
$rows = floor(strlen($response)/60);
?>
<td colspan="3">
<textarea name="response18" rows=<? echo $rows; ?> cols=62><? echo $response; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td colspan="3">
<input type="text" size=78 name="response18" value="<? echo $response; ?>">
</td>
<?
}
?>
</tr>
<tr>
<td width="40">
<? echo $comments[interactionprofs]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[c81]; ?>
</td>
<?
$query2 = "select * from openresponses where accessionNumber = " . $rec_num . " and QuestionNumber = 19";
$result2 = mysql_query($query2, $connection);
$response = mysql_result($result2, 0, 'Response');
// this if statement runs for long string values
if (strlen($response) > 60)
{
$rows = floor(strlen($response)/60);
?>
<td colspan="3">
<textarea name="response19" rows=<? echo $rows; ?> cols=62><? echo $response; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td colspan="3">
<input type="text" size=78 name="response19" value="<? echo $response; ?>">
</td>
<?
}
?>
</tr>
<tr>
<td width="40">
<? echo $comments[greatestimpact]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[c83]; ?>
</td>
<?
$query2 = "select * from openresponses where accessionNumber = " . $rec_num . " and QuestionNumber = 20";
$result2 = mysql_query($query2, $connection);
$response = mysql_result($result2, 0, 'Response');
// this if statement runs for long string values
if (strlen($response) > 60)
{
$rows = floor(strlen($response)/60);
?>
<td colspan="3">
<textarea name="response20" rows=<? echo $rows; ?> cols=62><? echo $response; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td colspan="3">
<input type="text" size=78 name="response20" value="<? echo $response; ?>">
</td>
<?
}
?>
</tr>
<tr>
<td width="40">
<? echo $comments[mostremember]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[c85]; ?>
</td>
<?
$query2 = "select * from openresponses where accessionNumber = " . $rec_num . " and QuestionNumber = 21";
$result2 = mysql_query($query2, $connection);
$response = mysql_result($result2, 0, 'Response');
// this if statement runs for long string values
if (strlen($response) > 60)
{
$rows = floor(strlen($response)/60);
?>
<td colspan="3">
<textarea name="response21" rows=<? echo $rows; ?> cols=62><? echo $response; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td colspan="3">
<input type="text" size=78 name="response21" value="<? echo $response; ?>">
</td>
<?
}
?>
</tr>
<tr>
<td width="40">
<? echo $comments[weekendacts]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[c87]; ?>
</td>
<?
$query2 = "select * from openresponses where accessionNumber = " . $rec_num . " and QuestionNumber = 22";
$result2 = mysql_query($query2, $connection);
$response = mysql_result($result2, 0, 'Response');
// this if statement runs for long string values
if (strlen($response) > 60)
{
$rows = floor(strlen($response)/60);
?>
<td colspan="3">
<textarea name="response22" rows=<? echo $rows; ?> cols=62><? echo $response; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td colspan="3">
<input type="text" size=78 name="response22" value="<? echo $response; ?>">
</td>
<?
}
?>
</tr>
<tr>
<td width="40">
<? echo $comments[Xtracurrorgs]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[c89]; ?>
</td>
<?
$query2 = "select * from openresponses where accessionNumber = " . $rec_num . " and QuestionNumber = 23";
$result2 = mysql_query($query2, $connection);
$response = mysql_result($result2, 0, 'Response');
// this if statement runs for long string values
if (strlen($response) > 60)
{
$rows = floor(strlen($response)/60);
?>
<td colspan="3">
<textarea name="response23" rows=<? echo $rows; ?> cols=62><? echo $response; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td colspan="3">
<input type="text" size=78 name="response23" value="<? echo $response; ?>">
</td>
<?
}
?>
</tr>
<tr>
<td width="40">
<? echo $comments[issues]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[c91]; ?>
</td>
<?
$query2 = "select * from openresponses where accessionNumber = " . $rec_num . " and QuestionNumber = 24";
$result2 = mysql_query($query2, $connection);
$response = mysql_result($result2, 0, 'Response');
// this if statement runs for long string values
if (strlen($response) > 60)
{
$rows = floor(strlen($response)/60);
?>
<td colspan="3">
<textarea name="response24" rows=<? echo $rows; ?> cols=62><? echo $response; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td colspan="3">
<input type="text" size=78 name="response24" value="<? echo $response; ?>">
</td>
<?
}
?>
</tr>
<tr>
<td width="40">
<? echo $comments[interestsexpressed]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[c93]; ?>
</td>
<?
$query2 = "select * from openresponses where accessionNumber = " . $rec_num . " and QuestionNumber = 25";
$result2 = mysql_query($query2, $connection);
$response = mysql_result($result2, 0, 'Response');
// this if statement runs for long string values
if (strlen($response) > 60)
{
$rows = floor(strlen($response)/60);
?>
<td colspan="3">
<textarea name="response25" rows=<? echo $rows; ?> cols=62><? echo $response; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td colspan="3">
<input type="text" size=78 name="response25" value="<? echo $response; ?>">
</td>
<?
}
?>
</tr>
<tr>
<td width="40">
<? echo $comments[housing]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[c95]; ?>
</td>
<?
$query2 = "select * from openresponses where accessionNumber = " . $rec_num . " and QuestionNumber = 26";
$result2 = mysql_query($query2, $connection);
$response = mysql_result($result2, 0, 'Response');
// this if statement runs for long string values
if (strlen($response) > 60)
{
$rows = floor(strlen($response)/60);
?>
<td colspan="3">
<textarea name="response26" rows=<? echo $rows; ?> cols=62><? echo $response; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td colspan="3">
<input type="text" size=78 name="response26" value="<? echo $response; ?>">
</td>
<?
}
?>
</tr>
<tr>
<td width="40">
<? echo $comments[likedislikehousing]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[c97]; ?>
</td>
<?
$query2 = "select * from openresponses where accessionNumber = " . $rec_num . " and QuestionNumber = 27";
$result2 = mysql_query($query2, $connection);
$response = mysql_result($result2, 0, 'Response');
// this if statement runs for long string values
if (strlen($response) > 60)
{
$rows = floor(strlen($response)/60);
?>
<td colspan="3">
<textarea name="response27" rows=<? echo $rows; ?> cols=62><? echo $response; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td colspan="3">
<input type="text" size=78 name="response27" value="<? echo $response; ?>">
</td>
<?
}
?>
</tr>
<tr>
<td width="40">
<? echo $comments[meals]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[c99]; ?>
</td>
<?
$query2 = "select * from openresponses where accessionNumber = " . $rec_num . " and QuestionNumber = 28";
$result2 = mysql_query($query2, $connection);
$response = mysql_result($result2, 0, 'Response');
// this if statement runs for long string values
if (strlen($response) > 60)
{
$rows = floor(strlen($response)/60);
?>
<td colspan="3">
<textarea name="response28" rows=<? echo $rows; ?> cols=62><? echo $response; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td colspan="3">
<input type="text" size=78 name="response28" value="<? echo $response; ?>">
</td>
<?
}
?>
</tr>
<tr>
<td width="40">
<? echo $comments[interactinCville]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[c101]; ?>
</td>
<?
$query2 = "select * from openresponses where accessionNumber = " . $rec_num . " and QuestionNumber = 29";
$result2 = mysql_query($query2, $connection);
$response = mysql_result($result2, 0, 'Response');
// this if statement runs for long string values
if (strlen($response) > 60)
{
$rows = floor(strlen($response)/60);
?>
<td colspan="3">
<textarea name="response29" rows=<? echo $rows; ?> cols=62><? echo $response; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td colspan="3">
<input type="text" size=78 name="response29" value="<? echo $response; ?>">
</td>
<?
}
?>
</tr>
<tr>
<td width="40">
<? echo $comments[Cvillememory]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[c103]; ?>
</td>
<?
$query2 = "select * from openresponses where accessionNumber = " . $rec_num . " and QuestionNumber = 30";
$result2 = mysql_query($query2, $connection);
$response = mysql_result($result2, 0, 'Response');
// this if statement runs for long string values
if (strlen($response) > 60)
{
$rows = floor(strlen($response)/60);
?>
<td colspan="3">
<textarea name="response30" rows=<? echo $rows; ?> cols=62><? echo $response; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td colspan="3">
<input type="text" size=78 name="response30" value="<? echo $response; ?>">
</td>
<?
}
?>
</tr>
<tr>
<td width="40">
<? echo $comments[partofUVAcomm]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
<td width="40">
<? echo $comments[partofcomm]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[c106]; ?>
</td>
<?
$query2 = "select * from openresponses where accessionNumber = " . $rec_num . " and QuestionNumber = 31";
$result2 = mysql_query($query2, $connection);
$response = mysql_result($result2, 0, 'Response');
// this if statement runs for long string values
if (strlen($response) > 60)
{
$rows = floor(strlen($response)/60);
?>
<td colspan="3">
<textarea name="response31" rows=<? echo $rows; ?> cols=62><? echo $response; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td colspan="3">
<input type="text" size=78 name="response31" value="<? echo $response; ?>">
</td>
<?
}
?>
</tr>
<tr>
<td width="40">
<? echo $comments[vividmems]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[c108]; ?>
</td>
<?
$query2 = "select * from openresponses where accessionNumber = " . $rec_num . " and QuestionNumber = 32";
$result2 = mysql_query($query2, $connection);
$response = mysql_result($result2, 0, 'Response');
// this if statement runs for long string values
if (strlen($response) > 60)
{
$rows = floor(strlen($response)/60);
?>
<td colspan="3">
<textarea name="response32" rows=<? echo $rows; ?> cols=62><? echo $response; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td colspan="3">
<input type="text" size=78 name="response32" value="<? echo $response; ?>">
</td>
<?
}
?>
</tr>
<tr>
<td width="40">
<? echo $comments[marstatusinfluence]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[c110]; ?>
</td>
<?
$query2 = "select * from openresponses where accessionNumber = " . $rec_num . " and QuestionNumber = 33";
$result2 = mysql_query($query2, $connection);
$response = mysql_result($result2, 0, 'Response');
// this if statement runs for long string values
if (strlen($response) > 60)
{
$rows = floor(strlen($response)/60);
?>
<td colspan="3">
<textarea name="response33" rows=<? echo $rows; ?> cols=62><? echo $response; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td colspan="3">
<input type="text" size=78 name="response33" value="<? echo $response; ?>">
</td>
<?
}
?>
</tr>
<tr>
<td width="40">
<? echo $comments[culturalbackgroundinf]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[c112]; ?>
</td>
<?
$query2 = "select * from openresponses where accessionNumber = " . $rec_num . " and QuestionNumber = 34";
$result2 = mysql_query($query2, $connection);
$response = mysql_result($result2, 0, 'Response');
// this if statement runs for long string values
if (strlen($response) > 60)
{
$rows = floor(strlen($response)/60);
?>
<td colspan="3">
<textarea name="response34" rows=<? echo $rows; ?> cols=62><? echo $response; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td colspan="3">
<input type="text" size=78 name="response34" value="<? echo $response; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[preparedpostUVA]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[c114]; ?>
</td>
<?
$query2 = "select * from openresponses where accessionNumber = " . $rec_num . " and QuestionNumber = 35";
$result2 = mysql_query($query2, $connection);
$response = mysql_result($result2, 0, 'Response');
// this if statement runs for long string values
if (strlen($response) > 60)
{
$rows = floor(strlen($response)/60);
?>
<td colspan="3">
<textarea name="response35" rows=<? echo $rows; ?> cols=62><? echo $response; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td colspan="3">
<input type="text" size=78 name="response35" value="<? echo $response; ?>">
</td>
<?
}
?>
</tr>
<tr>
<td width="40">
<? echo $comments[mostsigeventsince]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[c116]; ?>
</td>
<?
$query2 = "select * from openresponses where accessionNumber = " . $rec_num . " and QuestionNumber = 36";
$result2 = mysql_query($query2, $connection);
$response = mysql_result($result2, 0, 'Response');
// this if statement runs for long string values
if (strlen($response) > 60)
{
$rows = floor(strlen($response)/60);
?>
<td colspan="3">
<textarea name="response36" rows=<? echo $rows; ?> cols=62><? echo $response; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td colspan="3">
<input type="text" size=78 name="response36" value="<? echo $response; ?>">
</td>
<?
}
?>
</tr>
<tr>
<td width="40">
<? echo $comments[impactonprofessional]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[c118]; ?>
</td>
<?
$query2 = "select * from openresponses where accessionNumber = " . $rec_num . " and QuestionNumber = 37";
$result2 = mysql_query($query2, $connection);
$response = mysql_result($result2, 0, 'Response');
// this if statement runs for long string values
if (strlen($response) > 60)
{
$rows = floor(strlen($response)/60);
?>
<td colspan="3">
<textarea name="response37" rows=<? echo $rows; ?> cols=62><? echo $response; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td colspan="3">
<input type="text" size=78 name="response37" value="<? echo $response; ?>">
</td>
<?
}
?>
</tr>
<tr>
<td width="40">
<? echo $comments[otherfactors]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[c120]; ?>
</td>
<?
$query2 = "select * from openresponses where accessionNumber = " . $rec_num . " and QuestionNumber = 38";
$result2 = mysql_query($query2, $connection);
$response = mysql_result($result2, 0, 'Response');
// this if statement runs for long string values
if (strlen($response) > 60)
{
$rows = floor(strlen($response)/60);
?>
<td colspan="3">
<textarea name="response38" rows=<? echo $rows; ?> cols=62><? echo $response; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td colspan="3">
<input type="text" size=78 name="response38" value="<? echo $response; ?>">
</td>
<?
}
?>
</tr>
<tr>
<td width="40">
<? echo $comments[position1]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?><td width="40">
<? echo $comments[dates1]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[position2]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?><td width="40">
<? echo $comments[dates2]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr><td width="40">
<? echo $comments[position3]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?><td width="40">
<? echo $comments[dates3]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[c127]; ?>
</td>
<?
$query2 = "select * from openresponses where accessionNumber = " . $rec_num . " and QuestionNumber = 39";
$result2 = mysql_query($query2, $connection);
$response = mysql_result($result2, 0, 'Response');
// this if statement runs for long string values
if (strlen($response) > 60)
{
$rows = floor(strlen($response)/60);
?>
<td colspan="3">
<textarea name="response39" rows=<? echo $rows; ?> cols=62><? echo $response; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td colspan="3">
<input type="text" size=78 name="response39" value="<? echo $response; ?>">
</td>
<?
}
?>
</tr>
<tr>
<td width="40">
<? echo $comments[volunteer1]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?><td width="40">
<? echo $comments[volunteerdates1]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[volunteer2]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?><td width="40">
<? echo $comments[volunteerdates2]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[volunteer3]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?><td width="40">
<? echo $comments[volunteerdates3]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[c134]; ?>
</td>
<?
$query2 = "select * from openresponses where accessionNumber = " . $rec_num . " and QuestionNumber = 40";
$result2 = mysql_query($query2, $connection);
$response = mysql_result($result2, 0, 'Response');
// this if statement runs for long string values
if (strlen($response) > 60)
{
$rows = floor(strlen($response)/60);
?>
<td colspan="3">
<textarea name="response40" rows=<? echo $rows; ?> cols=62><? echo $response; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td colspan="3">
<input type="text" size=78 name="response40" value="<? echo $response; ?>">
</td>
<?
}
?>
</tr>
<tr>
<td width="40">
<? echo $comments[careerwoman]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[c136]; ?>
</td>
<?
$query2 = "select * from openresponses where accessionNumber = " . $rec_num . " and QuestionNumber = 41";
$result2 = mysql_query($query2, $connection);
$response = mysql_result($result2, 0, 'Response');
// this if statement runs for long string values
if (strlen($response) > 60)
{
$rows = floor(strlen($response)/60);
?>
<td colspan="3">
<textarea name="response41" rows=<? echo $rows; ?> cols=62><? echo $response; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td colspan="3">
<input type="text" size=78 name="response41" value="<? echo $response; ?>">
</td>
<?
}
?>
</tr>
<tr>
<td width="40">
<? echo $comments[workbarriers]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[c138]; ?>
</td>
<?
$query2 = "select * from openresponses where accessionNumber = " . $rec_num . " and QuestionNumber = 42";
$result2 = mysql_query($query2, $connection);
$response = mysql_result($result2, 0, 'Response');
// this if statement runs for long string values
if (strlen($response) > 60)
{
$rows = floor(strlen($response)/60);
?>
<td colspan="3">
<textarea name="response42" rows=<? echo $rows; ?> cols=62><? echo $response; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td colspan="3">
<input type="text" size=78 name="response42" value="<? echo $response; ?>">
</td>
<?
}
?>
</tr>
<tr>
<td width="40">
<? echo $comments[currmarry]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[c140]; ?>
</td>
<?
$query2 = "select * from openresponses where accessionNumber = " . $rec_num . " and QuestionNumber = 43";
$result2 = mysql_query($query2, $connection);
$response = mysql_result($result2, 0, 'Response');
// this if statement runs for long string values
if (strlen($response) > 60)
{
$rows = floor(strlen($response)/60);
?>
<td colspan="3">
<textarea name="response43" rows=<? echo $rows; ?> cols=62><? echo $response; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td colspan="3">
<input type="text" size=78 name="response43" value="<? echo $response; ?>">
</td>
<?
}
?>
</tr>
<tr>
<td width="40">
<? echo $comments[agerange]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[c142]; ?>
</td>
<?
$query2 = "select * from openresponses where accessionNumber = " . $rec_num . " and QuestionNumber = 44";
$result2 = mysql_query($query2, $connection);
$response = mysql_result($result2, 0, 'Response');
// this if statement runs for long string values
if (strlen($response) > 60)
{
$rows = floor(strlen($response)/60);
?>
<td colspan="3">
<textarea name="response44" rows=<? echo $rows; ?> cols=62><? echo $response; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td colspan="3">
<input type="text" size=78 name="response44" value="<? echo $response; ?>">
</td>
<?
}
?>
</tr>
<tr>
<td width="40">
<? echo $comments[numchildren]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[c144]; ?>
</td>
<?
$query2 = "select * from openresponses where accessionNumber = " . $rec_num . " and QuestionNumber = 45";
$result2 = mysql_query($query2, $connection);
$response = mysql_result($result2, 0, 'Response');
// this if statement runs for long string values
if (strlen($response) > 60)
{
$rows = floor(strlen($response)/60);
?>
<td colspan="3">
<textarea name="response45" rows=<? echo $rows; ?> cols=62><? echo $response; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td colspan="3">
<input type="text" size=78 name="response45" value="<? echo $response; ?>">
</td>
<?
}
?>
</tr>
<tr>
<td width="40">
<? echo $comments[employstatus]; ?>
</td>
<?
// this if statement runs for long string values
if (strlen($fin_result1[$i]) > 20)
{
$rows = floor(strlen($fin_result1[$i])/20);
?>
<td>
<textarea name="<? echo mysql_field_name($result1, $i); ?>" rows=<? echo $rows; ?> cols=20><? echo $fin_result1[$i]; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td width="40">
<input type="text" size="26" name="<? echo mysql_field_name($result1, $i); ?>" value="<? echo $fin_result1[$i]; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
<td width="40">
<? echo $comments[c146]; ?>
</td>
<?
$query2 = "select * from openresponses where accessionNumber = " . $rec_num . " and QuestionNumber = 46";
$result2 = mysql_query($query2, $connection);
$response = mysql_result($result2, 0, 'Response');
// this if statement runs for long string values
if (strlen($response) > 60)
{
$rows = floor(strlen($response)/60);
?>
<td colspan="3">
<textarea name="response46" rows=<? echo $rows; ?> cols=62><? echo $response; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td colspan="3">
<input type="text" size=78 name="response46" value="<? echo $response; ?>">
</td>
<?
}
$i++;
?>
</tr>
<tr>
</tr>
<tr>
<td width="40">
<? echo $comments[c147]; ?>
</td>
<?
$query2 = "select * from openresponses where accessionNumber = " . $rec_num . " and QuestionNumber = 47";
$result2 = mysql_query($query2, $connection);
$response = mysql_result($result2, 0, 'Response');
// this if statement runs for long string values
if (strlen($response) > 60)
{
$rows = floor(strlen($response)/60);
?>
<td colspan="3">
<textarea name="response47" rows=<? echo $rows; ?> cols=62><? echo $response; ?></textarea>
</td>
<?
}
// this else statement runs for short string and numerical values
else
{
?>
<td colspan="3">
<input type="text" size=78 name="response47" value="<? echo $response; ?>">
</td>
<?
}
?>
</tr>
<tr>
<input type="hidden" name="submitsearch" value="<? echo $submitsearch; ?>">
<input type="hidden" name="prog1" value="<? echo $prog1; ?>">
<input type="hidden" name="prog2" value="<? echo $prog2; ?>">
<input type="hidden" name="prog3" value="<? echo $prog3; ?>">
<input type="hidden" name="homet" value="<? echo $homet; ?>">
<input type="hidden" name="homes" value="<? echo $homes; ?>">
<input type="hidden" name="htclass" value="<? echo $htclass; ?>">
<input type="hidden" name="startyear" value="<? echo $startyear; ?>">
<input type="hidden" name="endyear" value="<? echo $endyear; ?>">
<input type="hidden" name="UVAspouse" value="<? echo $UVAspouse; ?>">
<input type="hidden" name="hsname" value="<? echo $hsname; ?>">
<input type="hidden" name="coedhs" value="<? echo $coedhs; ?>">
<input type="hidden" name="directfromhs" value="<? echo $directfromhs; ?>">
<input type="hidden" name="keyword" value="<? echo $keyword; ?>">
<input type="hidden" name="num_page" value=<? echo $num_page; ?>>
<input type="hidden" name="rec_num" value=<? echo $rec_num; ?>>
<td colspan="6">
<input type="hidden" name="rec_num2" value="<? echo $fin_result1[0]; ?>">
<center>
<input type="submit" name="remod" value="Back to search results">  <h3> OR </h3><a href="ROiph.php">Input new search criteria</a>
</center>
</td>
</tr>
</form>
</table>
<?  
// the bracket below ends the else statement that runs if they have put in a record number to view
}
?>
<? include("footer.html") ?>
