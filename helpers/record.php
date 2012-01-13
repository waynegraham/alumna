<?php

require_once dirname(__FILE__) . '/results.php';

/**
 * This is a helper class that assembles the page displaying a single result from a search.
 **/
class RecordHelper extends ResultsHelper
{

    function __construct($controller)
    {
        parent::__construct($controller);
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

        if (! $this->_get('rec_num')) {
            $helper = new ResultsHelper($this->controller);
            $output = $helper->render();
        } else {
            $output = $this->_render();
        }

        return $output;
    }

    /**
     * This returns the name of the base template for this page.
     *
     * @return string
     * @author Eric Rochester <err8n@virginia.edu>
     **/
    protected function _getTemplateName()
    {
        return 'browse';
    }

    /**
     * This returns the data needed to populate the template.
     *
     * @return array
     * @author Eric Rochester <err8n@virginia.edu>
     **/
    protected function _getTemplateData()
    {
        $vars = $this->_getArray(array(
            'rec_num', 'rec_num2', 'remod', 'submitsearch', 'next_page',
            'prev_page', 'num_page'
        ));
        $this->_normalizeRecordNos($vars);

        $this->_addCurrentForm('submitsearch', $vars['submitsearch']);
        $this->_addCurrentForm('num_page',     $vars['num_page']);
        $this->_addCurrentForm('rec_num',      $vars['rec_num']);

        $this->_getFieldValues();

        $id            = $vars['rec_num'];
        $participant   = $this->_getParticipant($id);
        $responses     = $this->_getResponses($id);
        $response_data = $this->_formatResponses(
            $participant,
            $responses
        );

        $this->_addCurrentForm('num_page', $vars['num_page']);
        $this->_addCurrentForm('rec_num2', $participant['accessionNumber']);

        $this->_delCurrentForm('rec_num');

        error_log("FORM: " . print_r($this->current_form, true));
        $log = fopen('/tmp/record.log', 'w');
        fwrite(print_r($this->current_form, true));
        fclose($log);

        $data = array(
            'id'      => $participant['accessionNumber'],
            'fields'  => $response_data,
            'current' => $this->current_form
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
            'displayfield'
        );
    }

    /**
     * This takes a record, a field name and label and returns an assoc array 
     * with the label, how many rows the output widget should have, and the 
     * value.
     **/
    protected function _makeRecordOutputInfo($record, $field, $label,
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
    protected function _makeRespOutputInfo($responses, $field, $q, $label,
        $alpha=false, $omega=false, $cols=20, $colspan=0
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
        return $this->_makeRecordOutputInfo(
            $record, $field, $label, $alpha, $omega, $cols, $colspan
        );
    }

    /**
     * This returns the associative array with information about the survey 
     * participant.
     *
     * @param $id int The database ID of the participant
     *
     * @return array
     * @author Eric Rochester <err8n@virginia.edu>
     **/
    protected function _getParticipant($id)
    {
        $db = $this->_db();
        $query = sprintf(
            'SELECT * FROM iph WHERE accessionNumber=%s;',
            $db->escape($id)
        );
        $result = $db->query($query);
        $record = mysql_fetch_assoc($result);
        return $record;
    }

    /**
     * This returns the responses from the participant as an array list of 
     * associative arrays.
     *
     * @param $pid int The participant's ID in the database.
     *
     * @return array
     * @author Eric Rochester <err8n@virginia.edu>
     **/
    protected function _getResponses($pid)
    {
        $db = $this->_db();
        $query = sprintf(
            'SELECT * FROM openresponses WHERE accessionNumber=%s;',
            $db->escape($pid)
        );
        $result = $db->query($query);

        $responses = array();
        while ($resp = mysql_fetch_assoc($result)) {
            array_push($responses, $resp);
        }

        return $responses;
    }

    /**
     * This takes the output of the database and formats it for the template.
     *
     * @param $participant array Information about the participant.
     * @param $responses   array Information about the participant's 
     * responses.
     *
     * @return array
     * @author Eric Rochester <err8n@virginia.edu>
     **/
    protected function _formatResponses($participant, $responses)
    {
        $resp_info = array(
            $this->_makeRecordOutputInfo($participant, 'school', "First UVA program", true),
            $this->_makeRecordOutputInfo($participant, 'school2', "Second UVA program", false, true),
            # $this->_makeRecordOutputInfo($participant, 'school3', "Third UVA program"),
            $this->_makeRespOutputInfo($responses, 'c4', 1, "Comments on UVA programs", true, true, 62, 3),
            $this->_makeRecordOutputInfo($participant, 'hometown', "Home town", true),
            $this->_makeRecordOutputInfo($participant, 'homestate', "Home state", false, true),
            $this->_makeRespOutputInfo($responses, 'c7', 2, "Comments on Home town and state", true, true, 62, 3),
            $this->_makeRecordOutputInfo($participant, 'htclassification', "Home town classification", true, true),
            $this->_makeRespOutputInfo($responses, 'c9', 3, "Comments on Home town classification", true, true, 62, 3),
            $this->_makeRecordOutputInfo($participant, 'highschool', "High school classification", true, true),
            $this->_makeRespOutputInfo($responses, 'c11', 4, "Comments on High school classification", true, true, 62, 3),
            $this->_makeRecordOutputInfo($participant, 'coedhighschool', "Was your high school co-ed?", true, true),
            $this->_makeRespOutputInfo($responses, 'c13', 5, "Comments on Co-ed high school", true, true, 62, 3),
            $this->_makeRecordOutputInfo($participant, 'directlyFromHS', "Did you come directly from high school to UVA?", true, true),
            $this->_makeRespOutputInfo($responses, 'c15', 6, "Comments if not directly from high school", true, true, 62, 3),
            $this->_makeRecordOutputInfo($participant, 'activities', "Important activities before coming to UVA", true, true),
            $this->_makeRespOutputInfo($responses, 'c17', 7, "Comments on important activities", true, true, 62, 3),
            $this->_makeRecordOutputInfo($participant, 'married', "If married, was it before, during, or after UVA?", true, true),
            $this->_makeRespOutputInfo($responses, 'c19', 8, "Comments on time of marriage", true, true, 62, 3),
            $this->_makeRecordOutputInfo($participant, 'spouseAtUVA', "Was your spouse associated with UVA?", true, true),
            $this->_makeRespOutputInfo($responses, 'c21', 9, "Comments on spouses association with UVA", true, true, 62, 3),
            $this->_makeRecordOutputInfo($participant, 'mothersDegree1', "Mother's first degree", true),
            $this->_makeRecordOutputInfo($participant, 'mothersSchool1', "Mother's first college", false, true),
            $this->_makeRecordOutputInfo($participant, 'mothersSchool2', "Mother's second college", true),
            $this->_makeRecordOutputInfo($participant, 'mothersDegree2', "Mother's second degree", false, true),
            $this->_makeRecordOutputInfo($participant, 'mothersSchool3', "Mother's third college", true),
            $this->_makeRecordOutputInfo($participant, 'mothersDegree3', "Mother's third degree", false, true),
            $this->_makeRecordOutputInfo($participant, 'fathersDegree1', "Father's first degree", true),
            $this->_makeRecordOutputInfo($participant, 'fathersschool1', "Father's first college", false, true),
            $this->_makeRecordOutputInfo($participant, 'fathersschool2', "Father's second college", true),
            $this->_makeRecordOutputInfo($participant, 'fathersdegree2', "Father's second degree", false, true),
            $this->_makeRecordOutputInfo($participant, 'fathersschool3', "Father's third school", true),
            $this->_makeRecordOutputInfo($participant, 'fathersdegree3', "Father's third degree", false, true),
            $this->_makeRespOutputInfo($responses, 'c34', 10, "Comments on parents' schooling", true, true, 62, 3),
            $this->_makeRecordOutputInfo($participant, 'sisterschooling1', "First sister's level of schooling", true),
            $this->_makeRecordOutputInfo($participant, 'sisterschooling2', "Second sister's level of schooling", false, true),
            $this->_makeRecordOutputInfo($participant, 'sisterschooling3', "Third sister's level of schooling", true),
            $this->_makeRecordOutputInfo($participant, 'brotherschooling1', "First brother's level of schooling", false, true),
            $this->_makeRecordOutputInfo($participant, 'brotherschooling2', "Second brother's level of schooling", true),
            $this->_makeRecordOutputInfo($participant, 'brotherschooling3', "Third brother's level of schooling", false, true),
            $this->_makeRespOutputInfo($responses, 'c40', 11, "Comments on siblings' schooling", true, true, 62, 3),
            $this->_makeRecordOutputInfo($participant, 'familyatuva', "Family members at UVA", true, true),
            $this->_makeRespOutputInfo($responses, 'c42', 12, "Comments on family members at UVA", true, true, 62, 3),
            $this->_makeRecordOutputInfo($participant, 'mothersoccupation1', "Mother's first occupation", true),
            $this->_makeRecordOutputInfo($participant, 'mothersoccupation2', "Mother's second occupation", false, true),
            $this->_makeRecordOutputInfo($participant, 'mothersoccupation3', "Mother's third occupation", true),
            $this->_makeRecordOutputInfo($participant, 'fathersoccupation1', "Father's first occupation", false, true),
            $this->_makeRecordOutputInfo($participant, 'fathersoccupation2', "Father's second occupation", true),
            $this->_makeRecordOutputInfo($participant, 'fathersoccupation3', "Father's third occupation", false, true),
            $this->_makeRespOutputInfo($responses, 'c48', 13, "Comments on parents' occupations", true, true, 62, 3),
            $this->_makeRecordOutputInfo($participant, 'entereduva', "Date entered UVA", true),
            $this->_makeRecordOutputInfo($participant, 'leftuva', "Date left UVA", false, true),
            $this->_makeRecordOutputInfo($participant, 'othercollege1', "First other college", true),
            $this->_makeRecordOutputInfo($participant, 'otherconcentration1', "First other concentration", false, true),
            $this->_makeRecordOutputInfo($participant, 'otherdegree1', "First other degree", true),
            $this->_makeRecordOutputInfo($participant, 'otherdate1', "First other dates of attendance", false, true),
            $this->_makeRecordOutputInfo($participant, 'othercollege2', "Second other college", true),
            $this->_makeRecordOutputInfo($participant, 'otherconcentration2', "Second other concentration", false, true),
            $this->_makeRecordOutputInfo($participant, 'otherdegree2', "Second other degree", true),
            $this->_makeRecordOutputInfo($participant, 'otherdate2', "Second other dates of attendance", false, true),
            $this->_makeRecordOutputInfo($participant, 'othercollege3', "Third other college", true),
            $this->_makeRecordOutputInfo($participant, 'otherconcentration3', "Third other concentration", false, true),
            $this->_makeRecordOutputInfo($participant, 'otherdegree3', "Third other degree", true),
            $this->_makeRecordOutputInfo($participant, 'otherdate3', "Third other dates of attendance", false, true),
            $this->_makeRecordOutputInfo($participant, 'othercollege4', "Fourth other college", true),
            $this->_makeRecordOutputInfo($participant, 'otherconcentration4', "Fourth other concentration", false, true),
            $this->_makeRecordOutputInfo($participant, 'otherdegree4', "Fourth other degree", true),
            $this->_makeRecordOutputInfo($participant, 'otherdate4', "Fourth other dates of attendance", false, true),
            $this->_makeRecordOutputInfo($participant, 'othercollege5', "Fifth other college", true),
            $this->_makeRecordOutputInfo($participant, 'otherconcentration5', "Fifth other concentration", false, true),
            $this->_makeRecordOutputInfo($participant, 'otherdegree5', "Fifth other degree", true),
            $this->_makeRecordOutputInfo($participant, 'otherdate5', "Fifth other dates of attendance", false, true),
            $this->_makeRespOutputInfo($responses, 'c71', 14, "Comments on college experiences", true, true, 62, 3),
            $this->_makeRecordOutputInfo($participant, 'whychooseuva', "Why did you choose UVA?", true, true),
            $this->_makeRespOutputInfo($responses, 'c73', 15, "Comments on choice to attend UVA", true, true, 62, 3),
            $this->_makeRecordOutputInfo($participant, 'howfinanceuva', "How did you finance UVA?", true, true),
            $this->_makeRespOutputInfo($responses, 'c75', 16, "Comments on financing", true, true, 62, 3),
            $this->_makeRecordOutputInfo($participant, 'whychooseFOC', "Why did you choose your UVA concentration(s)?", true, true),
            $this->_makeRespOutputInfo($responses, 'c77', 17, "Comments on choice of concentration", true, true, 62, 3),
            $this->_makeRecordOutputInfo($participant, 'classroomexp', "Description of UVA classroom experiences", true, true),
            $this->_makeRespOutputInfo($responses, 'c79', 18, "Comments on classroom experiences", true, true, 62, 3),
            $this->_makeRecordOutputInfo($participant, 'interactionprofs', "Description of UVA professor interactions", true, true),
            $this->_makeRespOutputInfo($responses, 'c81', 19, "Comments on professor interactions", true, true, 62, 3),
            $this->_makeRecordOutputInfo($participant, 'greatestimpact', "Who impacted your life at UVA?", true, true),
            $this->_makeRespOutputInfo($responses, 'c83', 20, "Comments on life impact", true, true, 62, 3),
            $this->_makeRecordOutputInfo($participant, 'mostremember', "Most memorable UVA educational experience", true, true),
            $this->_makeRespOutputInfo($responses, 'c85', 21, "Comments on memorable educational experience", true, true, 62, 3),
            $this->_makeRecordOutputInfo($participant, 'weekendacts', "UVA weekend activities", true, true),
            $this->_makeRespOutputInfo($responses, 'c87', 22, "Comments on weekend activities", true, true, 62, 3),
            $this->_makeRecordOutputInfo($participant, 'Xtracurrorgs', "UVA extra-curricular organizations", true, true),
            $this->_makeRespOutputInfo($responses, 'c89', 23, "Comments on extra-curricular organizations", true, true, 62, 3),
            $this->_makeRecordOutputInfo($participant, 'issues', "Important local, state, national, and international issues while at UVA", true, true),
            $this->_makeRespOutputInfo($responses, 'c91', 24, "Comments on important issues", true, true, 62, 3),
            $this->_makeRecordOutputInfo($participant, 'interestsexpressed', "How interest in issues was expressed", true, true),
            $this->_makeRespOutputInfo($responses, 'c93', 25, "Comments on how interest was expressed", true, true, 62, 3),
            $this->_makeRecordOutputInfo($participant, 'housing', "UVA housing ", true, true),
            $this->_makeRespOutputInfo($responses, 'c95', 26, "Comments on housing", true, true, 62, 3),
            $this->_makeRecordOutputInfo($participant, 'likedislikehousing', "Likes and dislikes about housing", true, true),
            $this->_makeRespOutputInfo($responses, 'c97', 27, "Comments on likes and dislikes", true, true, 62, 3),
            $this->_makeRecordOutputInfo($participant, 'meals', "Where were meals eaten", true, true),
            $this->_makeRespOutputInfo($responses, 'c99', 28, "Comments on meal location", true, true, 62, 3),
            $this->_makeRecordOutputInfo($participant, 'interactinCville', "Did you interact outside of UVA?", true, true),
            $this->_makeRespOutputInfo($responses, 'c101', 29, "Interaction detail", true, true, 62, 3),
            $this->_makeRecordOutputInfo($participant, 'Cvillememory', "Memories of Charlottesville", true, true),
            $this->_makeRespOutputInfo($responses, 'c103', 30, "Comments on Charlottesville memories", true, true, 62, 3),
            $this->_makeRecordOutputInfo($participant, 'partofUVAcomm', "Did you feel part of UVA community?", true),
            $this->_makeRecordOutputInfo($participant, 'partofcomm', "Did you feel part of community?", false, true),
            $this->_makeRespOutputInfo($responses, 'c106', 31, "Incidents that caused these community feelings", true, true, 62, 3),
            $this->_makeRecordOutputInfo($participant, 'vividmems', "Vivid UVA memories", true, true),
            $this->_makeRespOutputInfo($responses, 'c108', 32, "Comments on vivid memories", true, true, 62, 3),
            $this->_makeRecordOutputInfo($participant, 'marstatusinfluence', "Did marital status affect UVA experiences", true, true),
            $this->_makeRespOutputInfo($responses, 'c110', 33, "Comments on influence of marital status", true, true, 62, 3),
            $this->_makeRecordOutputInfo($participant, 'culturalbackgroundinf', "Did cultural background affect UVA experiences", true, true),
            $this->_makeRespOutputInfo($responses, 'c112', 34, "Comments on influence of cultural background", true, true, 62, 3),
            $this->_makeRecordOutputInfo($participant, 'preparedpostUVA', "Did UVA prepare you for post-UVA life?", true, true),
            $this->_makeRespOutputInfo($responses, 'c114', 35, "Comments on preparedness", true, true, 62, 3),
            $this->_makeRecordOutputInfo($participant, 'mostsigeventsince', "Most significant post-UVA events", true, true),
            $this->_makeRespOutputInfo($responses, 'c116', 36, "Comments on most significant event", true, true, 62, 3),
            $this->_makeRecordOutputInfo($participant, 'impactonprofessional', "Personal factors that affected professional life", true, true),
            $this->_makeRespOutputInfo($responses, 'c118', 37, "Comments on personal factors", true, true, 62, 3),
            $this->_makeRecordOutputInfo($participant, 'otherfactors', "Other factors affecting life after the university", true, true),
            $this->_makeRespOutputInfo($responses, 'c120', 38, "Comments on other factors", true, true, 62, 3),
            $this->_makeRecordOutputInfo($participant, 'position1', "First post-UVA job", true),
            $this->_makeRecordOutputInfo($participant, 'dates1', "First job dates", false, true),
            $this->_makeRecordOutputInfo($participant, 'position2', "Second post-UVA job", true),
            $this->_makeRecordOutputInfo($participant, 'dates2', "Second job dates", false, true),
            $this->_makeRecordOutputInfo($participant, 'position3', "Third post-UVA job", true),
            $this->_makeRecordOutputInfo($participant, 'dates3', "Third job dates", false, true),
            $this->_makeRespOutputInfo($responses, 'c127', 39, "Comments on post-UVA jobs", true, true, 62, 3),
            $this->_makeRecordOutputInfo($participant, 'volunteer1', "First post-UVA volunteer position", true),
            $this->_makeRecordOutputInfo($participant, 'volunteerdates1', "First volunteer dates", false, true),
            $this->_makeRecordOutputInfo($participant, 'volunteer2', "Second post-UVA volunteer position", true),
            $this->_makeRecordOutputInfo($participant, 'volunteerdates2', "Second volunteer dates", false, true),
            $this->_makeRecordOutputInfo($participant, 'volunteer3', "Third post-UVA volunteer position", true),
            $this->_makeRecordOutputInfo($participant, 'volunteerdates3', "Third volunteer dates", false, true),
            $this->_makeRespOutputInfo($responses, 'c134', 40, "Comments on volunteering", true, true, 62, 3),
            $this->_makeRecordOutputInfo($participant, 'careerwoman', "Do you think of yourself as a career woman?", true, true),
            $this->_makeRespOutputInfo($responses, 'c136', 41, "Comments on career woman question", true, true, 62, 3),
            $this->_makeRecordOutputInfo($participant, 'workbarriers', "Barriers faced in work and volunteering", true, true),
            $this->_makeRespOutputInfo($responses, 'c138', 42, "Comments on barriers question", true, true, 62, 3),
            $this->_makeRecordOutputInfo($participant, 'currmarry', "Current marital status", true, true),
            $this->_makeRespOutputInfo($responses, 'c140', 43, "Comments on current marital status", true, true, 62, 3),
            $this->_makeRecordOutputInfo($participant, 'agerange', "Current age range", true, true),
            $this->_makeRespOutputInfo($responses, 'c142', 44, "Comments on current age range", true, true, 62, 3),
            $this->_makeRecordOutputInfo($participant, 'numchildren', "Number of children", true, true),
            $this->_makeRespOutputInfo($responses, 'c144', 45, "Comments on number of children", true, true, 62, 3),
            $this->_makeRecordOutputInfo($participant, 'employstatus', "Current employment status", true, true),
            $this->_makeRespOutputInfo($responses, 'c146', 46, "Comments on employment status", true, true, 62, 3),
            $this->_makeRespOutputInfo($responses, 'c147', 47, "Additional comments", true, true, 62, 3)
        );
        return $resp_info;
    }

}

