<?
// echo "Hello";
$connection=mysql_connect("localhost", "leffler", "leffler");
mysql_select_db("leffler", $connection);
$query = "select * from iph";
$result = mysql_query($query, $connection);

for ($i = 0; $i < mysql_num_fields($result); $i++)
{
echo mysql_field_name($result, $i) . ", ";
}
?>