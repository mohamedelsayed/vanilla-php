<?php
namespace App\Models;

class PasswordResets extends Model
{
    protected $table = 'password_resets';
    
    protected $fillable = [
        'email',
        'token',
    ];

    public function getByToken($token = null)
    {
        if ($token) {
            return $this->where(["token = '$token'"])->selectOne();
        }
        return null;
    }
}