<?php

namespace App\Http\Controllers;

use App\Models\AdReport;
use App\Models\HiddenAd;
use App\Models\Admin;
use App\Notifications\AdReported;
use App\Helpers\MailHelper;
use App\Models\CarPart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Car\Entities\Car;

class AdReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function storeCar(Request $request, Car $car): RedirectResponse
    {
        $request->validate([
            'reason' => ['required', 'string', 'max:255'],
            'details' => ['nullable', 'string', 'max:5000'],
        ]);

        $user = Auth::guard('web')->user();

        $report = DB::transaction(function () use ($request, $user, $car) {
            $report = AdReport::create([
                'user_id' => $user->id,
                'reportable_type' => Car::class,
                'reportable_id' => $car->id,
                'reason' => $request->reason,
                'details' => $request->details,
                'reporter_ip' => (string) $request->ip(),
                'status' => 'new',
            ]);

            HiddenAd::firstOrCreate([
                'user_id' => $user->id,
                'hideable_type' => Car::class,
                'hideable_id' => $car->id,
            ], [
                'ad_report_id' => $report->id,
            ]);

            return $report;
        });

        $adUrl = route('listing', $car->slug);

        try {
            MailHelper::setMailConfig();

            $admins = Admin::query()->where('status', 'active')->get();
            foreach ($admins as $admin) {
                try {
                    $admin->notify(new AdReported($report, $adUrl));
                } catch (\Throwable $e) {
                }
            }
        } catch (\Throwable $e) {
        }

        $notification = 'Thanks. Your report has been submitted.';
        $notification = ['messege' => $notification, 'alert-type' => 'success'];

        return redirect()->route('listings')->with($notification);
    }

    public function storeCarPart(Request $request, CarPart $carPart): RedirectResponse
    {
        $request->validate([
            'reason' => ['required', 'string', 'max:255'],
            'details' => ['nullable', 'string', 'max:5000'],
        ]);

        $user = Auth::guard('web')->user();

        $report = DB::transaction(function () use ($request, $user, $carPart) {
            $report = AdReport::create([
                'user_id' => $user->id,
                'reportable_type' => CarPart::class,
                'reportable_id' => $carPart->id,
                'reason' => $request->reason,
                'details' => $request->details,
                'reporter_ip' => (string) $request->ip(),
                'status' => 'new',
            ]);

            HiddenAd::firstOrCreate([
                'user_id' => $user->id,
                'hideable_type' => CarPart::class,
                'hideable_id' => $carPart->id,
            ], [
                'ad_report_id' => $report->id,
            ]);

            return $report;
        });

        $adUrl = route('car-part', $carPart->slug);

        try {
            MailHelper::setMailConfig();

            $admins = Admin::query()->where('status', 'active')->get();
            foreach ($admins as $admin) {
                try {
                    $admin->notify(new AdReported($report, $adUrl));
                } catch (\Throwable $e) {
                }
            }
        } catch (\Throwable $e) {
        }

        $notification = 'Thanks. Your report has been submitted.';
        $notification = ['messege' => $notification, 'alert-type' => 'success'];

        return redirect()->route('car-parts')->with($notification);
    }
}
