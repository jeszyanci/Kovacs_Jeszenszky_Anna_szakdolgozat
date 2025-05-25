<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Method;
use App\Models\Log;

class OrderController extends Controller {

    public function saveOrder(Request $request) {
        $record = null;

        if ($request->id) {
            $record = DB::table('orders')
                ->where('id', $request->id)
                ->update([
                    'buyername'  => $request->buyerName,
                    'buyerphone' => $request->buyerPhone,
                    'deadline'   => $request->deadline,
                    'details'    => $request->details,
                    'price'      => $request->price,
                    'state'      => $request->state,
                    'deadline'   => $request->deadline
                ]);

        } else {
            $record = DB::table('orders')
                ->insertGetId([
                    'buyername'  => $request->buyerName,
                    'buyerphone' => $request->buyerPhone,
                    'deadline'   => $request->deadline,
                    'details'    => $request->details,
                    'price'      => $request->price,
                    'state'      => $request->state,
                    'deadline'   => $request->deadline
                ]);
        }

        if ($record) {
            Log::create('order', 'Order saved');

            return $record;
        } else {
            return 'false';
        }
    }

    public function saveSketch(Request $request) {
        $id = $request->input('id');
        $image = $request->file('image');
        $ext = $image->extension();

        $path = "orderSketches/$id/sketch.png";
        Storage::disk('local')->put($path, file_get_contents($image));

        Log::create('order', 'Image saved');
    }

    public function getList(Request $request) {
        $orders = DB::table('orders')
            ->select(
                'id',
                'buyername',
                'buyerphone',
                'details',
                'price',
                'state',
                'progressData',
                'created_at',
                'deadline'
            )
            ->get();

        return ($orders ? json_encode($orders) : false);
    }

    public function getMethods(Request $request) {
        $methods = Method::getAllMethod();

        $jsonFile = json_encode([
            'methods' => $methods
        ]);

        return $jsonFile;
    }

    public function searchInOrders(Request $request) {
        $column = $request->column;
        $word   = $request->word;
        $dateStart = $request->dateStart;
        $dateEnd   = $request->dateEnd;

        $orders = null;

        if ($word != "") {
            $orders = DB::table('orders')
                ->select()
                ->where($column, 'like', '%' . $word . '%')
                ->get();

        } else if ($column == "created_at" && $dateStart != "" && $dateEnd != "") {
            $orders = DB::table('orders')
                ->select()
                ->whereBetween($column, [$dateStart, $dateEnd])
                ->get();

        } else if ($column == "state") {
            $orders = DB::table('orders')
                ->select()
                ->where($column, $word)
                ->get();

        } else {
            $orders = DB::table('orders')
                ->select()
                ->get();
        }

        return ($orders ? json_encode($orders) : false);
    }

    public function checkDeadlines(Request $request) {
        $date = date('Y-m-d', strtotime('+4 day'));

        $orders = DB::table('orders')
            ->select()
            ->where('deadline', '<', $date)
            ->get();
                
        return ($orders ? json_encode($orders) : false);
    }

    public function save_methodProgress(Request $request) {
        $orderID = $request->id;
        $progressData = $request->progressData;

        DB::table('orders')
            ->where('id', $orderID)
            ->update([
                'progressData' => $progressData
            ]);

        Log::create('order', 'Method and progress saved');
    }
}