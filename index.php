<?php

require_once('./alumna.php');
require_once(ALUMNA_DIR . '/helpers/search.php');

/**
 * ROUTER
 */

// Instantiate controller.
$controller = new AlumnaController();

// Map routes.
dispatch_get('/', array($controller, 'index'));
dispatch_get('/search', array($controller, 'search'));
// dispatch_post('/search', array($controller, 'results'));


/**
 * CONTROLLER
 */

class AlumnaController
{

    /**
     * Instantiate mustache.
     *
     * @return void
     */
    public function __construct()
    {

        $this->templates = new MustacheLoader(TEMPLATE_DIR);

        $partials  = $this->_getPartials(array('header', 'footer'));

        $this->mustache = new Mustache(null, null, $partials);

        $this->__db = null;

    }

    /**
     * This returns a partials array for the listed partials.
     *
     * @param $names array This is a list of the partial names.
     *
     * @return Associative array.
     * @author Eric Rochester <err8n@virginia.edu>
     **/
    protected function _getPartials($names)
    {
        $partials = array();
        foreach ($names as $name) {
            $partials[$name] = $this->templates[$name];
        }
        return $partials;
    }

    /**
     * This returns the given template, rendered with the values and partials.
     *
     * @param $template string     The name of the template to render.
     * @param $values   array      The associative array of values to insert 
     * into the template.
     * @param $partials array|null The list of names of partials for that 
     * template, or null for the default set of partials.
     *
     * @return String
     * @author Eric Rochester <err8n@virginia.edu>
     **/
    public function render($template, $values, $partials=null)
    {
        return $this->mustache->render(
            $this->templates[$template],
            $values, 
            ($partials == null) ? null : $this->_getPartials($partials)
        );
    }

    /**
     * This lazily creates and returns a db manager. If one has already been created, it is returned.
     *
     * @return DatabaseManager
     * @author Eric Rochester <err8n@virginia.edu>
     **/
    public function db()
    {
        if ($this->__db == null) {
            $config = parse_ini_file(DB_CONFIG_FILE);
            $this->__db = new DatabaseManager($config);
        }
        return $this->__db;
    }

    /**
     * Home page.
     *
     * @return void
     */
    public function index()
    {
        error_log('CONTROLLER: index');
        return $this->_render('index', array());
    }

    /**
     * Search form.
     *
     * @return void
     */
    public function search()
    {
        error_log('CONTROLLER: search');
        $helper = new SearchHelper($this);
        return $helper->render();
    }

    /**
     * Search results
     *
     * @return void
     */
    public function results()
    {
        error_log('CONTROLLER: results');
        // ** render.
    }

    /**
     * Record view
     *
     * @return void
     */
    public function record()
    {
        error_log('CONTROLLER: record');
        // ** render.
    }

}


/**
 * RUN
 */
run();
