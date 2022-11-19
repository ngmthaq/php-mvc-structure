<?php

namespace App\Models;

class User extends Model
{
    protected function setTableName(): string
    {
        return "users";
    }

    protected function setPrimaryKey(): string
    {
        return "id";
    }
}
