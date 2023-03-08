<?php

namespace MGGFLOW\MsvcAuth;

use Illuminate\Database\ConnectionInterface;

class DbData
{
    protected ConnectionInterface $connection;
    protected string $tableName;

    public function __construct(ConnectionInterface $dbConnection,string $table)
    {
        $this->connection = $dbConnection;
        $this->tableName = $table;
    }
}
