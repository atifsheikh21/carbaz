<?php

namespace Modules\CompareList\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Modules\CompareList\Entities\CompareList;

class CompareListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::guard('api')->user();


        $compareList = CompareList::where('user_id', $user->id)->with('car')->get();

        return response()->json(['compareList' => $compareList]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'car_id' => 'required|integer|exists:cars,id',
        ]);

        $user = Auth::guard('api')->user();

        $compareList = CompareList::updateOrCreate(
            ['user_id' => $user->id, 'car_id' => $request->car_id],
            ['user_id' => $user->id, 'car_id' => $request->car_id]
        );

        $compareList = CompareList::with('car')->where(['user_id' => $user->id, 'car_id' => $request->car_id])->first();


        return response()->json(['message' => trans('Added to compare list'), 'compareList' => $compareList]);

    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = Auth::guard('api')->user();

        $compareList = CompareList::where(['user_id' => $user->id, 'car_id' => $id])->first();

        if ($compareList) {
            $compareList->delete();
        }

        return response()->json(['message' => trans('Removed to compare list')]);
    }
}
