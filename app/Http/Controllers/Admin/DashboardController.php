<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\Punch;
use App\Models\Shift;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ward;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $authUser = auth()->user();
        $is_admin = $authUser->hasRole(['Admin', 'Super Admin']) ? true : false;
        $ward = $request->ward;
        
        $totalEmployees = Employee::when(!$is_admin, fn($qr) => $qr->whereRelation('user', 'department', $authUser->department_id) )
                                    ->when($ward, fn($qr) => $qr->where('ward_id', $ward) )
                                    ->count();

        $totalDepartments = Department::whereDepartmentId(null)->when($ward, fn($qr) => $qr->where('ward_id', $ward) )->count();
        $totalHolidays = Holiday::whereYear('year', date('Y'))->count();
        $totalWards = Ward::withCount('users')->get();

        $todaysDate = '2023-07-05'; //Carbon::now()->toDateString();
        $backDate = Carbon::parse('2023-07-05')->subDay()->toDateString();

        $punchData = Punch::whereIn('punch_date', [$todaysDate, $backDate])
                            ->select('id', 'emp_code', 'check_in', 'check_out', 'duration', 'punch_date', 'is_latemark', 'is_latemark_updated', 'punch_by', 'type', 'leave_type_id')
                            ->withWhereHas('user', fn($q) => $q->with('department')
                                                    ->when(!$is_admin, fn($qr) => $qr->where('department_id', $authUser->department_id) )
                                                    ->when($is_admin && $ward, fn($qr) => $qr->where('ward_id', $ward))
                            )
                            ->latest()->get();

        $todayPunchData = $punchData->where('punch_date', '>=', Carbon::parse($todaysDate)->toDateString());

        $shiftWiseData = Shift::select('id', 'name', 'from_time', 'to_time')
                                ->selectSub( fn($q) => $q->selectRaw('count(id)')->from('app_users')->whereRaw('in_time >= shifts.from_time AND in_time <= shifts.to_time'), 'user_counts')
                                ->get();

                    
        return view('admin.dashboard')->with([
                        'is_admin' => $is_admin,
                        'totalEmployees' => $totalEmployees,
                        'totalDepartments'=> $totalDepartments,
                        'totalHolidays'=> $totalHolidays,
                        'totalWards'=> $totalWards,
                        'todaysDate'=> $todaysDate,
                        'backDate'=> $backDate,
                        'punchData'=> $punchData,
                        'todayPunchData'=> $todayPunchData,
                        'shiftWiseData'=> $shiftWiseData,
                    ]);
    }

    public function allUsers(Request $request)
    {
        $date = $request->input('date');
        $location = $request->input('location');

        $users = User::when($date, fn($q) => $q->withCount(['transactions'=> fn($qr) => $qr->where('date', '=', date('Y-m-d', strtotime($date) )) ]) )
                        ->when($location, fn($q) => $q->withCount(['transactions'=> fn($qr) => $qr->where( 'location', '=', $location ) ]) )
                        ->when(!$date && !$location, fn($q) => $q->withCount('transactions') )
                        ->get();

        return response()->json($users);
    }
}
