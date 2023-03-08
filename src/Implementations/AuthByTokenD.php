<?php

namespace MGGFLOW\MsvcAuth\Implementations;

use MGGFLOW\MsvcAuth\DbData;
use MGGFLOW\AuthBase\Interfaces\AuthByTokenData;

class AuthByTokenD extends DbData implements AuthByTokenData
{
    /**
     * @inheritDoc
     */
    public function getUserByToken($token): ?object
    {
        return $this->connection->table($this->tableName)
            ->where('access_token', $token)
            ->first();
    }
}
