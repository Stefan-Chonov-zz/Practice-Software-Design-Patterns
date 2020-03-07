<?php

class NotFound
{
    public function index()
    {
        $response = [ 'error' => 'Path not found!' ];

        header('Content-Type: application/json');
        echo json_encode($response);
    }
}