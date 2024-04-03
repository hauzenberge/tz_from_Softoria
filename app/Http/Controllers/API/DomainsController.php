<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Helpers\DataSEOHelper;

use App\Models\FormData;
use App\Models\Target;

use Illuminate\Support\Facades\Validator;

class DomainsController extends Controller
{
    public function index()
    {
        return Target::paginate(10);
    }

    public function add(Request $request)
    {
        //dd($request->post());
        $validator = Validator::make($request->all(), [
            "targets" => ['required', 'array'],
            "exclude_targets" => ['required', 'array']
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $exclude_targets = $request->post("exclude_targets");

        $items = collect($request->post("targets"))
            ->map(function ($item) use ($exclude_targets) {
                // dd($item);
                return [
                    'target' => $item,
                    'exclude_targets' => json_encode($exclude_targets)
                ];
            })
            ->toArray();
        // dd($items);
        FormData::insert($items);
        return [
            'status' => 'Ok'
        ];
    }
}
