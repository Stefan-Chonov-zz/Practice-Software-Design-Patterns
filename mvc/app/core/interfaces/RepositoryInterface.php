<?php

namespace Core\Interfaces;

interface RepositoryInterface
{
    function create($model);
    function findById($id);
    function getAll();
    function update($model);
    function delete($id);
}