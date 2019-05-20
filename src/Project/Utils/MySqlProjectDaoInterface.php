<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 19/03/2019
 * Time: 9:15
 */

namespace Project\Utils;


class MySqlProjectDaoInterface implements ProjectDaoInterface
{
    private  $dbConection;

    function __construct(\PDO $dbConeection)
    {
        $this->dbConection = $dbConeection;
    }
    public function fetchAll($sql, $params = null)
    {
        // TODO: Implement fetchAll() method.
        $stm = $this->dbConection->prepare($sql);
        $stm->execute($params);
        return $stm->fetchAll();
    }

    public function fetch($sql, $params = null)
    {
        // TODO: Implement fetch() method.
        $stm = $this->dbConection->prepare($sql);
        $stm->execute($params);
        return $stm->fetch();
    }

    public function execute($sql, $params = null)
    {
        // TODO: Implement execute() method.
        $stm = $this->dbConection->prepare($sql);
        $stm->execute($params);
    }

    public function insert($sql, $params = null)
    {
        // TODO: Implement insert() method.
        $stm = $this->dbConection->prepare($sql);
        $stm->execute($params);
        return $this->dbConection->lastInsertId();
    }

}