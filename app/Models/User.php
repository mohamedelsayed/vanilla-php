<?php

namespace App\Models;

class User extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'mail',
        'name',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    public function insert($parameters)
    {
        if (isset($parameters['password'])) {
            $parameters['password'] = password_hash($parameters['password'], PASSWORD_DEFAULT);
        }
        parent::insert($parameters);
    }
    public function getByEmail($mail=null)
    {
        if($mail){
           return $this->where(["mail = '$mail'"])->selectOne();
        }
        return null;
    }
    public function getObject(){

    }
}