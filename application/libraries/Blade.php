<?php

use eftec\bladeone\BladeOne;

class Blade extends BladeOne
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct(VIEWPATH, CACHEPATH, ENVIRONMENT === 'production' ? BladeOne::MODE_AUTO : BladeOne::MODE_DEBUG);
        $this->setBaseUrl(base_url('assets'));
        $this->directiveRT('sets', function (string $text) {
            echo sets($text);
        });
        $this->directiveRT('lang', function (string $text) {
            echo lang($text);
        });
        $this->directiveRT('base_url', function (string $path = '') {
            echo base_url($path);
        });
    }

    /**
     * @param string $view
     * @param array $variables
     * @return void
     */
    public function load(string $view, array $variables = []): void
    {
        try {
            echo $this->run($view, $variables);
        } catch (Exception $e) {
            echo $e;
        }
        exit(0);
    }

}
