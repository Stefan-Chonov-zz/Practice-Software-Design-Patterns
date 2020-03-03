<?php

namespace Core\Interfaces;

interface RepositoryInterface
{
    function create($model);
    function getById($id);
    function getAll();
    function update($model);
    function delete($id);
}