<?php

/**
 * This is a base helper class.
 **/
abstract class BaseHelper
{

    /**
     * This is the primary controller, giving this access to the DB manager and templating.
     *
     * @var AlumnaControler
     **/
    var $controller;
    
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
     * This returns the base template for this page.
     *
     * @return string
     * @author Eric Rochester <err8n@virginia.edu>
     **/
    abstract protected function _getTemplateName();

    /**
     * This should return the data needed to pass to the template.
     *
     * @return array
     * @author Eric Rochester <err8n@virginia.edu>
     **/
    abstract protected function _getTemplateData();

    /**
     * This should return the partials that the template requires, or null if 
     * the standard partials are sufficient.
     *
     * @return array list|null
     * @author Eric Rochester <err8n@virginia.edu>
     **/
    protected function _getPartialNames()
    {
        return null;
    }

    /**
     * This renders the page and returns it as a string.
     *
     * @return string
     * @author Eric Rochester <err8n@virginia.edu>
     **/
    public function render()
    {
        $template = $this->_getTemplateName();
        $data     = $this->_getTemplateData();
        $partials = $this->_getPartialNames();

        return $this->controller->render($template, $data, $partials);
    }
}

