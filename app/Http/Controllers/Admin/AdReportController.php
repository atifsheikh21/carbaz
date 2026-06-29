<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdReport;
use App\Models\CarPart;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Car\Entities\Car;

class AdReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(Request $request)
    {
        $reports = AdReport::with('user')->latest()->paginate(20);

        $reports->getCollection()->transform(function (AdReport $report) {
            $url = null;
            try {
                if ($report->reportable_type === Car::class) {
                    $car = Car::select('id', 'slug')->find($report->reportable_id);
                    if ($car && !empty($car->slug)) {
                        $url = route('listing', $car->slug);
                    }
                }

                if ($report->reportable_type === CarPart::class) {
                    $part = CarPart::select('id', 'slug')->find($report->reportable_id);
                    if ($part && !empty($part->slug)) {
                        $url = route('car-part', $part->slug);
                    }
                }
            } catch (\Throwable $e) {
                $url = null;
            }

            $report->ad_url = $url;

            return $report;
        });

        $title = 'Ad Reports';

        return view('admin.ad_reports.index', compact('reports', 'title'));
    }
}
