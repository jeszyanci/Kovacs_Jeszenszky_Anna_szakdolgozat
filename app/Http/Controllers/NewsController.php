<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\News;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Events\NewsUpdated;

class NewsController extends Controller {
    
    public function saveNews(Request $request) {
        $recordID = DB::table('news')
            ->insertGetId([
                'user_id' => Auth::id(),
                'content' => $request->content
            ]);

        $loggedUser = Auth::user()->id;
        $isAdmin    = User::find($loggedUser)->isAdmin();

        $newData = News::find($recordID);
        $findUser = User::find($newData->user_id);

        $result = [
            'id'      => $newData->id,
            'user'    => $findUser->nickname,
            'content' => $newData->content,
            'date'    => $newData->created_at
        ];

        // event(new NewsUpdated("Teszt üzenet"));
        NewsUpdated::dispatch($result);

        Log::create('news', 'Created news');

        return json_encode(['data' => $result]);
    }

    public function getNews(Request $request) {
        $news = DB::table('news')
            ->select()
            ->get();

        $result = [];

        $loggedUser = Auth::user()->id;
        $isAdmin    = User::find($loggedUser)->isAdmin();

        foreach ( $news as $elem ) {
            $findUser = User::find($elem->user_id);
            $data = [
                'id'      => $elem->id,
                'user'    => $findUser->nickname,
                'content' => $elem->content,
                'date'    => $elem->created_at,
                'modifiable' => $elem->user_id == $loggedUser || $isAdmin,
            ];

            array_push($result, $data);
        }

        return json_encode(['result' => $result]);
    }

    public function deleteNews(Request $request) {
        DB::table('news')
            ->where('id', $request->id)
            ->delete();

        Log::create('news', 'Deleted news');

        return json_encode(['result' => 'ok']);
    }
}