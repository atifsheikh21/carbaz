<?php

namespace Modules\Subscription\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Subscription\Entities\SubscriptionPlan;

use Modules\Subscription\Http\Requests\SubscriptionPlanRequest;
use Modules\Subscription\Entities\SubscriptionHistory;

class SubscriptionPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $plans = SubscriptionPlan::orderBy('serial', 'asc')->get();

        return view('subscription::index', ['plans' => $plans]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('subscription::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(SubscriptionPlanRequest $request)
    {

        $plan = new SubscriptionPlan();
        $plan->plan_name = $request->plan_name;
        $plan->plan_price = $request->plan_price;
        $plan->expiration_date = $request->expiration_date;
        $plan->serial = $request->serial;
        $plan->max_car = $request->max_car;
        $plan->featured_car = $request->featured_car;
        $plan->status = $request->status ? 'active' : 'inactive';
        $plan->save();

        $notification = trans('translate.Create Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.subscription-plan.index')->with($notification);

    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('subscription::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $plan = SubscriptionPlan::findOrFail($id);

        return view('subscription::edit', ['plan' => $plan]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(SubscriptionPlanRequest $request, $id)
    {
        $plan = SubscriptionPlan::findOrFail($id);

        $plan->plan_name = $request->plan_name;
        $plan->plan_price = $request->plan_price;
        $plan->expiration_date = $request->expiration_date;
        $plan->serial = $request->serial;
        $plan->max_car = $request->max_car;
        $plan->featured_car = $request->featured_car;
        $plan->status = $request->status ? 'active' : 'inactive';
        $plan->save();

        $notification = trans('translate.Update Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.subscription-plan.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $purchase_qty = SubscriptionHistory::where('subscription_plan_id', $id)->count();
        if($purchase_qty > 0){
            $notification = trans('translate.Multiple order already created using it, so you can not delete this one');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }

        $plan = SubscriptionPlan::findOrFail($id);
        $plan->delete();

        $notification = trans('translate.Delete Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.subscription-plan.index')->with($notification);
    }
}
