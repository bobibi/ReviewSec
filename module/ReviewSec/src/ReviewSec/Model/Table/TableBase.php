<?php
namespace ReviewSec\Model\Table;

use Zend\Db\TableGateway\TableGateway;

abstract class TableBase
{

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function beginTransaction()
    {
        $this->tableGateway->getAdapter()
            ->getDriver()
            ->getConnection()
            ->beginTransaction();
    }

    public function commit()
    {
        $this->tableGateway->getAdapter()
            ->getDriver()
            ->getConnection()
            ->commit();
    }

    public function rollback()
    {
        $this->tableGateway->getAdapter()
            ->getDriver()
            ->getConnection()
            ->rollback();
    }
}