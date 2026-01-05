<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Modules\Subscription\Entities\SubscriptionHistory;
use Modules\Car\Entities\Car;
use Carbon\Carbon;

class DashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function dashboard(){

        $lable = array();
        $data = array();
        $start = new Carbon('first day of this month');
        $last = new Carbon('last day of this month');
        $first_date = $start->format('Y-m-d');
        $last_date = $last->format('Y-m-d');
        $today = date('Y-m-d');
        $length = date('d')-$start->format('d');

        for($i=1; $i <= $length+1; $i++){

            $date = '';
            if($i == 1){
                $date = $first_date;
            }else{
                $date = $start->addDays(1)->format('Y-m-d');
            };

            $sum = SubscriptionHistory::whereDate('created_at', $date)->sum('plan_price');
            $data[] = $sum;
            $lable[] = $i;

        }

        $data = json_encode($data);
        $lable = json_encode($lable);

        $total_car = Car::count();
        $awaiting_car = Car::where('approved_by_admin', 'pending')->count();
        $featured_car = Car::where('is_featured', 'enable')->count();;
        $total_user = User::where('status', 'enable')->count();

        $recent_cars = Car::with('translate','dealer')->get()->take(10);

        return view('admin.dashboard', ['data' => $data, 'lable' => $lable, 'awaiting_car' => $awaiting_car, 'featured_car' => $featured_car, 'total_car' => $total_car, 'total_user' => $total_user, 'recent_cars' => $recent_cars]);

    }

}
