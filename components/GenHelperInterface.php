<?php
namespace components;

interface GenHelperInterface
{
    public function search($params);
    public function getData();
    public function exportColumns();
    public  function searchFields();
    public  function TableColumns();
}