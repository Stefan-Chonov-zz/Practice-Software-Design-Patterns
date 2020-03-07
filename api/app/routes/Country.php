<?php

use App\Core\BaseRoute;

class Country extends BaseRoute
{
    /**
     * Country constructor.
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