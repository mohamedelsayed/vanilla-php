<?php
namespace App\Models;
class Item extends Model
{
    protected $table = 'items';

    protected $fillable = [
        'name',
        'list_id',
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
