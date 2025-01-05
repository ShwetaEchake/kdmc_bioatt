<?php

namespace App\Console\Commands;

use App\Models\Punch;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SetLatemarkInPunches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'punches:set-latemark';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will update employee late mark based on their shift time and grace period allowed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $settings = Setting::getValues( 1 )->pluck('value', 'key');
        $defaultShift = collect( config('default_data.shift_time') );
        
        Punch::with('user.employee', 'user.latestWeekoff')
                    ->where('is_latemark_updated', '0')->orderBy('id')
                    ->chunk(200, function($unUpdatedLatemarkPunches) use ($settings, $defaultShift){

                        foreach($unUpdatedLatemarkPunches as $punches)
                        {
                            if(!$punches->user)
                                continue;

                            $allotedTime = '';
                            if($punches->user->latestWeekoff)
                                $allotedTime = Carbon::parse($punches->user->latestWeekoff->end_of_week)->gt($punches->punch_date) ? $punches->user->latestWeekoff->shift_in_time : $punches->user->in_time;
                            else
                                $allotedTime = $punches->user->in_time ?? $defaultShift['from_time'];
                            
                            $allotedTime = $allotedTime ?? $defaultShift['from_time'];
                            
                            if(Carbon::parse($punches->check_in)
                                    ->gt( Carbon::parse( $allotedTime )
                                            ->addMinutes( 
                                                $punches->user->employee->is_divyang == 'y' ? $settings['LATE_MARK_TIMING_DIVYANG'] : $settings['LATE_MARK_TIMING'] 
                                            )
                            ))
                            {
                                $punches->is_latemark = '1';
                            }
                            $punches->is_latemark_updated = '1';
                            $punches->save();
                        }

                    });


        $this->info('Latemark is updated on all employees successfully!');
    }
}
