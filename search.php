<?php

require_once './alumna.php';

$connection=mysql_connect("localhost", "alumnaAdmin", "alumna%adm%orion");
mysql_select_db("alumna", $connection);

/**
 * This creates an initial field info array. It contains the name, the label, 
 * and an empty array for the set of values.
 **/
function finfo($name, $label) {
    return array(
        'name'   => $name,
        'label'  => $label,
        'values' => array()
    );
}

/**
 * This adds the row of data values into the $fields array. For each value, 
 * it's added to the values set for that field.
 **/
function add_row_values(&$fields, $row) {
    $row_keys = array_keys($row);
    foreach ($fields as $f => $info) {
        if (!isset($row[$f])) {
            continue;
        }
        $value = $row[$f];
        if (isset($value) && $value != null) {
            $info['values'][$value] = 1;
        }
        $fields[$f] = $info;
    }
}

/**
 * This converts an array/set into an array/list of sorted arrays with values 
 * keys. Yuck. HOF would be nice here.
 **/
function set_to_values_list($set) {
    $values = array();

    $keys = array_keys($set);
    sort($keys);

    foreach ($keys as $k) {
        array_push($values, array('value' => $k));
    }

    return $values;
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

// Set the initial set/array for each field. Populate it from the database.
$query = mysql_query($sql, $connection);
while ($row = mysql_fetch_assoc($query)) {
    add_row_values($fields, $row);
}

// Now convert the sets into sorted lists.
foreach ($fields as $f => $info) {
    $info['values'] = set_to_values_list($info['values']);
    $fields[$f] = $info;
}

// Now add in the free-form text fields.
$fields['hometown'] = array('name' => 'homet', 'label' => 'Hometown');

echo $mustache->render($templates['search'], $fields,
    array(
        'header'      => $templates['header'],
        'footer'      => $templates['footer'],
        'queryselect' => $templates['queryselect'],
        'querytext'   => $templates['querytext']
    )
);

