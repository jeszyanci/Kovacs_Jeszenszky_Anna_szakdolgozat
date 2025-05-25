<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Method;
use App\Models\Log;

class RepairController extends Controller {

    public function saveRepair(Request $request) {
        $record = null;

        if ($request->id) {
            $record = DB::table('repairs')
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
            $record = DB::table('repairs')
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
            Log::create('repair', 'Repair saved');

            return $record;
        } else {
            return 'false';
        }
    }

    public function saveSketch(Request $request) {
        $id = $request->input('id');
        $image = $request->file('image');
        $ext = $image->extension();

        $path = "repairSketches/$id/sketch.png";
        Storage::disk('local')->put($path, file_get_contents($image));

        Log::create('repair', 'Image saved');
    }

    public function getList(Request $request) {
        $repairs = DB::table('repairs')
            ->select(
                'id',
                'buyername',
                'buyerphone',
                'details',
                'price',
                'state',
                'created_at',
                'deadline'
            )
            ->get();

        return ($repairs ? json_encode($repairs) : false);
    }

    public function searchInOrders(Request $request) {
        $column = $request->column;
        $word   = $request->word;
        $dateStart = $request->dateStart;
        $dateEnd   = $request->dateEnd;

        $repairs = null;

        if ($word != "") {
            $repairs = DB::table('repairs')
                ->select()
                ->where($column, 'like', '%' . $word . '%')
                ->get();

        } else if ($column == "created_at" && $dateStart != "" && $dateEnd != "") {
            $repairs = DB::table('repairs')
                ->select()
                ->whereBetween($column, [$dateStart, $dateEnd])
                ->get();

        } /* else if ($column == "state") {
            $repairs = DB::table('repairs')
                ->select()
                ->where($column, $word)
                ->get();

        } */ else {
            $repairs = DB::table('repairs')
                ->select()
                ->get();
        }

        return ($repairs ? json_encode($repairs) : false);
    }
}