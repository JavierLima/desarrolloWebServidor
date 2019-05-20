<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 19/03/2019
 * Time: 9:14
 */

namespace Project\Utils;


interface ProjectDaoInterface
{
    public function fetchAll($sql,$params = null);
    public function fetch($sql, $params = null);
    public function execute($sql,$params = null);
    public function insert($sql, $params = null);
}