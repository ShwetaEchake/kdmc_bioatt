<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests\Admin\ChangeLeaveRequestStatusRequest;
use App\Http\Requests\Admin\StoreLeaveRequestRequest;
use App\Http\Requests\Admin\UpdateLeaveRequestRequest;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Repositories\LeaveRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LeaveRequestController extends Controller
{

    protected $leaveRepository;
    public function __construct()
    {
        $this->leaveRepository = new LeaveRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pageType = $request->page_type ?? 'full_day';
        $type_const = strtoupper( 'LEAVE_FOR_TYPE_'.$request->page_type );
        
        $leaveRequests = LeaveRequest::with('user.department', 'user.employee.clas', 'leaveType', 'document')
                            ->whereRequestForType( constant("App\Models\LeaveRequest::$type_const") )
                            ->when($pageType == 'full_day',
                                fn ($qr) => $qr->whereNotIn('leave_type_id', ['2','7']) )
                            ->latest()->get();
    
        $leaveTypes = LeaveType::with('leave')
                        ->when($pageType == 'full_day', 
                            fn ($qr) => $qr->whereNotIn('id', ['2','7']) )
                        ->get();

        return view('admin.leave-requests')->with([ 'leaveRequests'=> $leaveRequests, 'pageType'=> $pageType, 'leaveTypes'=> $leaveTypes ]);
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
    public function store(StoreLeaveRequestRequest $request)
    {
        try
        {
            $this->leaveRepository->storeLeaveRequest($request->validated());
            return response()->json(['success'=> 'Leave request added successfully!']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'adding', 'Leave request');
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
    public function edit(LeaveRequest $leave_request)
    {
        return $this->leaveRepository->editLeaveRequest($leave_request);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLeaveRequestRequest $request, LeaveRequest $leave_request)
    {
        try
        {
            $this->leaveRepository->updateLeaveRequest($request->validated(), $leave_request);
            return response()->json(['success'=> 'Leave request updated successfully!']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'updating', 'Leave request');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeaveRequest $leave_request)
    {
        $leave_request->delete();
        return response()->json(['success'=> 'Leave request deleted successfully!']);
    }


    public function changeRequest(ChangeLeaveRequestStatusRequest $request, LeaveRequest $leave_request)
    {
        $this->leaveRepository->changeRequest($request->validated(), $leave_request);
        return response()->json(['success'=> 'Leave request '. ($request->status == 1 ? 'approved' : 'rejected') .' successfully!']);
    }


    public function activeMedicalLeaveRequest()
    {
        $pageType = 'active';
        $leaveRequests = LeaveRequest::with('user', 'leaveType', 'document')
                            ->whereLeaveTypeId( 7 )->whereToDate(null)
                            ->latest()->get();

        $leaveTypes = LeaveType::with('leave')->where('id', 7)->get();

        return view('admin.medical-leave-requests')->with([ 'leaveRequests'=> $leaveRequests, 'pageType'=> $pageType, 'leaveTypes'=> $leaveTypes ]);
    }

    public function completedMedicalLeaveRequest()
    {
        $pageType = 'completed';
        $leaveRequests = LeaveRequest::with('user', 'leaveType', 'document')
                            ->whereLeaveTypeId( 7 )->whereNot('is_approved', '0')
                            ->latest()->get();

        $leaveTypes = LeaveType::with('leave')->get();

        return view('admin.medical-leave-requests')->with([ 'leaveRequests'=> $leaveRequests, 'pageType'=> $pageType, 'leaveTypes'=> $leaveTypes ]);
    }

    public function pendingLeaveRequest(Request $request)
    {        
        $pageType = $request->page_type ?? 'pending';
        $type_const = strtoupper( 'LEAVE_STATUS_IS_'.$request->page_type );

        $leaveRequests = LeaveRequest::with('user.department', 'user.employee.clas', 'leaveType', 'document')
                                    ->whereIsApproved( constant("App\Models\LeaveRequest::$type_const") )
                                    ->whereNot('leave_type_id', '7')
                                    ->orWhere( fn($q) => $q->where('leave_type_id', null)->whereIsApproved(constant("App\Models\LeaveRequest::$type_const")) )
                                    ->latest()->get();

        return view('admin.leave-applications')->with([ 'leaveRequests'=> $leaveRequests, 'pageType'=> $pageType ]);
    }
}
