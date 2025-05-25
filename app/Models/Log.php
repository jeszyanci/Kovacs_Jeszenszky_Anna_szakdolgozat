<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Log extends Model
{
    use HasFactory;

    protected $attributes = [
        'userid'  => "",
        'type'    => "",
        'content' => ""
    ];

    public static function create($type, $content) {
        DB::table('logs')->insert([
            'userid'  => Auth::id(),
            'type'    => $type,
            'content' => $content
        ]);
    }
}
