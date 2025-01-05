<?php

namespace App\Console\Commands;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\Punch;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MedicalLeaveEntryToPunches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'punches:medical-leave-entry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will add blank record of absent leave in punches table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $medicalLeaveRequests = LeaveRequest::with('user')->where(['leave_type_id'=> 7, 'to_date'=> null, 'is_approved'=> '1'])->get();

        foreach($medicalLeaveRequests as $mlRequest)
        {
            $hasPunchedToday = DB::table('punches')->where(['emp_code'=> $mlRequest->user->emp_code, 'punch_date'=> Carbon::today()->toDateString()])->first();
            
            if($hasPunchedToday)
            {
                $mlRequest->to_date = Carbon::today()->toDateString();
                $mlRequest->save();
                continue;
            }

            Punch::create([
                'emp_code'=> $mlRequest->user->emp_code,
                'device_id'=> '0',
                'check_in'=> '0000-00-00 00-00-00',
                'check_out'=> '0000-00-00 00-00-00',
                'punch_date'=> Carbon::today()->toDateString(),
                'duration'=> '0',
                'is_latemark'=> '0',
                'is_latemark_updated'=> '1',
                'punch_by'=> '1',
                'type'=> '1',
                'is_paid'=> LeaveType::IS_MEDICAL_LEAVE_PAID,
            ]);
        }

        $this->info('Medical leave entry updated successfully!');
    }
}
