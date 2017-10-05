<?php

namespace tete0148\PHPRouter;

class BaseController {

    /**
     * @var App
     */
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function getApp()
    {
        return $this->app;
    }
}