<?php

/**
 * This is a helper for the search page. This is delegated to by the 
 * controller.
 **/
class SearchHelper {

    /**
     * This is the primary controller, giving this access to the DB manager and templating.
     *
     * @var AlumnaControler
     **/
    var $controller;

    var $_rec_num;
    var $_rec_num2;
    var $_remod;
    var $_submitsearch;
    var $_next_page;
    var $_prev_page;
    var $_num_page;
    
    function __construct($controller)
    {
        $this->controller = $controller;
    }

    /**
     * This returns the DB.
     *
     * @return DatabaseManager
     * @author Eric Rochester <err8n@virginia.edu>
     **/
    protected function _db()
    {
        return $this->controller->db();
    }

    /**
     * This returns a GET parameter, or the default.
     *
     * @param $var     string The GET parameter to return.
     * @param $default string The default value to return. This defaults to the 
     * empty string.
     *
     * @return string
     * @author Eric Rochester <err8n@virginia.edu>
     **/
    protected function _get($var, $default='')
    {
        return isset($_GET[$var]) ? $_GET[$var] : $default;
    }

    /**
     * This returns the series of GET parameters as an associative array.
     *
     * @param $vars array This is a list of the GET parameters to return.
     *
     * @return array
     * @author Eric Rochester <err8n@virginia.edu>
     **/
    protected function _getArray($vars)
    {
        $params = array();
        foreach ($vars as $var) {
            $params[$var] = $this->_get($var);
        }
        return $params;
    }

    /**
     * This returns an array with the initial data for a field, including its 
     * name, its label, and an empty array for keeping a set of values.
     *
     * @param $name  string The name of the field.
     * @param $label string The label for the field.
     *
     * @return array
     * @author Eric Rochester <err8n@virginia.edu>
     **/
    protected function _finfo($name, $label)
    {
        return array(
            'name'   => $name,
            'label'  => $label,
            'values' => array()
        );
    }

    /**
     * This adds the row of data values into the $fields array. For each value, 
     * it's added to the values set for that field.
     *
     * @param $fields array The field info for the field (the output of 
     * _finfo).
     * @param $row array The data row as an associative array.
     *
     * @return void
     * @author Eric Rochester <err8n@virginia.edu>
     **/
    protected function _add_row_values(&$fields, $row)
    {
        $row_keys = array_keys($row);
        foreach ($fields as $f => $info) {
            if (!isset($row[$f])) {
                continue;
            }
            $value = $row[$f];
            if ($value != null) {
                $info['values'][$value] = 1;
            }
            $fields[$f] = $info;
        }
    }

    /**
     * This takes a set (an associative array with the keys as the set members) 
     * and returns an array list of sorted values.
     *
     * @param $set array The associate array/set to extract the members from.
     *
     * @return array
     * @author Eric Rochester <err8n@virginia.edu>
     **/
    protected function _set_to_values_list($set)
    {
        $values = array();

        $keys = array_keys($set);
        sort($keys);

        foreach ($keys as $k) {
            array_push($values, array('value' => $k));
        }

        return $values;
    }

    /**
     * This generates the field data for the template.
     *
     * This includes the fields, labels, and their values in the database.
     *
     * @return array
     * @author Eric Rochester <err8n@virginia.edu>
     **/
    protected function _getFieldInfo()
    {
        $db = $this->_db();
        $sql = 'SELECT * FROM iph';
        $fields = array(
            'school'           => $this->_finfo('prog1', 'First UVa school/program'),
            'school2'          => $this->_finfo('prog2', 'Second UVa school/program'),
            'homestate'        => $this->_finfo('homet', 'Hometown'),
            'htclassification' => $this->_finfo('htclass', 'Hometown Classification'),
            'entereduva'       => $this->_finfo('startyear', 'Year started at UVa'),
            'leftuva'          => $this->_finfo('endyear', 'Year left UVa'),
            'spouseAtUVA'      => $this->_finfo('UVAspouse', 'Spouse at UVa'),
            'highschool'       => $this->_finfo('hsname', 'High school type'),
            'coedhighschool'   => $this->_finfo('coedhs', 'Coed high school'),
            'directlyFromHS'   => $this->_finfo('directfromhs', 'Directly from high school')
        );

        $query = $db->query($sql);
        while ($row = mysql_fetch_assoc($query)) {
            $this->_add_row_values($fields, $row);
        }

        foreach ($fields as $f => $info) {
            $info['values'] = $this->_set_to_values_list($info['values']);
            $fields[$f] = $info;
        }

        // Now free-form text fields.
        $fields['hometown'] = array('name' => 'homet', 'label' => 'Hometown');

        return $fields;
    }

    /**
     * This returns the array for the template data.
     *
     * @return array
     * @author Eric Rochester <err8n@virginia.edu>
     **/
    protected function _makeTemplateData()
    {
        $fields = $this->_getFieldInfo();
        return $fields;
    }

    /**
     * This renders the page and returns it as a string.
     *
     * @return string
     * @author Eric Rochester <err8n@virginia.edu>
     **/
    public function render()
    {
        $data     = $this->_makeTemplateData();
        $partials = array(
            'header',
            'footer',
            'queryselect',
            'querytext'
        );

        return $this->controller->render('search', $data, $partials);
    }
}

