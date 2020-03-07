<?php

use App\Core\BaseRoute;

class User extends BaseRoute
{
    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct(get_class($this));
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