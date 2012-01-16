<?php

require_once './alumna.php';

$db = new DatabaseManager($DB_CONFIG_FILE);

$connection=mysql_connect("localhost", "alumnaAdmin", "alumna%adm%orion");
mysql_select_db("alumna", $connection);

$rec_num      = isset($_GET['rec_num'])      ? $_GET['rec_num']      : '';
$rec_num2     = isset($_GET['rec_num2'])     ? $_GET['rec_num2']     : '';
$remod        = isset($_GET['remod'])        ? $_GET['remod']        : '';
$submitsearch = isset($_GET['submitsearch']) ? $_GET['submitsearch'] : '';
$next_page    = isset($_GET['next_page'])    ? $_GET['next_page']    : '';
$prev_page    = isset($_GET['prev_page'])    ? $_GET['prev_page']    : '';
$num_page     = isset($_GET['num_page'])     ? $_GET['num_page']     : '';

$rows_page    = 20;
$partials = array(
    'header'      => $templates['header'],
    'footer'      => $templates['footer'],
    'hidden_form' => $templates['hidden_form']
);

/**
 * This takes a record, a field name and label and returns an assoc array with 
 * the label, how many rows the output widget should have, and the value.
 **/
function make_record_output_info($record, $field, $label,
    $alpha=false, $omega=false, $cols=20, $colspan=0
) {
    $value = (isset($record[$field])) ? $record[$field] : '';

    if (strlen($value) > $cols) {
        $rows = floor(strlen($value) / $cols);
    } else {
        $rows = 0;
    }

    $size = 24;
    if ($colspan) {
        $size *= $colspan;
    }

    return array(
        'field'    => $field,
        'label'    => $label,
        'rows'     => $rows,
        'value'    => $value,
        'alpha'    => $alpha,
        'omega'    => $omega,
        'cols'     => $cols,
        'colspan'  => $colspan,
        'textsize' => $size
    );
}

/**
 * This handles the responses.
 **/
function make_resp_output_info($responses, $field, $q, $label, $alpha=false,
    $omega=false, $cols=20, $colspan=0
) {
    $value = '';
    foreach ($responses as $resp) {
        if ($resp['questionnumber'] == $q) {
            $value = $resp['response'];
            break;
        }
    }
    $field = "response$q";
    $record = array($field => $value);
    return make_record_output_info(
        $record, $field, $label, $alpha, $omega, $cols, $colspan
    );
}

if ($remod) {
    $rec_num  = 0;
    $rec_num2 = 0;
}


// This creates the parameter arrays based on the GET request.
$fields = array(
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
$params          = array();
$current_query   = array();
$current_form    = array(
    array('field' => 'submitsearch', 'value' => $submitsearch),
    array('field' => 'num_page',     'value' => $num_page),
    array('field' => 'rec_num',      'value' => $rec_num)
);

foreach ($fields as $param => $column_name) {
    if (! isset($_GET[$param])) {
        continue;
    }
    $value = $_GET[$param];
    if ($value) {
        $params[$column_name]  = $value;
        $current_query[$param] = $value;
        array_push($current_form, array(
            'field' => $param,
            'value' => $value
        ));
    }
}
if (isset($_GET['keyword']) && $_GET['keyword']) {
    $keyword                  = $_GET['keyword'];
    $current_query['keyword'] = $keyword;
    array_push($current_form, array(
        'field' => 'keyword',
        'value' => $keyword
    ));
}


// This is for displaying the list. This should be factored into some kind of 
// browse or list controller, really.
if (!$rec_num) {

    // They've submitted a search query.
    $display_fields = array(
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

    $display_labels = array(
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

    $query           = '';
    $template_params = array();

    // Build the query.
    $i = 0;
    $query .= "SELECT accessionNumber FROM iph WHERE ";
    foreach ($params as $column => $value) {
        $op = $i > 0 ? 'AND' : '';
        $query .= " $op $column='$value'";
        $i++;
    }

    // Handle keywords by wrapping the query so far and adding the full 
    // text search to it, as well as the full text search on the responses.
    if (isset($current_query['keyword'])) {
        $op      = $i > 0 ? 'AND' : '';
        $keyword = $current_query['keyword'];
        $value   = mysql_real_escape_string($keyword);

        $query = "($query $op ";
        $query .= <<<EOQ
                MATCH (
                    lastName, firstName, address, city, state, 
                    zip, phoneNumber, email, canwecontact, contact2, school, 
                    school2, school3, hometown, homestate, htclassification, 
                    highschool, coedhighschool, directlyFromHS, ifNotComment, 
                    activities, married, spouseAtUVA, mothersDegree1, 
                    mothersSchool1, mothersSchool2, mothersDegree2, 
                    mothersSchool3, mothersDegree3, fathersDegree1, 
                    fathersschool1, fathersschool2, fathersdegree2, 
                    fathersschool3, fathersdegree3, comments10, 
                    sisterschooling1, sisterschooling2, sisterschooling3, 
                    brotherschooling1, brotherschooling2, brotherschooling3, 
                    comments11, familyatuva, mothersoccupation1, 
                    mothersoccupation2, mothersoccupation3, fathersoccupation1, 
                    fathersoccupation2, fathersoccupation3, comments13, 
                    othercollege1, otherconcentration1, otherdegree1, 
                    otherdate1, othercollege2, otherconcentration2, 
                    otherdegree2, otherdate2, othercollege3, 
                    otherconcentration3, otherdegree3, otherdate3, 
                    othercollege4, otherconcentration4, otherdegree4, 
                    otherdate4, othercollege5, otherconcentration5, 
                    otherdegree5, otherdate5, comments14, whychooseuva, 
                    howfinanceuva, whychooseFOC, classroomexp, 
                    interactionprofs, greatestimpact, mostremember, 
                    weekendacts, Xtracurrorgs, issues, interestsexpressed, 
                    housing, likedislikehousing, commenton27, meals, 
                    interactinCville, interactdetail, Cvillememory, 
                    partofUVAcomm, partofcomm, specincidents, vividmems, 
                    marstatusinfluence, culturalbackgroundinf, 
                    educationprepared, preparedpostUVA, commentonprepared, 
                    mostsigeventsince, impactonprofessional, otherfactors, 
                    position1, dates1, position2, dates2, position3, dates3, 
                    commentson39, volunteer1, volunteerdates1, volunteer2, 
                    volunteerdates2, volunteer3, volunteerdates3, commentson40, 
                    careerwoman, commentson41, workbarriers, commentson42, 
                    currmarry, agerange, employstatus, commenton46, addcomments
                ) AGAINST (
EOQ;
        $query .= "'$value' IN BOOLEAN MODE)) UNION (SELECT DISTINCT accessionNumber FROM openresponses WHERE MATCH (response) AGAINST ('$value' IN BOOLEAN MODE)) ORDER BY accessionNumber";
    }

    // All that was just to get the accessionNumbers returned. Now let's 
    // use that to get all the fields and the count. ($countq has to be 
    // done first, because we're getting ready to clobber $query.)
    $countq = "SELECT COUNT(*) FROM iph WHERE accessionNumber IN ($query);";
    $query  = "SELECT * FROM iph WHERE accessionNumber IN ($query) ORDER BY accessionNumber";

    // Now we need to add pagination....
    if (!$num_page) {
        $num_page = 1;
    }
    if ($next_page) {
        $num_page++;
    }
    if ($prev_page) {
        $num_page--;
    }
    array_push($current_form, array(
        'field' => 'num_page',
        'value' => $num_page
    ));

    $offset = ($num_page - 1) * $rows_page;
    $limit  = $rows_page;
    $query .= " LIMIT $limit OFFSET $offset;";

    // Get the count of the full, non-paginated results.
    error_log("COUNT QUERY: $countq");
    $result = mysql_query($countq, $connection);
    $row = mysql_fetch_row($result);
    $count = $row[0];
    error_log("count = $count");

    // Get the page of results. Build the data structures for the template.
    $results = array();
    error_log("SEARCH QUERY: $query");
    $result = mysql_query($query, $connection);
    error_log("RESULTS: " . print_r($result, true));
    if ($result) {
        $current_query_str = http_build_query($current_query, '', '&amp;');
        $n = 1;
        while ($row = mysql_fetch_assoc($result)) {
            $values = array();
            foreach ($display_fields as $column => $label) {
                array_push($values, array(
                    'value'  => $row[$column]
                ));
            }

            $meta = array(
                'n'      => $n,
                'id'     => $row['accessionNumber'],
                'query'  => $current_query_str,
                'values' => $values
            );

            array_push($results, $meta);
            $n++;
        }
    }

    $template_params['count']     = $count;
    $template_params['results']   = $results;
    $template_params['labels']    = $display_labels;
    $template_params['current']   = $current_form;
    $template_params['page_size'] = $rows_page;
    $template_params['prev']      = (
        $num_page > 1 && $count > $rows_page
    );
    $template_params['next']      = (
        $num_page < ($count / $rows_page) - 1 && $count > $rows_page
    );
    $template_params['paging']    = (
        $template_params['prev'] || $template_params['next']
    );

    $template_name            = 'browse';
    $partials['searchresult'] = $templates['searchresult'];

} else {
    // This is the case if they want to look at a single record.
    $query = sprintf(
        'SELECT * FROM iph WHERE accessionNumber=%s;',
        mysql_real_escape_string($rec_num)
    );
    error_log("ROW QUERY: $query");
    $result = mysql_query($query, $connection);
    $record = mysql_fetch_assoc($result);

    $query = sprintf(
        'SELECT * FROM openresponses WHERE accessionNumber=%s;',
        mysql_real_escape_string($rec_num)
    );
    error_log("RESPONSE QUERY: $query");
    $result = mysql_query($query, $connection);
    $responses = array();
    while ($resp = mysql_fetch_assoc($result)) {
        array_push($responses, $resp);
    }

    $display_fields = array(
        make_record_output_info($record, 'school', "First UVA program", true),
        make_record_output_info($record, 'school2', "Second UVA program", false, true),
        # make_record_output_info($record, 'school3', "Third UVA program"),
        make_resp_output_info($responses, 'c4', 1, "Comments on UVA programs", true, true, 62, 3),
        make_record_output_info($record, 'hometown', "Home town", true),
        make_record_output_info($record, 'homestate', "Home state", false, true),
        make_resp_output_info($responses, 'c7', 2, "Comments on Home town and state", true, true, 62, 3),
        make_record_output_info($record, 'htclassification', "Home town classification", true, true),
        make_resp_output_info($responses, 'c9', 3, "Comments on Home town classification", true, true, 62, 3),
        make_record_output_info($record, 'highschool', "High school classification", true, true),
        make_resp_output_info($responses, 'c11', 4, "Comments on High school classification", true, true, 62, 3),
        make_record_output_info($record, 'coedhighschool', "Was your high school co-ed?", true, true),
        make_resp_output_info($responses, 'c13', 5, "Comments on Co-ed high school", true, true, 62, 3),
        make_record_output_info($record, 'directlyFromHS', "Did you come directly from high school to UVA?", true, true),
        make_resp_output_info($responses, 'c15', 6, "Comments if not directly from high school", true, true, 62, 3),
        make_record_output_info($record, 'activities', "Important activities before coming to UVA", true, true),
        make_resp_output_info($responses, 'c17', 7, "Comments on important activities", true, true, 62, 3),
        make_record_output_info($record, 'married', "If married, was it before, during, or after UVA?", true, true),
        make_resp_output_info($responses, 'c19', 8, "Comments on time of marriage", true, true, 62, 3),
        make_record_output_info($record, 'spouseAtUVA', "Was your spouse associated with UVA?", true, true),
        make_resp_output_info($responses, 'c21', 9, "Comments on spouses association with UVA", true, true, 62, 3),
        make_record_output_info($record, 'mothersDegree1', "Mother's first degree", true),
        make_record_output_info($record, 'mothersSchool1', "Mother's first college", false, true),
        make_record_output_info($record, 'mothersSchool2', "Mother's second college", true),
        make_record_output_info($record, 'mothersDegree2', "Mother's second degree", false, true),
        make_record_output_info($record, 'mothersSchool3', "Mother's third college", true),
        make_record_output_info($record, 'mothersDegree3', "Mother's third degree", false, true),
        make_record_output_info($record, 'fathersDegree1', "Father's first degree", true),
        make_record_output_info($record, 'fathersschool1', "Father's first college", false, true),
        make_record_output_info($record, 'fathersschool2', "Father's second college", true),
        make_record_output_info($record, 'fathersdegree2', "Father's second degree", false, true),
        make_record_output_info($record, 'fathersschool3', "Father's third school", true),
        make_record_output_info($record, 'fathersdegree3', "Father's third degree", false, true),
        make_resp_output_info($responses, 'c34', 10, "Comments on parents' schooling", true, true, 62, 3),
        make_record_output_info($record, 'sisterschooling1', "First sister's level of schooling", true),
        make_record_output_info($record, 'sisterschooling2', "Second sister's level of schooling", false, true),
        make_record_output_info($record, 'sisterschooling3', "Third sister's level of schooling", true),
        make_record_output_info($record, 'brotherschooling1', "First brother's level of schooling", false, true),
        make_record_output_info($record, 'brotherschooling2', "Second brother's level of schooling", true),
        make_record_output_info($record, 'brotherschooling3', "Third brother's level of schooling", false, true),
        make_resp_output_info($responses, 'c40', 11, "Comments on siblings' schooling", true, true, 62, 3),
        make_record_output_info($record, 'familyatuva', "Family members at UVA", true, true),
        make_resp_output_info($responses, 'c42', 12, "Comments on family members at UVA", true, true, 62, 3),
        make_record_output_info($record, 'mothersoccupation1', "Mother's first occupation", true),
        make_record_output_info($record, 'mothersoccupation2', "Mother's second occupation", false, true),
        make_record_output_info($record, 'mothersoccupation3', "Mother's third occupation", true),
        make_record_output_info($record, 'fathersoccupation1', "Father's first occupation", false, true),
        make_record_output_info($record, 'fathersoccupation2', "Father's second occupation", true),
        make_record_output_info($record, 'fathersoccupation3', "Father's third occupation", false, true),
        make_resp_output_info($responses, 'c48', 13, "Comments on parents' occupations", true, true, 62, 3),
        make_record_output_info($record, 'entereduva', "Date entered UVA", true),
        make_record_output_info($record, 'leftuva', "Date left UVA", false, true),
        make_record_output_info($record, 'othercollege1', "First other college", true),
        make_record_output_info($record, 'otherconcentration1', "First other concentration", false, true),
        make_record_output_info($record, 'otherdegree1', "First other degree", true),
        make_record_output_info($record, 'otherdate1', "First other dates of attendance", false, true),
        make_record_output_info($record, 'othercollege2', "Second other college", true),
        make_record_output_info($record, 'otherconcentration2', "Second other concentration", false, true),
        make_record_output_info($record, 'otherdegree2', "Second other degree", true),
        make_record_output_info($record, 'otherdate2', "Second other dates of attendance", false, true),
        make_record_output_info($record, 'othercollege3', "Third other college", true),
        make_record_output_info($record, 'otherconcentration3', "Third other concentration", false, true),
        make_record_output_info($record, 'otherdegree3', "Third other degree", true),
        make_record_output_info($record, 'otherdate3', "Third other dates of attendance", false, true),
        make_record_output_info($record, 'othercollege4', "Fourth other college", true),
        make_record_output_info($record, 'otherconcentration4', "Fourth other concentration", false, true),
        make_record_output_info($record, 'otherdegree4', "Fourth other degree", true),
        make_record_output_info($record, 'otherdate4', "Fourth other dates of attendance", false, true),
        make_record_output_info($record, 'othercollege5', "Fifth other college", true),
        make_record_output_info($record, 'otherconcentration5', "Fifth other concentration", false, true),
        make_record_output_info($record, 'otherdegree5', "Fifth other degree", true),
        make_record_output_info($record, 'otherdate5', "Fifth other dates of attendance", false, true),
        make_resp_output_info($responses, 'c71', 14, "Comments on college experiences", true, true, 62, 3),
        make_record_output_info($record, 'whychooseuva', "Why did you choose UVA?", true, true),
        make_resp_output_info($responses, 'c73', 15, "Comments on choice to attend UVA", true, true, 62, 3),
        make_record_output_info($record, 'howfinanceuva', "How did you finance UVA?", true, true),
        make_resp_output_info($responses, 'c75', 16, "Comments on financing", true, true, 62, 3),
        make_record_output_info($record, 'whychooseFOC', "Why did you choose your UVA concentration(s)?", true, true),
        make_resp_output_info($responses, 'c77', 17, "Comments on choice of concentration", true, true, 62, 3),
        make_record_output_info($record, 'classroomexp', "Description of UVA classroom experiences", true, true),
        make_resp_output_info($responses, 'c79', 18, "Comments on classroom experiences", true, true, 62, 3),
        make_record_output_info($record, 'interactionprofs', "Description of UVA professor interactions", true, true),
        make_resp_output_info($responses, 'c81', 19, "Comments on professor interactions", true, true, 62, 3),
        make_record_output_info($record, 'greatestimpact', "Who impacted your life at UVA?", true, true),
        make_resp_output_info($responses, 'c83', 20, "Comments on life impact", true, true, 62, 3),
        make_record_output_info($record, 'mostremember', "Most memorable UVA educational experience", true, true),
        make_resp_output_info($responses, 'c85', 21, "Comments on memorable educational experience", true, true, 62, 3),
        make_record_output_info($record, 'weekendacts', "UVA weekend activities", true, true),
        make_resp_output_info($responses, 'c87', 22, "Comments on weekend activities", true, true, 62, 3),
        make_record_output_info($record, 'Xtracurrorgs', "UVA extra-curricular organizations", true, true),
        make_resp_output_info($responses, 'c89', 23, "Comments on extra-curricular organizations", true, true, 62, 3),
        make_record_output_info($record, 'issues', "Important local, state, national, and international issues while at UVA", true, true),
        make_resp_output_info($responses, 'c91', 24, "Comments on important issues", true, true, 62, 3),
        make_record_output_info($record, 'interestsexpressed', "How interest in issues was expressed", true, true),
        make_resp_output_info($responses, 'c93', 25, "Comments on how interest was expressed", true, true, 62, 3),
        make_record_output_info($record, 'housing', "UVA housing ", true, true),
        make_resp_output_info($responses, 'c95', 26, "Comments on housing", true, true, 62, 3),
        make_record_output_info($record, 'likedislikehousing', "Likes and dislikes about housing", true, true),
        make_resp_output_info($responses, 'c97', 27, "Comments on likes and dislikes", true, true, 62, 3),
        make_record_output_info($record, 'meals', "Where were meals eaten", true, true),
        make_resp_output_info($responses, 'c99', 28, "Comments on meal location", true, true, 62, 3),
        make_record_output_info($record, 'interactinCville', "Did you interact outside of UVA?", true, true),
        make_resp_output_info($responses, 'c101', 29, "Interaction detail", true, true, 62, 3),
        make_record_output_info($record, 'Cvillememory', "Memories of Charlottesville", true, true),
        make_resp_output_info($responses, 'c103', 30, "Comments on Charlottesville memories", true, true, 62, 3),
        make_record_output_info($record, 'partofUVAcomm', "Did you feel part of UVA community?", true),
        make_record_output_info($record, 'partofcomm', "Did you feel part of community?", false, true),
        make_resp_output_info($responses, 'c106', 31, "Incidents that caused these community feelings", true, true, 62, 3),
        make_record_output_info($record, 'vividmems', "Vivid UVA memories", true, true),
        make_resp_output_info($responses, 'c108', 32, "Comments on vivid memories", true, true, 62, 3),
        make_record_output_info($record, 'marstatusinfluence', "Did marital status affect UVA experiences", true, true),
        make_resp_output_info($responses, 'c110', 33, "Comments on influence of marital status", true, true, 62, 3),
        make_record_output_info($record, 'culturalbackgroundinf', "Did cultural background affect UVA experiences", true, true),
        make_resp_output_info($responses, 'c112', 34, "Comments on influence of cultural background", true, true, 62, 3),
        make_record_output_info($record, 'preparedpostUVA', "Did UVA prepare you for post-UVA life?", true, true),
        make_resp_output_info($responses, 'c114', 35, "Comments on preparedness", true, true, 62, 3),
        make_record_output_info($record, 'mostsigeventsince', "Most significant post-UVA events", true, true),
        make_resp_output_info($responses, 'c116', 36, "Comments on most significant event", true, true, 62, 3),
        make_record_output_info($record, 'impactonprofessional', "Personal factors that affected professional life", true, true),
        make_resp_output_info($responses, 'c118', 37, "Comments on personal factors", true, true, 62, 3),
        make_record_output_info($record, 'otherfactors', "Other factors affecting life after the university", true, true),
        make_resp_output_info($responses, 'c120', 38, "Comments on other factors", true, true, 62, 3),
        make_record_output_info($record, 'position1', "First post-UVA job", true),
        make_record_output_info($record, 'dates1', "First job dates", false, true),
        make_record_output_info($record, 'position2', "Second post-UVA job", true),
        make_record_output_info($record, 'dates2', "Second job dates", false, true),
        make_record_output_info($record, 'position3', "Third post-UVA job", true),
        make_record_output_info($record, 'dates3', "Third job dates", false, true),
        make_resp_output_info($responses, 'c127', 39, "Comments on post-UVA jobs", true, true, 62, 3),
        make_record_output_info($record, 'volunteer1', "First post-UVA volunteer position", true),
        make_record_output_info($record, 'volunteerdates1', "First volunteer dates", false, true),
        make_record_output_info($record, 'volunteer2', "Second post-UVA volunteer position", true),
        make_record_output_info($record, 'volunteerdates2', "Second volunteer dates", false, true),
        make_record_output_info($record, 'volunteer3', "Third post-UVA volunteer position", true),
        make_record_output_info($record, 'volunteerdates3', "Third volunteer dates", false, true),
        make_resp_output_info($responses, 'c134', 40, "Comments on volunteering", true, true, 62, 3),
        make_record_output_info($record, 'careerwoman', "Do you think of yourself as a career woman?", true, true),
        make_resp_output_info($responses, 'c136', 41, "Comments on career woman question", true, true, 62, 3),
        make_record_output_info($record, 'workbarriers', "Barriers faced in work and volunteering", true, true),
        make_resp_output_info($responses, 'c138', 42, "Comments on barriers question", true, true, 62, 3),
        make_record_output_info($record, 'currmarry', "Current marital status", true, true),
        make_resp_output_info($responses, 'c140', 43, "Comments on current marital status", true, true, 62, 3),
        make_record_output_info($record, 'agerange', "Current age range", true, true),
        make_resp_output_info($responses, 'c142', 44, "Comments on current age range", true, true, 62, 3),
        make_record_output_info($record, 'numchildren', "Number of children", true, true),
        make_resp_output_info($responses, 'c144', 45, "Comments on number of children", true, true, 62, 3),
        make_record_output_info($record, 'employstatus', "Current employment status", true, true),
        make_resp_output_info($responses, 'c146', 46, "Comments on employment status", true, true, 62, 3),
        make_resp_output_info($responses, 'c147', 47, "Additional comments", true, true, 62, 3)
    );

    array_push($current_form, array(
        'field' => 'num_page',
        'value' => $num_page
    ));
    array_push($current_form, array(
        'field' => 'rec_num2',
        'value' => $record['accessionNumber']
    ));

    $template_params['id']      = $record['accessionNumber'];
    $template_params['fields']  = $display_fields;
    error_log('(B) current_form = ' . print_r($current_form, true));
    $template_params['current'] = $current_form;
    $template_name              = 'display';
    $partials['displayfield']   = $templates['displayfield'];
}

echo $mustache->render($templates[$template_name], $template_params, $partials);

