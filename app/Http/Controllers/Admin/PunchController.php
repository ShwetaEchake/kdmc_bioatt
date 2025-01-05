<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests\Admin\StorePunchRequest;
use App\Models\Device;
use App\Models\Holiday;
use App\Models\Setting;
use App\Models\User;
use App\Repositories\PunchRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PunchController extends Controller
{

    protected $punchRepository;
    public function __construct()
    {
        $this->punchRepository = new PunchRepository();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authUser = Auth::user();
        $settings = Setting::getValues( $authUser->tenant_id )->pluck('value', 'key');

        $employees = User::where('tenant_id', $authUser->tenant_id)
                    ->withWhereHas('employee')
                    ->where('id', '!=', $authUser->id)
                    ->with(['subDepartment', 'punches'])
                    ->when(!$authUser->hasRole(['Admin', 'Super Admin']), 
                        fn ($qr) => $qr->where('department_id', $authUser->department_id) )
                    ->latest()
                    ->get();

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $weekDays = $startOfMonth->diffInDaysFiltered( fn (Carbon $date)=> !$date->isWeekday(), $endOfMonth);

        $holidays = Holiday::whereBetween('date', [$startOfMonth, $endOfMonth])->get();

        $devices = Device::orderByDesc('DeviceId')->get();

        return view('admin.punches')->with(['employees'=> $employees, 'settings'=> $settings, 'weekDays'=>$weekDays, 'holidays'=>$holidays, 'devices'=> $devices]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePunchRequest $request)
    {
        try
        {
            $this->punchRepository->store($request->validated());
            return response()->json(['success'=> 'Attendace created successfully!']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'adding', 'Attendance');
        }
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
}
