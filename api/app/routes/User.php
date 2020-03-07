<?php

use App\Core\BaseRoute;

class User extends BaseRoute
{
    protected $modelName;

    public function __construct()
    {
        $this->modelName = get_class($this);

        parent::__construct($this->modelName);
    }

    public function index($data = [])
    {
        parent::index($data);
    }
}