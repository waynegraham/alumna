<?php

require_once('./alumna.php');

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

        $partials  = array(
            'header' => $this->templates['header'],
            'footer' => $this->templates['footer']
        );

        $this->mustache = new Mustache(null, null, $partials);

    }

    /**
     * Home page.
     *
     * @return void
     */
    public function index()
    {
        return $this->mustache->render(
            $this->templates['index'], array()
        );
    }

    /**
     * Search form.
     *
     * @return void
     */
    public function search()
    {
        return $this->mustache->render(
            $this->templates['search'], array()
        );
    }

    /**
     * Search results
     *
     * @return void
     */
    public function results()
    {
        // ** render.
    }

    /**
     * Record view
     *
     * @return void
     */
    public function record()
    {
        // ** render.
    }

}


/**
 * RUN
 */
run();
