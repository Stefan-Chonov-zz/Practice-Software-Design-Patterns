<?php

namespace App\Core;

class App
{
    protected $path;
    protected $direction;
    protected $crumbs;

    public function __construct()
    {
        $this->path = 'NotFound';
        $this->direction = 'index';
        $this->crumbs = [];

        $this->startWalking();
    }

    private function startWalking()
    {
        $url = $this->parseUrl();

        if (file_exists('../app/routes/' . $url[0] . '.php')) {
            $this->path = $url[0];
            unset($url[0]);
        }

        require_once '../app/routes/' . $this->path . '.php';

        if (isset($url[1])) {
            if (method_exists($this->path, $url[1])) {
                $this->direction = $url[1];
                unset($url[1]);
            }
        }

        unset($_GET['url']);
        $this->crumbs = $this->getRequestParameters();

        $obj = new $this->path();
        $func = $this->direction;
        $obj->$func($this->crumbs);
    }

    public function parseUrl()
    {
        if (isset($_GET['url'])) {
            return $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
    }

    public function getRequestParameters()
    {
        $putDeleteParameters = file_get_contents("php://input");
        $putDeleteParameters = !empty($putDeleteParameters) ? json_decode($putDeleteParameters, true) : [];
        $getParameters = $_GET;
        $postParameters = $_POST;

        return array_merge_recursive($putDeleteParameters, $getParameters, $postParameters);
    }
}