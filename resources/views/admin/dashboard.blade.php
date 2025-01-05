<x-admin.admin-layout>
    <x-slot name="title">Core Bio - Dashboard</x-slot>

    @push('styles')
        <style>
            .clockdate-wrapper {
                background-color: #665ed5;
                padding:25px;
                max-width:380px;
                /* width:112%; */
                text-align:center;
                border-radius:5px;
                margin:0 auto;

            }
            #clock{
                background-color:#665ed5;
                font-family: sans-serif;
                font-size:40px;
                text-shadow:0px 0px 1px #fff;
                color:#fff;
            }
            #clock span {
                color:#fff;
                text-shadow:0px 0px 1px #665ed5;
                font-size:30px;
                position:relative;
                top:-5px;
                left:10px;
            }
            #date {
                letter-spacing:3px;
                font-size:14px;
                font-family:arial,sans-serif;
                color:#fff;
            }
        </style>
    @endpush

    <div class="page-body">
        <!-- Container-fluid starts-->
        <div class="container-fluid dashboard-default-sec">

            <div class="row">
                    <div class="row">
                        <div class="col-sm-6 col-xl-3 col-lg-6">
                            <div class="card o-hidden border-0">
                                <div class="bg-card1 b-r-4 card-body">
                                    <div class="media static-top-widget">
                                        <div class="media-body"><span class="m-0">Total Employees</span>
                                            <h4 class="mb-0 counter"> {{ $totalEmployees }} </h4><i class="icon-bg" data-feather="user"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-xl-3 col-lg-6">
                            <div class="card o-hidden border-0">
                                <div class="bg-card2 b-r-4 card-body">
                                    <div class="media static-top-widget">
                                        <div class="media-body"><span class="m-0">Total Department</span>
                                            <h4 class="mb-0 counter"> {{ $totalDepartments }} </h4><i class="icon-bg" data-feather="book"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-xl-3 col-lg-6">
                            <div class="card o-hidden border-0">
                                <div class="bg-card3 b-r-4 card-body">
                                    <div class="media static-top-widget">
                                        <div class="media-body"><span class="m-0">Total Holidays</span>
                                            <h4 class="mb-0 counter"> {{ $totalHolidays }} </h4><i class="icon-bg" data-feather="home"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-xl-3 col-lg-6">
                            <div class="card o-hidden border-0">
                                <div class="bg-card4 b-r-4 card-body">
                                    <div class="media static-top-widget">
                                        <div class="media-body"><span class="m-0">Total Office</span>
                                            <h4 class="mb-0 counter"> {{ $totalWards->count() }} </h4><i class="icon-bg" data-feather="briefcase"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <div class="col-9">


                    <div class="row">
                        {{-- Todays present --}}
                        <div class="col-md-6 col-lg-6 col-xl-6 box-col-6">
                            <div class="card custom-card rounded">
                                <h6 class="card-header rounded bg-primary py-2 px-3 text-center">Today's Present</h6>
                                <div class="card-body px-3">
                                    <div class="row">
                                        <div class="col-6">
                                            @php
                                                $todaysPresentCount = $todayPunchData->where('check_in', '!=', '0000-00-00 00:00:00')->count();
                                                $todaysPresentPercent = $totalEmployees ? ceil(($todaysPresentCount/$totalEmployees)*100) : '0';
                                                $todaysAbsentCount = $totalEmployees-$todaysPresentCount;
                                            @endphp
                                            <label for="">{{ $todaysPresentPercent }}%</label>
                                            <div class="progress">
                                                <div class="progress-bar-animated bg-primary progress-bar-striped" role="progressbar" style="width: {{$todaysPresentPercent}}%" aria-valuenow="{{$todaysPresentPercent}}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <strong style="font-size:22px">{{$todaysPresentCount}} </strong>({{ $todaysPresentPercent }}%) <br>
                                            <strong>Present Count</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer row">
                                    @php
                                        $till10AMCount = $todayPunchData->countBy( fn($item) => Carbon\Carbon::parse($item->check_in)->gte($todaysDate.' 10:00:00') );
                                        $after10AMCount = array_key_exists('1', $till10AMCount->toArray()) ? $till10AMCount['1'] : 0;
                                        $till10AMCount = array_key_exists('0', $till10AMCount->toArray()) ? $till10AMCount['0'] : 0;
                                    @endphp
                                    <div class="col-6 col-sm-6">
                                        <h6>Till 10AM</h6>
                                        <h3><span class="counter" style="font-size:22px">{{ $till10AMCount }}</span><span style="font-size:14px">({{ $totalEmployees ? ceil(($till10AMCount/$totalEmployees)*100) : '0' }}%)</span></h3>
                                    </div>
                                    <div class="col-6 col-sm-6">
                                        <h6>After 10AM</h6>
                                        <h3><span class="counter" style="font-size:22px">{{ $after10AMCount }}</span><span style="font-size:14px">({{ $totalEmployees ? ceil(($after10AMCount/$totalEmployees)*100) : '0' }}%)</span></h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Todays absent --}}
                        <div class="col-md-6 col-lg-6 col-xl-6 box-col-6">
                            <div class="card custom-card rounded">
                                <h6 class="card-header rounded bg-primary py-2 px-3 text-center">Today's Absent</h6>
                                <div class="card-body px-3">
                                    <div class="row">
                                        <div class="col-6">
                                            <label for="">{{ $totalEmployees ? ceil(($todaysAbsentCount/$totalEmployees)*100) : '0' }}%</label>
                                            <div class="progress">
                                                <div class="progress-bar-animated bg-primary progress-bar-striped" role="progressbar" style="width: {{ $totalEmployees ? ceil(($todaysAbsentCount/$totalEmployees)*100) : '0' }}%" aria-valuenow="{{ $totalEmployees ? ceil(($todaysAbsentCount/$totalEmployees)*100) : '0' }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <strong style="font-size:22px">{{ max($todaysAbsentCount, 0) }} </strong>({{ $totalEmployees ? ceil(($todaysAbsentCount/$totalEmployees)*100) : '0' }}%) <br>
                                            <strong>Absent Count</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer row">
                                    @php
                                        $permittedLeaveCount = $todayPunchData->where('check_in', '0000-00-00 00:00:00')->where('punch_by', '2')->count();
                                    @endphp
                                    <div class="col-6 col-sm-6">
                                        <h6>Permitted Leave</h6>
                                        <h3><span class="counter" style="font-size:22px">{{ $permittedLeaveCount }}</span><span style="font-size:14px">({{ $totalEmployees ? ceil( ($permittedLeaveCount/$totalEmployees)*100) : '0' }}%)</span></h3>
                                    </div>
                                    <div class="col-6 col-sm-6">
                                        <h6>Non Permitted Leave</h6>
                                        <h3><span class="counter" style="font-size:22px">{{ max($todaysAbsentCount-$permittedLeaveCount, 0) }}</span><span style="font-size:14px">({{ $totalEmployees ? ceil((($todaysAbsentCount-$permittedLeaveCount)/$totalEmployees)*100) : '0' }}%)</span></h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Leave bifurcation --}}
                        <div class="col-md-12 col-lg-12 col-xl-12 box-col-12">
                            <div class="card custom-card rounded">
                                <h6 class="card-header rounded bg-primary py-2 px-3 text-center">Leave Bifurcation</h6>
                                <div class="card-footer row">
                                    <div class="col-2 col-sm-2">
                                        <h6>CL</h6>
                                        <h3 class="counter">{{ $todayPunchData->where('leave_type_id', '6')->count() }}</h3>
                                    </div>
                                    <div class="col-2 col-sm-2">
                                        <h6>EL</h6>
                                        <h3><span class="counter">{{ $todayPunchData->where('leave_type_id', '5')->count() }}</span></h3>
                                    </div>
                                    <div class="col-2 col-sm-2">
                                        <h6>ML</h6>
                                        <h3><span class="counter">{{ $todayPunchData->where('leave_type_id', '7')->count() }}</span></h3>
                                    </div>
                                    <div class="col-3 col-sm-3">
                                        <h6>Other Leave</h6>
                                        <h3><span class="counter">{{ $todayPunchData->where('leave_type_id', '4')->count() }}</span></h3>
                                    </div>
                                    <div class="col-3 col-sm-3">
                                        <h6>Half Day</h6>
                                        <h3><span class="counter">{{ $todayPunchData->where('leave_type_id', '0')->count() }}</span></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Repeatedly latemark / absent --}}
                    <div class="row">
                        @php
                            $repeatedlyLateMark = $punchData->groupBy('emp_code')->countBy( fn($item) => $item->where('is_latemark', '>', '0')->count() > 1 );
                            $repeatedlyLateMark = array_key_exists('1', $repeatedlyLateMark->toArray()) ? $repeatedlyLateMark['1'] : 0;
                        @endphp

                        <div class="col-md-6 col-lg-6 col-xl-6 box-col-6">
                            <div class="card custom-card rounded">
                                <h6 class="card-header rounded bg-primary py-2 px-3 text-center">Repeatedly Latemark</h6>
                                <div class="card-body px-3">
                                    <div class="row">
                                        <div class="col-6">
                                            <label for="">{{ $totalEmployees ? ceil(($repeatedlyLateMark/$totalEmployees)*100) : '0' }}%</label>
                                            <div class="progress">
                                                <div class="progress-bar-animated bg-primary progress-bar-striped" role="progressbar" style="width: {{ $totalEmployees ? ceil(($repeatedlyLateMark/$totalEmployees)*100) : '0' }}%" aria-valuenow="{{ $totalEmployees ? ceil(($repeatedlyLateMark/$totalEmployees)*100) : '0' }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <strong style="font-size:22px">{{ $repeatedlyLateMark }} </strong>({{ $totalEmployees ? ceil(($repeatedlyLateMark/$totalEmployees)*100) : '0' }}%) <br>
                                            <strong>Repeatedly Late Mark Count</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @php
                            $regularEmployeeCount = $punchData->groupBy('emp_code')->count();
                            $repeatedlyAbsent = $totalEmployees-$regularEmployeeCount;
                        @endphp

                        <div class="col-md-6 col-lg-6 col-xl-6 box-col-6">
                            <div class="card custom-card rounded">
                                <h6 class="card-header rounded bg-primary py-2 px-3  text-center">Repeatedly Absent</h6>
                                <div class="card-body px-3">
                                    <div class="row">
                                        <div class="col-6">
                                            <label for="">{{ $totalEmployees ? ceil(($repeatedlyAbsent/$totalEmployees)*100) : '0' }}%</label>
                                            <div class="progress">
                                                <div class="progress-bar-animated bg-primary progress-bar-striped" role="progressbar" style="width: {{ $totalEmployees ? ceil(($repeatedlyAbsent/$totalEmployees)*100) : '0' }}%" aria-valuenow="{{ $totalEmployees ? ceil(($repeatedlyAbsent/$totalEmployees)*100) : '0' }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <strong style="font-size:22px">{{ max($repeatedlyAbsent, 0) }} </strong>({{ $totalEmployees ? ceil(($repeatedlyAbsent/$totalEmployees)*100) : '0' }}%) <br>
                                            <strong>Repeatedly Absent Count</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


                {{-- Side recent 5 attendance list --}}
                <div class="col-3">

                    <div class="col-12">
                        <div id="clockdate">
                            <div class="clockdate-wrapper">
                                <div id="clock"></div>
                                <div id="date"></div>
                            </div>
                        </div>
                    </div>


                    <h6 class="my-4">Today's Latest 5 Records</h6>
                    @php
                        $latestFives = $todayPunchData->take(5);
                    @endphp
                    @foreach ($latestFives as $latest)
                        <div class="col-12 card rounded latest-update-sec mb-2">
                            <div class="media py-2">
                                <div class="col-7 br-right">
                                    <div class="media-body">
                                        <span>{{ ucwords($latest->user->name) }}</span> <br>
                                        <span>Emp Id => {{ $latest->emp_code }}</span>
                                        <p class="">{{ ucfirst($latest->user->department->name) }}</p>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="media-body">
                                        <span class="text-danger">{{ Carbon\Carbon::parse($latest->check_in)->format('d-m-Y h:i A') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>




                {{-- Shift wise details --}}
                <div class="row">
                    <div class="card rounded">
                        <div class="card-header px-2 py-3">
                            <h6>Shift Wise Details</h6>
                        </div>
                        <div class="row">
                            @foreach ($shiftWiseData as $shift)
                            @php
                                $currentShiftData = $todayPunchData->where('check_in', '>=', $todaysDate.' '.$shift->from_time)->where('check_in', '<=', $todaysDate.' '.$shift->to_time)
                            @endphp
                                <div class="col-md-6 col-lg-6 col-xl-6 box-col-6">
                                    <div class="card custom-card rounded">
                                        <h6 class="card-header rounded bg-primary py-2 px-3"> {{ ucwords($shift->name) }} Employees</h6>
                                        <div class="card-body px-3">
                                            <div class="row">
                                                <div class="col-4">
                                                    <h6 class="mb-0">Total</h6>
                                                    <strong style="font-size:22px">{{ $shift->user_counts }} </strong> <br>
                                                </div>
                                                <div class="col-4 br-right">
                                                    <strong>Present</strong> <br>
                                                    <span style="font-size:22px; display:inline-block;">{{ $currentShiftData->count() }} </span><span style="font-size:14px; display:inline-block;">({{ $shift->user_counts ? ceil(($currentShiftData->count()/$shift->user_counts)*100) : '0' }}%)</span> <br>
                                                </div>
                                                <div class="col-4">
                                                    <strong>Absent</strong> <br>
                                                    <span style="font-size:22px; display:inline-block;">{{ abs( $shift->user_counts-$currentShiftData->count() ) }} </span><span style="font-size:14px; display:inline-block;">({{ $shift->user_counts ? ceil(((($shift->user_counts-$currentShiftData->count() ))/$shift->user_counts)*100) : '0' }}%)</span> <br>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer row">
                                            <div class="col-2 col-sm-2">
                                                <h6>CL</h6>
                                                <h3><span class="counter" style="font-size:22px">{{ $currentShiftData->where('leave_type_id', '6')->count() }}</span></h3>
                                            </div>
                                            <div class="col-2 col-sm-2">
                                                <h6>EL</h6>
                                                <h3><span class="counter" style="font-size:22px">{{ $currentShiftData->where('leave_type_id', '5')->count() }}</span></h3>
                                            </div>
                                            <div class="col-2 col-sm-2">
                                                <h6>ML</h6>
                                                <h3><span class="counter" style="font-size:22px">{{ $currentShiftData->where('leave_type_id', '7')->count() }}</span></h3>
                                            </div>
                                            <div class="col-3 col-sm-3">
                                                <h6>OL</h6>
                                                <h3><span class="counter" style="font-size:22px">{{ $currentShiftData->where('leave_type_id', '4')->count() }}</span></h3>
                                            </div>
                                            <div class="col-3 col-sm-3">
                                                <h6>HDL</h6>
                                                <h3><span class="counter" style="font-size:22px">{{ $currentShiftData->where('leave_type_id', '0')->count() }}</span></h3>
                                            </div>
                                        </div>
                                        <div class="card-footer row">
                                            @php
                                                $beforeTime = $todayPunchData->where('check_in', '>=', Carbon\Carbon::parse($todaysDate.' '.$shift->from_time)->subHour()->toDateTimeString())->where('check_in', '<=', Carbon\Carbon::parse($todaysDate.' '.$shift->from_time)->toDateTimeString() )->count();
                                                $afterTime = $todayPunchData->where('check_in', '<=', Carbon\Carbon::parse($todaysDate.' '.$shift->from_time)->addHour()->toDateTimeString())->where('check_in', '>=', Carbon\Carbon::parse($todaysDate.' '.$shift->from_time)->toDateTimeString() )->count();
                                            @endphp
                                            {{-- {{ dd( Carbon\Carbon::parse($todaysDate.' '.$shift->from_time)->toTimeString() ) }} --}}
                                            <div class="col-6 col-sm-6">
                                                <h6>Before Time</h6>
                                                <h3><span class="counter" style="font-size:22px">{{ $beforeTime }}</span><span style="font-size:14px">( {{ $currentShiftData->count() ? ceil(($beforeTime/$currentShiftData->count())*100) : '0' }}%)</span></h3>
                                            </div>
                                            <div class="col-6 col-sm-6">
                                                <h6>After Time</h6>
                                                <h3><span class="counter" style="font-size:22px">{{ ($afterTime) }}</span><span style="font-size:14px">({{ $currentShiftData->count() ? ceil(($afterTime/$currentShiftData->count())*100) : '0' }}%)</span></h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>



                {{-- Office/Ward wise details --}}
                @if ( $is_admin && !request()->ward )
                    <div class="row">
                        <div class="card rounded">
                            <div class="card-header px-2 py-3">
                                <h6>Office Wise Details</h6>
                            </div>
                            <div class="row">
                                @foreach ($totalWards as $totalWard)
                                    @php
                                        $currentWardData = $todayPunchData->where( fn($item) => $item->user->ward_id == $totalWard->id );
                                    @endphp
                                    {{-- @if ($loop->iteration == 3)
                                        {{ dd($currentWardData) }}
                                    @endif --}}
                                    <div class="col-md-4 col-lg-4 col-xl-4 box-col-4">
                                        <div class="card custom-card rounded">
                                            <h6 class="card-header rounded bg-primary py-2 px-3"> {{ ucwords($totalWard->name) }} Office</h6>
                                            <div class="card-body px-3">
                                                <div class="row">
                                                    <div class="col-4">
                                                        <h6 class="mb-0">Total</h6>
                                                        <strong style="font-size:22px">{{ $totalWard->users_count }} </strong> <br>
                                                    </div>
                                                    <div class="col-4 br-right">
                                                        <strong>Present</strong> <br>
                                                        <span style="font-size:22px; display:inline-block;">{{ $currentWardData->count() }} </span><span style="font-size:14px; display:inline-block;">({{ $totalWard->users_count ? ceil(($currentWardData->count()/$totalWard->users_count)*100) : '0' }}%)</span> <br>
                                                    </div>
                                                    <div class="col-4">
                                                        <strong>Absent</strong> <br>
                                                        <span style="font-size:22px; display:inline-block;">{{ abs( $totalWard->users_count-$currentWardData->count() ) }} </span><span style="font-size:14px; display:inline-block;">({{ $totalWard->users_count ? ceil(((($totalWard->users_count-$currentWardData->count() ))/$totalWard->users_count)*100) : '0' }}%)</span> <br>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer row">
                                                <div class="col-12 col-sm-12">
                                                    <h6>Click <a href="{{ route('dashboard', ['ward'=> $totalWard->id]) }}">here</a> for more details</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif



            </div>

        </div>
        <!-- Container-fluid Ends-->
    </div>

@push('scripts')
    <script >
        $(document).ready(function(){
            if ( window.location.pathname == '/dashboard' )
                startTime()
        });
        function startTime() {
            var today = new Date();
            var hr = today.getHours();
            var min = today.getMinutes();
            var sec = today.getSeconds();
            ap = (hr < 12) ? "<span>AM</span>" : "<span>PM</span>";
            hr = (hr == 0) ? 12 : hr;
            hr = (hr > 12) ? hr - 12 : hr;
            //Add a zero in front of numbers<10
            hr = checkTime(hr);
            min = checkTime(min);
            sec = checkTime(sec);
            document.getElementById("clock").innerHTML = hr + ":" + min + ":" + sec + " " + ap;

            var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            var days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            var curWeekDay = days[today.getDay()];
            var curDay = today.getDate();
            var curMonth = months[today.getMonth()];
            var curYear = today.getFullYear();
            var date = curWeekDay + ", " + curDay + " " + curMonth + " " + curYear;
            document.getElementById("date").innerHTML = date;

            var time = setTimeout(function() {
                startTime()
            }, 500);
        }

        function checkTime(i) {
            if (i < 10) {
                i = "0" + i;
            }
            return i;
        }
    </script>
@endpush

</x-admin.admin-layout>
