<?php
namespace App\Models;
class ListModel extends Model
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

    public function getById($id = null)
    {
        if ($id) {
            $data= $this->where(["id = $id"])->selectOne();
            if($data && isset($data[0])){
                return $data[0];
            }
        }
        return null;
    }
}
