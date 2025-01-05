<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests\Admin\StoreEmployeeRequest;
use App\Http\Requests\Admin\UpdateEmployeeRequest;
use App\Models\Clas;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Device;
use App\Models\Shift;
use App\Models\User;
use App\Models\Ward;
use App\Repositories\EmployeeRepository;
use Exception;
use Illuminate\Support\Facades\Auth;


class EmployeeController extends Controller
{

    protected $employeeRepository;
    public function __construct()
    {
        $this->employeeRepository = new EmployeeRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authUser = Auth::user();
        $employees = User::withWhereHas('employee', fn ($q) => $q->with('designation', 'ward', 'clas', 'shift'))
                            ->with(['department', 'subDepartment'])
                            ->whereNot('id', $authUser->id);

        if( $authUser->hasRole(['Admin', 'Super Admin']) )
            $employees = $employees->latest()->get();
        else
            $employees = $employees->where('department_id', $authUser->department_id)->get();

        return view('admin.employees')->with(['employees'=> $employees]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $wards = Ward::latest()->get();
        $designations = Designation::latest()->get();
        $clas = Clas::latest()->get();
        $shifts = Shift::latest()->get();
        $devices = Device::orderByDesc('DeviceId')->get();


        return view('admin.add-employees')->with(['wards'=> $wards, 'designations'=> $designations, 'clas'=> $clas, 'shifts'=> $shifts, 'devices'=>$devices]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeRequest $request)
    {
        try
        {
            $this->employeeRepository->store($request->validated());
            return response()->json(['success'=> 'Employee created successfully!']);
        }
        catch(Exception $e)
        {
            return $this->respondWithAjax($e, 'adding', 'Employee');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $employee)
    {
        return $this->employeeRepository->showEmployee($employee);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $employee)
    {
        return $this->employeeRepository->editEmployee($employee);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeRequest $request, User $employee)
    {
        try
        {
            $this->employeeRepository->updateEmployee($request->validated(), $employee);
            return response()->json(['success'=> 'Employee updated successfully!']);
        }
        catch(Exception $e)
        {
            return $this->respondWithAjax($e, 'updating', 'Employee');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function fetchInfo(User $employee)
    {
        if( auth()->user()->department_id != $employee->department_id )
            return response()->json(['error2'=> 'Employee does not belongs to your department']);

        return $employee->load(['employee.ward', 'employee.clas', 'department']);
    }
}
