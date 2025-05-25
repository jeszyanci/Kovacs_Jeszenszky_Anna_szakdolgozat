<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Method extends Model
{
    protected $table = 'methods';
    protected $primaryKey = 'id';
    

    public static function getAllMethod() : Array {
        $methods = Method::all();
        $result = [];

        foreach ($methods as $method) {
            array_push($result, $method);
        }

        return $result;
    }

    public function getName() {
        return $this->name;
    }
}