<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class News extends Model
{
    use HasFactory;

    protected $attributes = [
        'user_id'  => "",
        'content' => ""
    ];

    public static function create($type, $content) {
        DB::table('news')->insert([
            'user_id'  => Auth::id(),
            'content' => $content
        ]);
    }
}
