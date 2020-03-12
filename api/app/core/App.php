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

    /**
     *
     */
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

    /**
     * @return array
     */
    public function parseUrl()
    {
        if (isset($_GET['url'])) {
            return $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
    }

    /**
     * @return array
     */
    public function getRequestParameters()
    {
        try {
            $putDeleteParameters = file_get_contents("php://input");
            if (!empty($putDeleteParameters)) {
                $putDeleteParameters = json_decode($putDeleteParameters, true);
            }

            if (empty($putDeleteParameters)) {
                $putDeleteParameters = [];
            }

            $getParameters = $_GET;

            $postParameters = $_POST;
            if (count($putDeleteParameters) == 0 && !empty($postParameters)) {
                foreach ($postParameters as $key => $value) {
                    if (strpos($key, '_') !== false) {
                        throw new \Exception(json_encode([ 'message' => 'Post parameters should be posted in raw JSON format. Form-data is not supported.' ]));
                    }
                }
            } else {
                $postParameters = [];
            }

            return array_merge_recursive($putDeleteParameters, $getParameters, $postParameters);
        } catch (\Exception $ex) {
            print_r($ex->getMessage());
        }
    }
}