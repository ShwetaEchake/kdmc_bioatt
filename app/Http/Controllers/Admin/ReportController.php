<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clas;
use App\Models\Department;
use App\Models\Holiday;
use App\Models\LeaveType;
use App\Models\Setting;
use App\Models\User;
use App\Models\Ward;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $authUser = Auth::user();
        $departments = Department::orderBy('name')->get();
        $wards = Ward::orderBy('name')->get();
        $class = Clas::orderBy('name')->get();
        $empList = [];
        $weekDays = '';
        $leaveTypes = LeaveType::whereNot('id', 7)->get();
        $totalDays = '';
        $holidays = 0;

        $settings = Setting::getValues( $authUser->tenant_id )->pluck('value', 'key');
        $fromDate = Carbon::parse($request->year ?? date('Y').'-'.($request->month ?? 1).'-'.$settings['PAYROLL_DATE']);
        $toDate = clone($fromDate);
        $fromDate = (string) $fromDate->subMonth()->toDateString();
        $toDate = (string) $toDate->subDay()->toDateString();

        if( $request->month )
        {
            $departmentId = $authUser->hasRole(['Admin', 'Super Admin']) ? $request->department : $authUser->department_id;
            $empList = User::whereNot('id', $authUser->id)->where('department_id', $departmentId)
                    ->with(['department', 'leaveRequests' => fn($q) => $q->whereBetween('created_at', [$fromDate, $toDate])->where('is_approved', '1') ])
                    ->withWhereHas('punches', fn($q) => $q->whereBetween('punch_date', [$fromDate, $toDate] ) );

            if($request->ward)
                $empList = $empList->withWhereHas( 'employee', function($q) use ($request){
                        $q->where('ward_id', $request->ward)->with('shift');
                        if($request->class)
                            $q->where('clas_id', $request->class)->with('shift');
                    })->get();
            else
                $empList = $empList->with('employee.shift')->get();

            $weekDays = Carbon::parse($fromDate)->diffInDaysFiltered( fn (Carbon $date)=> $date->isWeekend(), $toDate);

            $holidays = Holiday::whereBetween('date', [$fromDate, $toDate])->get();
            $totalDays = Carbon::parse($fromDate)->diffInDays($toDate)+1;
        }
        return view('admin.reports.month-wise-report')->with(['empList'=> $empList, 'weekDays'=> $weekDays, 'holidays'=> $holidays, 'settings'=> $settings, 'leaveTypes'=> $leaveTypes, 'departments'=> $departments, 'wards'=> $wards, 'class'=> $class, 'fromDate'=> $fromDate, 'toDate'=> $toDate, 'totalDays'=> $totalDays]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function musterReport(Request $request)
    {
        $authUser = Auth::user();
        $departments = Department::whereDepartmentId(null)->latest()->get();
        $wards = Ward::latest()->get();
        $class = Clas::latest()->get();
        $empList = [];
        $weekDays = '';
        $leaveTypes = LeaveType::whereNot('id', 7)->get();
        $totalDays = '';
        $holidays = 0;

        $settings = Setting::getValues( $authUser->tenant_id )->pluck('value', 'key');
        $fromDate = Carbon::parse($request->year ?? date('Y').'-'.($request->month ?? 1).'-'.$settings['PAYROLL_DATE']);
        $toDate = clone($fromDate);
        $fromDate = (string) $fromDate->subMonth()->toDateString();
        $toDate = (string) $toDate->subDay()->toDateString();

        if( $request->month )
        {
            $empList = User::whereNot('id', $authUser->id)
                    ->with([ 'leaveRequests' => fn($q) => $q->whereBetween('created_at', [$fromDate, $toDate])->where('is_approved', '1') ])
                    ->withWhereHas('punches', fn($q) => $q->whereBetween('punch_date', [$fromDate, $toDate] ) );

            if($request->ward)
                $empList = $empList->withWhereHas( 'employee', function($q) use ($request){
                        $q->where('ward_id', $request->ward)->with('shift');
                        if($request->class)
                            $q->where('clas_id', $request->class)->with('shift');
                    });
            else
                $empList = $empList->with('employee.shift');

            if( $authUser->hasRole(['Admin', 'Super Admin']) )
                $empList = $empList->where('department_id', $request->department)->latest()->get();
            else
                $empList = $empList->where('department_id', $authUser->department_id)->latest()->get();

            $weekDays = Carbon::parse($fromDate)->diffInDaysFiltered( fn (Carbon $date)=> $date->isWeekend(), $toDate);

            $holidays = Holiday::whereBetween('date', [$fromDate, $toDate])->get();
            $totalDays = Carbon::parse($fromDate)->diffInDays($toDate)+1;
        }
        return view('admin.reports.muster-report')->with(['empList'=> $empList, 'weekDays'=> $weekDays, 'holidays'=> $holidays, 'settings'=> $settings, 'leaveTypes'=> $leaveTypes, 'departments'=> $departments, 'wards'=> $wards, 'class'=> $class, 'fromDate'=> $fromDate, 'toDate'=> $toDate, 'totalDays'=> $totalDays]);
    }

    public function monthWiseDate(Request $request)
    {
        $settings = Setting::getValues( auth()->user()->tenant_id )->pluck('value', 'key');
        $fromDate = Carbon::parse($request->year ?? date('Y').'-'.($request->month ?? 1).'-'.$settings['PAYROLL_DATE']);
        $toDate = clone($fromDate);
        $fromDate = (string) $fromDate->subMonth()->toDateString();
        $toDate = (string) $toDate->subDay()->toDateString();

        return response()->json([
            'success'=> true,
            'fromDate'=> $fromDate,
            'toDate'=> $toDate,
        ]);
    }
}
