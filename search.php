<?php

require_once './alumna.php';

$connection=mysql_connect("localhost", "alumnaAdmin", "alumna%adm%orion");
mysql_select_db("alumna", $connection);

function finfo($name, $label) {
    return array(
        'name'   => $name,
        'label'  => $label,
        'values' => array()
    );
}

$sql = 'SELECT * FROM iph';
$fields = array(
    'school'           => finfo('prog1', 'First UVa school/program'),
    'school2'          => finfo('prog2', 'Second UVa school/program'),
    'homestate'        => finfo('homet', 'Hometown'),
    'htclassification' => finfo('htclass', 'Hometown Classification'),
    'entereduva'       => finfo('startyear', 'Year started at UVa'),
    'leftuva'          => finfo('endyear', 'Year left UVa'),
    'spouseAtUVA'      => finfo('UVAspouse', 'Spouse at UVa'),
    'highschool'       => finfo('hsname', 'High school type'),
    'coedhighschool'   => finfo('coedhs', 'Coed high school'),
    'directlyFromHS'   => finfo('directfromhs', 'Directly from high school')
);

$log = '';

// Set the initial set/array for each field. Populate it from the database.
$query = mysql_query($sql, $connection);
while ($row = mysql_fetch_assoc($query)) {
    $row_keys = array_keys($row);
    foreach ($fields as $f => $info) {
        $value = $row[$f];
        if (isset($value) && $value != null) {
            $info['values'][$value] = 1;
        }
        $fields[$f] = $info;
    }
}
$log .= print_r($fields, true) . "\n";

// Now convert the sets into sorted lists.
foreach ($fields as $f => $info) {
    $keys = array_keys($info['values']);
    sort($keys);
    $info['values'] = array();
    foreach ($keys as $k) {
        array_push($info['values'], array('value' => $k));
    }
    $fields[$f] = $info;
}
$log .= print_r($fields, true) . "\n";

// Now add in the free-form text fields.
$fields['hometown'] = array('name' => 'homet', 'label' => 'Hometown');

$fields['debug'] = array(
    # array('key' => 'log',      'value' => $log),
    # array('key' => 'fields',   'value' => print_r($fields, true)),
    array('key' => 'row_keys', 'value' => print_r($row_keys, true))
);

echo $mustache->render($templates['search'], $fields,
    array(
        'header'      => $templates['header'],
        'footer'      => $templates['footer'],
        'queryselect' => $templates['queryselect'],
        'querytext'   => $templates['querytext']
    )
);

