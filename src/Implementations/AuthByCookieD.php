<?php

namespace MGGFLOW\MsvcAuth\Implementations;

use MGGFLOW\AuthBase\Interfaces\AuthByCookieData;
use MGGFLOW\MsvcAuth\DbData;

class AuthByCookieD extends DbData implements AuthByCookieData
{
    public function getUserById($id): ?object
    {
        return $this->connection->table($this->tableName)
            ->where('id', $id)
            ->first();
    }
}