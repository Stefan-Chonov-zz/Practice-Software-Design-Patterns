<?php

use App\Core\BaseRoute;

class User extends BaseRoute
{
    protected $modelName;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->modelName = get_class($this);

        parent::__construct($this->modelName);
    }

    /**
     * @param array $data
     * @return void
     */
    public function index($data = [])
    {
        parent::index($data);
    }
}