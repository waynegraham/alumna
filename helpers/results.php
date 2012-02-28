<?php

require_once dirname(__FILE__) . '/base.php';

/**
 * This is a helper class that assembles the page listing all the results for a search.
 **/
class ResultsHelper extends BaseHelper
{
    /**
     * This is the number of rows per page.
     *
     * @var int
     **/
    var $rows_page = 20;

    /**
     * This is a mapping between GET parameters and database columns.
     *
     * @var array
     **/
    var $fields = array(
        'prog1'              => 'school',
        'prog2'              => 'school2',
        'prog3'              => 'school3',
        'homet'              => 'hometown',
        'homes'              => 'homestate',
        'htclass'            => 'htclassification',
        'startyear'          => 'entereduva',
        'endyear'            => 'leftuva',
        'UVAspouse'          => 'spouseAtUVA',
        'hsname'             => 'highschool',
        'coedhs'             => 'coedhighschool',
        'directfromhs'       => 'directlyFromHS'
    );

    /**
     * These are labels for the database columns.
     *
     * @var array
     **/
    var $display_fields = array(
        'school'             => 'First UVa program',
        'school2'            => 'Second UVa program',
        'school3'            => 'Thrid UVa program',
        'hometown'           => 'Home town',
        'homestate'          => 'Home state',
        'entereduva'         => 'Year entered UVa',
        'leftuva'            => 'Year left UVa',
        'htclassification'   => 'Home town classification',
        'highschool'         => 'High school type',
        'coedhighschool'     => 'Coed High school?',
        'directlyFromHS'     => 'Directly from high school',
        'spouseAtUVA'        => 'Spouse at UVa'
    );

    /**
     * These are the database columns in a shape that the template can use.
     *
     * @var array
     **/
    var $display_labels = array(
        array('label' => 'Accession Number'),
        array('label' => 'First UVa program'),
        array('label' => 'Second UVa program'),
        array('label' => 'Thrid UVa program'),
        array('label' => 'Home town'),
        array('label' => 'Home state'),
        array('label' => 'Year entered UVa'),
        array('label' => 'Year left UVa'),
        array('label' => 'Home town classification'),
        array('label' => 'High school type'),
        array('label' => 'Coed High school?'),
        array('label' => 'Directly from high school'),
        array('label' => 'Spouse at UVa')
    );

    /**
     * This is the database column names and their values in the query.
     *
     * @var array
     **/
    var $params;

    /**
     * This is a mapping of the current GET query.
     *
     * @var array
     **/
    var $current_query;

    /**
     * This is information in the currey GET query in a format that the 
     * template can use.
     *
     * @var array
     **/
    var $current_form;

    /**
     * The current GET parameters as a flat associative array.
     *
     * @var array
     **/
    var $current_params;

    /**
     * The query string.
     *
     * @var string
     **/
    var $query;

    /**
     * The query string for the COUNT query.
     *
     * @var string
     **/
    var $countq;

    /**
     * The template parameters.
     *
     * @var array
     **/
    var $template_params;

    function __construct($controller)
    {
        parent::__construct($controller);
        $this->params          = array();
        $this->current_query   = array();
        $this->current_form    = array();
        $this->current_params  = array();
        $this->query           = '';
        $this->template_params = array();
    }

    /**
     * This overrides render to call over to DisplayHelper if rec_num is set.
     *
     * @return string
     * @author Eric Rochester <err8n@virginia.edu>
     **/
    public function render()
    {
        $output = '';

        if ($this->_get('rec_num')) {
            require_once dirname(__FILE__) . '/record.php';
            $helper = new RecordHelper($this->controller);
            $output = $helper->render();
        } else {
            $output = parent::render();
        }

        return $output;
    }

    /**
     * This directly calls the parent render, without checking that rec_num 
     * is set appropriately.
     *
     * @return string
     * @author Eric Rochester <err8n@virginia.edu>
     **/
    protected function _render()
    {
        return parent::render();
    }

    /**
     * This returns the name of the base template for this page.
     *
     * @return string
     * @author Eric Rochester <err8n@virginia.edu>
     **/
    protected function _getTemplateName()
    {
        return 'results';
    }

    /**
     * This returns the data needed to populate the template.
     *
     * @return array
     * @author Eric Rochester <err8n@virginia.edu>
     **/
    protected function _getTemplateData()
    {
        // Get and prep the GET variables.
        $vars = $this->_getArray(array(
            'rec_num', 'rec_num2', 'remod', 'submitsearch', 'next_page',
            'prev_page', 'num_page'
        ));
        $this->_normalizeRecordNos($vars);

        $this->_addCurrentForm('submitsearch', $vars['submitsearch']);
        $this->_addCurrentForm('num_page',     $vars['num_page']);
        $this->_addCurrentForm('rec_num',      $vars['rec_num']);

        $this->_getFieldValues();

        $this->_buildQuery();
        $this->_setPagination($vars);

        $count   = $this->_getCount();
        $results = $this->_getSearchResults(($vars['num_page'] - 1) * $this->rows_page);

        $prev = ($vars['num_page'] > 1 && $count > $this->rows_page);
        $next = (
            $vars['num_page'] < ceil($count / $this->rows_page) &&
            $count > $this->rows_page
        );
        $data = array(
            'count'        => $count,
            'results'      => $results,
            'labels'       => $this->display_labels,
            'current'      => $this->current_form,
            'query_params' => http_build_query($this->current_params),
            'page_size'    => $this->rows_page,
            'prev'         => $prev,
            'next'         => $next,
            'paging'       => ($prev || $next)
        );

        return $data;
    }

    /**
     * This returns the list of partials that this template requires.
     *
     * @return array
     * @author Eric Rochester <err8n@virginia.edu>
     **/
    protected function _getPartialNames()
    {
        return array(
            'header',
            'footer',
            'hidden_form',
            'searchresult'
        );
    }

    /**
     * This re-sets the values of rec_num and rec_num2 if remod is set.
     *
     * @return void
     * @author Eric Rochester <err8n@virginia.edu>
     **/
    protected function _normalizeRecordNos(&$vars)
    {
        if ($vars['remod']) {
            $vars['rec_num'] = 0;
            $vars['rec_num2'] = 0;
        }
    }

    /**
     * This adds a field name and value to the current form information.
     *
     * @param $field string The field key.
     * @param $value string The field value.
     *
     * @return void
     * @author Eric Rochester <err8n@virginia.edu>
     **/
    protected function _addCurrentForm($field, $value)
    {
        array_push($this->current_form, array(
            'field' => $field,
            'value' => $value
        ));
        $this->current_params[$field] = $value;
    }

    /**
     * This removes a field from the current_form.
     *
     * @param $field string The field to remove.
     *
     * @return void
     * @author Eric Rochester <err8n@virginia.edu>
     **/
    protected function _delCurrentForm($field)
    {
        $i = 0;
        foreach ($this->current_form as $info) {
            if ($info['field'] == $field) {
                break;
            }
            $i++;
        }

        unset($this->current_form[$i]);
        $this->current_form = array_values($this->current_form);
    }

    /**
     * This gets values from the GET request and populates the params, 
     * current_query, and current_form values.
     *
     * This doesn't use _get, because it skips missing or empty values 
     * entirely.
     *
     * @return void
     * @author Eric Rochester <err8n@virginia.edu>
     **/
    protected function _getFieldValues()
    {
        foreach ($this->fields as $param => $column_name) {
            if (! isset($_GET[$param])) {
                continue;
            }
            $value = $_GET[$param];
            if ($value) {
                $this->params[$column_name]  = $value;
                $this->current_query[$param] = $value;
                $this->_addCurrentForm($param, $value);
            }
        }

        // Also look for the keyword.
        if (isset($_GET['keyword']) && $_GET['keyword']) {
            $keyword = $_GET['keyword'];
            $this->current_query['keyword'] = $keyword;
            $this->_addCurrentForm('keyword', $keyword);
        }
    }

    /**
     * This constructs the SQL query.
     *
     * This sets the $countq and $query parameters.
     *
     * @return void
     * @author Eric Rochester <err8n@virginia.edu>
     **/
    protected function _buildQuery()
    {
        $db = $this->_db();
        $query = 'SELECT i.accessionNumber FROM iph i ';
        $i = 0;

        // HACK ALERT: querying prog1=* is a flag that returns everything in 
        // the database.
        if ($this->current_query['prog1'] != '*') {
            $query .= ' WHERE ';

            foreach ($this->params as $column => $value) {
                $op = $i > 0 ? 'AND' : '';
                $value = $db->escape($value);
                $query .= " $op $column='$value'";
                $i++;
            }

            $query = $this->_buildKeywordQuery($query, $i);
        }

        $this->countq = "SELECT COUNT(*) FROM iph WHERE accessionNumber IN ($query);";
        $this->query  = "SELECT * FROM iph WHERE accessionNumber IN ($query) ORDER BY accessionNumber";
    }

    /**
     * This takes a general query and pimps it out with keyword search.
     *
     * @params $query string The initial query to decorate.
     *
     * @return string
     * @author Eric Rochester <err8n@virginia.edu>
     **/
    protected function _buildKeywordQuery($query, $i)
    {
        if (!isset($this->current_query['keyword'])) {
            return $query;
        }

        $op      = $i > 0 ? 'AND' : '';
        $keyword = $this->current_query['keyword'];
        $value   = $this->_db()->escape($keyword);

        //$query = "$query $op ";
        $query = ''; // reset query paramenter
        //$query .= <<<EOQ
                //MATCH (
                     //school, school2,
//hometown, htclassification, highschool,
//MothersOccupation1, MothersOccupation2, 
//FathersOccupation1, FathersOccupation2,  
//Comments14, 
//Position1, Position2, CommentsonAbove39, 
//Volunteer1, Volunteer2,
//CommentsonAbove40
                //) AGAINST (
//EOQ;
        //$query .= "'$value' IN BOOLEAN MODE) UNION SELECT DISTINCT accessionNumber FROM openresponses WHERE MATCH (response) AGAINST ('$value' IN BOOLEAN MODE)";
        $query .= <<<EOQ
  SELECT DISTINCT o.accessionNumber 
  FROM `openresponses` o
  WHERE MATCH (response)

EOQ;

        $query .= " AGAINST ('$value' IN BOOLEAN MODE)";

        //print_r($query);


        return $query;
    }

        /**
     * This sets the pagination.
     *
     * @param $vars array The GET parameters.
     *
     * @return void
     * @author Eric Rochester <err8n@virginia.edu>
     **/
    protected function _setPagination(&$vars)
    {
        if (!$vars['num_page']) {
            $vars['num_page'] = 1;
        }
        if ($vars['next_page']) {
            $vars['num_page'] = $vars['num_page'] + 1;
        }
        if ($vars['prev_page']) {
            $vars['num_page'] = $vars['num_page'] - 1;
        }
        $this->_addCurrentForm('num_page', $vars['num_page']);

        $offset = ($vars['num_page'] - 1) * $this->rows_page;
        $limit = $this->rows_page;

        $this->query .= " LIMIT $limit OFFSET $offset;";
    }

    /**
     * This performs the COUNT query and returns the result.
     *
     * @return int
     * @author Eric Rochester <err8n@virginia.edu>
     **/
    protected function _getCount()
    {
        $result = $this->_db()->query($this->countq);
        $row = mysql_fetch_row($result);
        return $row[0];
    }

    /**
     * This returns the search results in a form that the template will like.
     *
     * @params $offset int This is the page offset. It defaults to zero.
     *
     * @return array
     * @author Eric Rochester <err8n@virginia.edu>
     **/
    protected function _getSearchResults($offset=0)
    {
        $results = array();

        $db_result = $this->_db()->query($this->query);
        if ($db_result) {
            $current_query_str = http_build_query($this->current_query, '', '&amp;');
            $n = 1;
            while ($row = mysql_fetch_assoc($db_result)) {
                $values = array();
                foreach ($this->display_fields as $column => $label) {
                    $value = (isset($row[$column])) ? $row[$column] : '';
                    array_push($values, array(
                        'value' => $value
                    ));
                }

                $meta = array(
                    'n'      => $n + $offset,
                    'id'     => $row['accessionNumber'],
                    'query'  => $current_query_str,
                    'values' => $values
                );

                array_push($results, $meta);
                $n++;
            }
        }

        return $results;
    }

}

