<?php

namespace Examples\MatchaORM;

use MatchaORM\Model;

class User extends Model
{
    protected $fillable = ['name', 'email'];
}
