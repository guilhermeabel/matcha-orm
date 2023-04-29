<?php

namespace Examples\MatchaORM;

use MatchaORM\Model;

class User extends Model
{
    protected array $fillable = ['name', 'email', 'password'];
}
