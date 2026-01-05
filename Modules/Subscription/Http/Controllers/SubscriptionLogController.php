<?php

namespace Modules\Subscription\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Subscription\Entities\SubscriptionHistory;
use Modules\Car\Entities\Car;

class SubscriptionLogController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $histories = SubscriptionHistory::latest()->get();

        return view('subscription::history', ['histories' => $histories]);
    }

    public function pending_index()
    {
        $histories = SubscriptionHistory::where('status', 'pending')->latest()->get();

        return view('subscription::pending_history', ['histories' => $histories]);
    }




    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $history = SubscriptionHistory::findOrFail($id);

        return view('subscription::history_detail', ['history' => $history]);
    }


    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $history = SubscriptionHistory::findOrFail($id);
        $history->delete();

        $notification = trans('translate.Deleted Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.purchase-history')->with($notification);
    }

    public function approval_payment($id)
    {
        $history = SubscriptionHistory::findOrFail($id);

        SubscriptionHistory::where('user_id', $history->user_id)->update(['status' => 'expired']);

        $history->payment_status = 'success';
        $history->status = 'active';
        $history->save();

        $expiration_date = $history->expiration_date;

        if($expiration_date == 'lifetime'){
            Car::where('agent_id', $history->user_id)->update(['expired_date' => null]);
        }else{
            Car::where('agent_id', $history->user_id)->update(['expired_date' => $expiration_date]);
        }

        $cars = Car::where('agent_id', $history->user_id)->get();

        if($cars->count() > $history->max_car){

            foreach($cars as $index => $car){
                if($index > $history->max_car){
                    $car->approved_by_admin = 'pending';
                    $car->save();
                }
            }
        }


        $notification = trans('translate.Payment approved Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }


}
