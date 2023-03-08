<?php

namespace MGGFLOW\MsvcAuth\Implementations;

use MGGFLOW\MsvcAuth\DbData;
use MGGFLOW\AuthBase\Interfaces\AuthByPasswordData;

class AuthByPasswordD extends DbData implements AuthByPasswordData
{
    /**
     * @inheritDoc
     */
    public function getUserByEmail($email): ?object
    {
        return $this->connection->table($this->tableName)
            ->where('email', $email)
            ->first();
    }

    /**
     * @inheritDoc
     */
    public function getUserByUsername($username): ?object
    {
        return $this->connection->table($this->tableName)
            ->where('username', $username)
            ->first();
    }
}
