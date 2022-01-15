<?php

namespace App\Models;

class User extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'email',
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

    public function update($parameters)
    {
        if (isset($parameters['password'])) {
            $parameters['password'] = '"'.password_hash($parameters['password'], PASSWORD_DEFAULT).'"';
        }
        parent::update($parameters);
    }

    public function getByEmail($email = null)
    {
        if ($email) {
            return $this->where(["email = '$email'"])->selectOne();
        }
        return null;
    }
}
