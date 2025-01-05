<x-admin.admin-layout>
    <x-slot name="title">{{ auth()->user()->tenant_name }} - Attendance</x-slot>

    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">


                <!-- Add Form Start -->
                <div class="row" id="addContainer" style="display:none;">
                    <div class="col-sm-12">
                        <div class="card">
                            <form class="theme-form" name="addForm" id="addForm">
                                @csrf
                                <div class="card-header pb-0">
                                    <h4>Create Attendance</h4>
                                </div>
                                <div class="card-body pt-0">


                                    <div class="mb-3 row">

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="emp_code">Select Employee <span class="text-danger">*</span></label>
                                            <select class="js-example-basic-single col-sm-12" name="emp_code">
                                                <option value="">--Select Employee--</option>
                                                {{-- @foreach ($empList as $empl)
                                                    <option value="{{ $empl->emp_code }}">{{ $empl->name }}</option>
                                                @endforeach --}}
                                            </select>
                                            <span class="text-danger error-text emp_code_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="device_id">Select Device <span class="text-danger">*</span></label>
                                            <select class="js-example-basic-single col-sm-12" name="device_id">
                                                <option value="">--Select Device--</option>
                                                @foreach ($devices as $dev)
                                                    <option value="{{ $dev->DeviceId }}">{{ $dev->DeviceLocation }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger error-text device_id_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="punch_date">Date <span class="text-danger">*</span> </label>
                                            <input class="form-control" name="punch_date" type="date" min="{{ Carbon\Carbon::yesterday()->format('Y-m-d') }}" max="{{ Carbon\Carbon::tomorrow()->format('Y-m-d') }}" placeholder="Enter Check In" value="10:00:00">
                                            <span class="text-danger error-text punch_date_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="check_in">Check In <span class="text-danger">*</span> </label>
                                            <input class="form-control" name="check_in" type="time" placeholder="Enter Check In" value="10:00:00">
                                            <span class="text-danger error-text check_in_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="check_out">Check Out <span class="text-danger">*</span> </label>
                                            <input class="form-control" name="check_out" type="time" placeholder="Enter Check Out" value="19:00:00">
                                            <span class="text-danger error-text check_out_err"></span>
                                        </div>

                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary" id="addSubmit">Submit</button>
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


                {{-- Edit Form --}}
                <div class="row" id="editContainer" style="display:none;">
                    <div class="col">
                        <form class="form-horizontal form-bordered" method="post" id="editForm">
                            @csrf
                            <section class="card">
                                <header class="card-header pb-0">
                                    <h4 class="card-title">Edit Attendance</h4>
                                </header>

                                <div class="card-body py-2">

                                    <input type="hidden" id="edit_model_id" name="edit_model_id" value="">

                                    <div class="mb-3 row">

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="emp_code">Select Employee <span class="text-danger">*</span></label>
                                            <select class="js-example-basic-single col-sm-12" name="emp_code">
                                                <option value="">--Select Employee--</option>
                                                {{-- @foreach ($empList as $empl)
                                                    <option value="{{ $empl->emp_code }}">{{ $empl->name }}</option>
                                                @endforeach --}}
                                            </select>
                                            <span class="text-danger error-text emp_code_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="device_id">Select Device <span class="text-danger">*</span></label>
                                            <select class="js-example-basic-single col-sm-12" name="device_id">
                                                <option value="">--Select Device--</option>
                                                @foreach ($devices as $dev)
                                                    <option value="{{ $dev->DeviceId }}">{{ $dev->DeviceLocation }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger error-text device_id_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="punch_date">Date <span class="text-danger">*</span> </label>
                                            <input class="form-control" name="punch_date" type="date" min="{{ Carbon\Carbon::yesterday()->format('Y-m-d') }}" max="{{ Carbon\Carbon::tomorrow()->format('Y-m-d') }}" placeholder="Enter Check In" value="10:00:00">
                                            <span class="text-danger error-text punch_date_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="check_in">Check In <span class="text-danger">*</span> </label>
                                            <input class="form-control" name="check_in" type="time" placeholder="Enter Check In" value="10:00:00">
                                            <span class="text-danger error-text check_in_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="check_out">Check Out <span class="text-danger">*</span> </label>
                                            <input class="form-control" name="check_out" type="time" placeholder="Enter Check Out" value="19:00:00">
                                            <span class="text-danger error-text check_out_err"></span>
                                        </div>

                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button class="btn btn-primary" id="editSubmit">Update</button>
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                </div>
                            </section>
                        </form>
                    </div>
                </div>


                <div class="row">
                    <div class="col-sm-6">
                        <h3>Attendance</h3>
                    </div>
                    <div class="col-sm-6">
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid starts-->
        <div class="container-fluid support-ticket">
            <div class="row">

                <div class="col-sm-12">
                    <div class="card">

                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <button id="addToTable" class="btn btn-primary">Manual Attendance <i class="fa fa-plus"></i></button>
                                        <button id="btnCancel" class="btn btn-danger" style="display:none;">Cancel</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="display table-bordered" id="advance-1">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Emp Code</th>
                                            <th>Emp Name</th>
                                            <th>Department</th>
                                            <th>Total Working Days</th>
                                            <th>Late Marks</th>
                                            <th>Half Days</th>
                                            <th>Sat/Sun</th>
                                            <th>Holidays</th>
                                            <th>Leaves</th>
                                            <th>CL</th>
                                            <th>ML</th>
                                            <th>EL</th>
                                            <th>OL</th>
                                            <th>Comp Off</th>
                                            <th>Outpost</th>
                                            <th>Technical</th>
                                            <th>Others</th>
                                            <th>Present Days</th>
                                            <th>Total Present Days</th>
                                            <th>Absent Days</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($employees as $emp)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $emp->emp_code }}</td>
                                                <td>{{ $emp->name }}</td>
                                                <td>{{ $emp->subDepartment?->name }}</td>
                                                <td>{{ Carbon\Carbon::now()->daysInMonth }}</td>
                                                <td>{{ $emp->punches->count() }}</td>
                                                <td>{{ $emp->punches->count() }}</td>
                                                <td>{{ $weekDays }}</td>
                                                <td>{{ $holidays->count() }}</td>
                                                <td>{{ $emp->punches->count() }}</td>
                                                <td>{{ $emp->punches->count() }}</td>
                                                <td>{{ $emp->punches->count() }}</td>
                                                <td>{{ $emp->punches->count() }}</td>
                                                <td>{{ $emp->punches->count() }}</td>
                                                <td>{{ $emp->punches->count() }}</td>
                                                <td>{{ $emp->punches->count() }}</td>
                                                <td>{{ $emp->punches->count() }}</td>
                                                <td>{{ $emp->punches->count() }}</td>
                                                <td>{{ $emp->punches->count() }}</td>
                                                <td>{{ $emp->punches->count() }}</td>
                                                <td>{{ $emp->punches->count() }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>



    {{-- Show More Info Modal --}}
    <div class="modal fade" id="more-info-modal" role="dialog" >
        <div class="modal-dialog" role="document">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Punch Info</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="empMoreInfo">

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
        </div>
    </div>



</x-admin.admin-layout>


<!-- Toggle Status -->
<script>
    $("#advance-1").on("change", ".status", function(e) {
        e.preventDefault();
        var model_id = $(this).attr("data-id");
        var url = "{{ route('users.toggle', ':model_id') }}";

        $.ajax({
            url: url.replace(':model_id', model_id),
            type: 'GET',
            data: {
                '_token': "{{ csrf_token() }}"
            },
            success: function(data, textStatus, jqXHR) {
                if (!data.error && !data.error2) {
                    swal("Success!", data.success, "success");
                } else {
                    if (data.error) {
                        swal("Error!", data.error, "error");
                    } else {
                        swal("Error!", data.error2, "error");
                    }
                }
            },
            error: function(error, jqXHR, textStatus, errorThrown) {
                swal("Error!", "Something went wrong", "error");
            },
        });
    });
</script>


{{-- Add --}}
<script>
    $("#addForm").submit(function(e) {
        e.preventDefault();
        $("#addSubmit").prop('disabled', true);

        var formdata = new FormData(this);
        $.ajax({
            url: '{{ route('punches.store') }}',
            type: 'POST',
            data: formdata,
            contentType: false,
            processData: false,
            success: function(data) {
                $("#addSubmit").prop('disabled', false);
                if (!data.error2)
                    swal("Successful!", data.success, "success")
                    .then((action) => {
                        window.location.href = '{{ route('punches.index') }}';
                    });
                else
                    swal("Error!", data.error2, "error");
            },
            statusCode: {
                422: function(responseObject, textStatus, jqXHR) {
                    $("#addSubmit").prop('disabled', false);
                    resetErrors();
                    printErrMsg(responseObject.responseJSON.errors);
                },
                500: function(responseObject, textStatus, errorThrown) {
                    $("#addSubmit").prop('disabled', false);
                    swal("Error occured!", "Something went wrong please try again", "error");
                }
            }
        });

        function resetErrors() {
            var form = document.getElementById('addForm');
            var data = new FormData(form);
            for (var [key, value] of data) {
                var field = key.replace('[]', '');
                $('.' + key + '_err').text('');
                $("[name='"+field+"']").removeClass('is-invalid');
                $("[name='"+field+"']").addClass('is-valid');
            }
        }

        function printErrMsg(msg) {
            $.each(msg, function(key, value) {
                var field = key.replace('[]', '');
                $('.' + key + '_err').text(value);
                $("[name='"+field+"']").addClass('is-invalid');
                $("[name='"+field+"']").removeClass('is-valid');
            });
        }

    });
</script>


<!-- Edit -->
<script>
    $("#advance-1").on("click", ".edit-element", function(e) {
        e.preventDefault();
        var model_id = $(this).attr("data-id");
        var url = "{{ route('punches.edit', ':model_id') }}";

        $.ajax({
            url: url.replace(':model_id', model_id),
            type: 'GET',
            data: {
                '_token': "{{ csrf_token() }}"
            },
            success: function(data, textStatus, jqXHR) {
                $("#addContainer").slideUp();
                $("#btnCancel").show();
                $("#addToTable").hide();
                $("#editContainer").slideDown();

                if (!data.error) {
                    $("#editForm input[name='edit_model_id']").val(data.user.id);
                    $("#editForm input[name='emp_code']").val(data.user.emp_code);
                    $("#editForm select[name='department_id']").html(data.departmentHtml);
                    $("#editForm select[name='sub_department_id']").html(data.subDepartmentHtml);
                    $("#editForm select[name='ward_id']").html(data.wardHtml);
                    $("#editForm select[name='clas_id']").html(data.clasHtml);
                    $("#editForm select[name='designation_id']").html(data.designationHtml);
                    $("#editForm select[name='shift_id']").html(data.shiftHtml);
                    $("#editForm input[name='name']").val(data.user.name);
                    $("#editForm input[name='email']").val(data.user.email);
                    $("#editForm input[name='mobile']").val(data.user.mobile);
                    $("#editForm input[name='dob']").val(data.user.dob);
                    $("#editForm input[name='doj']").val(data.user.punch.doj);
                    $("#editForm input[name='present_address']").val(data.user.punch.present_address);
                    $("#editForm input[name='permanent_address']").val(data.user.punch.permanent_address);
                    data.user.gender == 'm' ? $("#editForm input[name='gender'][value='m']").prop("checked", true) : $("#editForm input[name='gender'][value='f']").prop("checked", true) ;
                    data.user.punch.is_ot == 'y' ? $("#editForm input[name='is_ot'][value='y']").prop("checked", true) : $("#editForm input[name='is_ot'][value='n']").prop("checked", true) ;
                    data.user.punch.is_divyang == 'y' ? $("#editForm input[name='is_divyang'][value='y']").prop("checked", true) : $("#editForm input[name='is_divyang'][value='n']").prop("checked", true) ;
                } else {
                    swal("Error!", data.error, "error");
                }
            },
            error: function(error, jqXHR, textStatus, errorThrown) {
                swal("Error!", "Some thing went wrong", "error");
            },
        });
    });
</script>


<!-- Update -->
<script>
    $(document).ready(function() {
        $("#editForm").submit(function(e) {
            e.preventDefault();
            $("#editSubmit").prop('disabled', true);
            var formdata = new FormData(this);
            formdata.append('_method', 'PUT');
            var model_id = $('#edit_model_id').val();
            var url = "{{ route('punches.update', ':model_id') }}";
            //
            $.ajax({
                url: url.replace(':model_id', model_id),
                type: 'POST',
                data: formdata,
                contentType: false,
                processData: false,
                success: function(data) {
                    $("#editSubmit").prop('disabled', false);
                    if (!data.error2)
                        swal("Successful!", data.success, "success")
                        .then((action) => {
                            window.location.href = '{{ route('punches.index') }}';
                        });
                    else
                        swal("Error!", data.error2, "error");
                },
                statusCode: {
                    422: function(responseObject, textStatus, jqXHR) {
                        $("#editSubmit").prop('disabled', false);
                        resetErrors();
                        printErrMsg(responseObject.responseJSON.errors);
                    },
                    500: function(responseObject, textStatus, errorThrown) {
                        $("#editSubmit").prop('disabled', false);
                        swal("Error occured!", "Something went wrong please try again", "error");
                    }
                }
            });

            function resetErrors() {
                var form = document.getElementById('editForm');
                var data = new FormData(form);
                for (var [key, value] of data) {
                    var field = key.replace('[]', '');
                    $('.' + field + '_err').text('');
                    $("[name='"+field+"']").removeClass('is-invalid');
                    $("[name='"+field+"']").addClass('is-valid');
                }
            }

            function printErrMsg(msg) {
                $.each(msg, function(key, value) {
                    var field = key.replace('[]', '');
                    $('.' + field + '_err').text(value);
                    $("[name='"+field+"']").addClass('is-invalid');
                });
            }

        });
    });
</script>


<!-- Get Sub departments -->
<script>
    $("select[name='department_id']").change( function(e) {
        e.preventDefault();

        var model_id = $(this).val();
        var url = "{{ route('departments.sub_departments', ':model_id') }}";

        $.ajax({
            url: url.replace(':model_id', model_id),
            type: 'GET',
            data: {
                '_token': "{{ csrf_token() }}"
            },
            success: function(data, textStatus, jqXHR)
            {
                if (!data.error)
                {
                    $("select[name='sub_department_id']").html(data.subDepartmentHtml);
                } else {
                    swal("Error!", data.error, "error");
                }
            },
            error: function(error, jqXHR, textStatus, errorThrown) {
                swal("Error!", "Some thing went wrong", "error");
            },
        });
    });
</script>


<!-- Show Details -->
<script>
    $("#advance-1").on("click", ".emp-more-info", function(e) {
        e.preventDefault();
        var model_id = $(this).attr("data-id");
        var url = "{{ route('punches.show', ':model_id') }}";

        $.ajax({
            url: url.replace(':model_id', model_id),
            type: 'GET',
            data: {
                '_token': "{{ csrf_token() }}"
            },
            success: function(data, textStatus, jqXHR)
            {
                console.log(data);
                if (data.result == 1)
                {
                    $("#more-info-modal").modal('show');
                    $("#empMoreInfo").html(data.html);
                }
                else
                {
                    swal("Error!", "Some thing went wrong", "error");
                }
            },
            error: function(error, jqXHR, textStatus, errorThrown) {
                swal("Error!", "Some thing went wrong", "error");
            },
        });
    });
</script>
