<?php
namespace App\Models;
class Item extends Model
{
    protected $table = 'lists';

    protected $fillable = [
        'name',
    ];

    public function insert($parameters)
    {    
        parent::insert($parameters);
    }

    public function update($parameters)
    {
        parent::update($parameters);
    }
}
