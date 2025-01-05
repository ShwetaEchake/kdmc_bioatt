<x-admin.admin-layout>
    <x-slot name="title">{{ auth()->user()->tenant_name }} - Employees</x-slot>

    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">


                {{-- Edit Form --}}
                <div class="row" id="editContainer" style="display:none;">
                    <div class="col">
                        <form class="form-horizontal form-bordered" method="post" id="editForm">
                            @csrf
                            <section class="card">
                                <header class="card-header pb-0">
                                    <h4 class="card-title">Edit Employee</h4>
                                </header>

                                <div class="card-body py-2">

                                    <input type="hidden" id="edit_model_id" name="edit_model_id" value="">

                                    <div class="mb-3 row">
                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="emp_code">Employee Code <span class="text-danger">*</span> </label>
                                            <input class="form-control" name="emp_code" type="text" placeholder="Enter Employee Code">
                                            <span class="text-danger error-text emp_code_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="name">Employee Name <span class="text-danger">*</span></label>
                                            <input class="form-control" name="name" type="text" placeholder="Enter Employee Name">
                                            <span class="text-danger error-text name_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="email">Employee Email </label>
                                            <input class="form-control" name="email" type="email" placeholder="Enter Employee Email">
                                            <span class="text-danger error-text email_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="mobile">Employee Mobile </label>
                                            <input class="form-control" name="mobile" type="number" placeholder="Enter Employee Mobile">
                                            <span class="text-danger error-text mobile_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="permanent_address">Permanent Address </label>
                                            <input class="form-control" name="permanent_address" type="text" placeholder="Enter Permanent Address">
                                            <span class="text-danger error-text permanent_address_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="present_address">Present Address </label>
                                            <input class="form-control" name="present_address" type="text" placeholder="Enter Present Address">
                                            <span class="text-danger error-text present_address_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="dob">Date of Birth </label>
                                            <input class="form-control" id="dob" name="dob" type="date" max="{{ Carbon\Carbon::now()->format('Y-m-d') }}" onclick="this.showPicker()" placeholder="Enter Date of Birth">
                                            <span class="text-danger error-text dob_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="doj">Date of Joining </label>
                                            <input class="form-control" id="doj" name="doj" type="date" max="{{ Carbon\Carbon::now()->format('Y-m-d') }}" onclick="this.showPicker()" placeholder="Enter Date of Joining">
                                            <span class="text-danger error-text doj_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="gender">Gender <span class="text-danger">*</span></label>
                                            <div class="col">
                                                <label class="me-3" for="radioMale">
                                                    <input class="radio_animated" id="radioMale" type="radio" name="gender" checked="" value="m">Male
                                                </label>
                                                <label class="me-3" for="radioFemale">
                                                    <input class="radio_animated" id="radioFemale" type="radio" name="gender" value="f">Female
                                                </label>
                                            </div>
                                            <span class="text-danger error-text gender_err"></span>
                                        </div>


                                        
                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" >Select Office <span class="text-danger">*</span></label>
                                            <select class="js-example-basic-single col-sm-12" name="ward_id">
                                                <option value="">--Select Office--</option>
                                            </select>
                                            <span class="text-danger error-text ward_id_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="department_id">Select Department <span class="text-danger">*</span></label>
                                            <select class="js-example-basic-single col-sm-12" name="department_id">
                                                <option value="">--Select Department--</option>
                                            </select>
                                            <span class="text-danger error-text department_id_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" >Select Sub Department </label>
                                            <select class="js-example-basic-single col-sm-12" name="sub_department_id">
                                                <option value="">--Select Sub Department--</option>
                                            </select>
                                            <span class="text-danger error-text sub_department_id_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" >Select Machine <span class="text-danger">*</span></label>
                                            <select class="js-example-basic-single col-sm-12" name="device_id">
                                                <option value="">--Select Machine--</option>
                                            </select>
                                            <span class="text-danger error-text device_id_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" >Select Class <span class="text-danger">*</span></label>
                                            <select class="js-example-basic-single col-sm-12" name="clas_id">
                                                <option value="">--Select Class--</option>
                                            </select>
                                            <span class="text-danger error-text clas_id_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" >Select Designation </label>
                                            <select class="js-example-basic-single col-sm-12" name="designation_id">
                                                <option value="">--Select Designation--</option>
                                            </select>
                                            <span class="text-danger error-text designation_id_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" >Select Shift <span class="text-danger">*</span></label>
                                            <select class="js-example-basic-single col-sm-12" name="shift_id">
                                                <option value="">--Select Shift--</option>
                                            </select>
                                            <span class="text-danger error-text shift_id_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" >Choose In Time </label>
                                            <input type="time" class="form-control" name="in_time" value="10:00:00" onclick="this.showPicker()">
                                            <span class="text-danger error-text in_time_err"></span>
                                        </div>
                                        
                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="is_ot">Is OT Allow ? <span class="text-danger">*</span></label>
                                            <div class="col">
                                                <label class="me-3" for="radio_ot_yes">
                                                    <input class="radio_animated" id="radio_ot_yes" type="radio" name="is_ot" checked="" value="y">Yes
                                                </label>
                                                <label class="me-3" for="radio_ot_yes">
                                                    <input class="radio_animated" id="radio_ot_yes" type="radio" name="is_ot" value="n">No
                                                </label>
                                            </div>
                                            <span class="text-danger error-text is_ot_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="is_divyang">Is Employee Divyang ? <span class="text-danger">*</span></label>
                                            <div class="col">
                                                <label class="me-3" for="radio_divyang_yes">
                                                    <input class="radio_animated" id="radio_divyang_yes" type="radio" name="is_divyang" value="y">Yes
                                                </label>
                                                <label class="me-3" for="radio_divyang_no">
                                                    <input class="radio_animated" id="radio_divyang_no" type="radio" name="is_divyang" checked="" value="n">No
                                                </label>
                                            </div>
                                            <span class="text-danger error-text is_ot_err"></span>
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
                        <h3>Employees</h3>
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
                                        {{-- <button id="addToTable" class="btn btn-primary">Add <i class="fa fa-plus"></i></button> --}}
                                        <button id="btnCancel" class="btn btn-danger" style="display:none;">Cancel</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="display table-bordered" id="advance-1">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th style="min-width: 150px;">Employee</th>
                                            <th style="min-width: 180px;">Department</th>
                                            <th>Mobile</th>
                                            <th style="min-width: 200px;">Office</th>
                                            <th>Details</th>
                                            <th>Status</th>
                                            <th>Registered On</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($employees as $emp)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <strong>Emp Id : </strong> {{ $emp->emp_code }} <br>
                                                    <strong>Name : </strong> {{ $emp->name }} <br>
                                                </td>
                                                <td>
                                                    <strong>Dep : </strong> {{ $emp->department?->name }} <br>
                                                    <strong>Sub Dep : </strong> {{ $emp->subDepartment?->name }} <br>
                                                </td>
                                                <td>{{ $emp->mobile }}</td>
                                                <td>
                                                    <strong>Office : </strong> {{ $emp->employee->ward->name }} <br>
                                                    <strong>Class : </strong> {{ $emp->employee->clas->name }} <br>
                                                    <strong>Desig : </strong> {{ $emp->employee->designation?->name }} <br>
                                                </td>
                                                <td>
                                                    <button class="emp-more-info btn btn-primary px-2 py-1" title="More info" data-id="{{ $emp->id }}"><i data-feather="info"></i></button>
                                                </td>
                                                <td>
                                                    <div class="media-body text-end icon-state">
                                                        <label class="switch">
                                                            <input type="checkbox" class="status" data-id="{{ $emp->id }}" {{ $emp->active_status == '1' ? 'checked' : '' }}><span class="switch-state"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($emp->created_at)->format('d M, y h:i:s') }}
                                                </td>
                                                <td>
                                                    <button class="edit-element btn btn-primary px-2 py-1" title="Edit Employee" data-id="{{ $emp->id }}"><i data-feather="edit"></i></button>
                                                    {{-- <button class="btn btn-primary change-password px-2 py-1" title="Change Password" data-id="{{ $emp->id }}"><i data-feather="lock"></i></button> --}}
                                                </td>
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
                        <h5 class="modal-title">Employee Info</h5>
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


<!-- Edit -->
<script>
    $("#advance-1").on("click", ".edit-element", function(e) {
        e.preventDefault();
        var model_id = $(this).attr("data-id");
        var url = "{{ route('employees.edit', ':model_id') }}";

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
                    $("#editForm select[name='device_id']").html(data.deviceHtml);
                    $("#editForm select[name='clas_id']").html(data.clasHtml);
                    $("#editForm select[name='designation_id']").html(data.designationHtml);
                    $("#editForm select[name='shift_id']").html(data.shiftHtml);
                    $("#editForm input[name='in_time']").html(data.user.in_time);
                    $("#editForm input[name='name']").val(data.user.name);
                    $("#editForm input[name='email']").val(data.user.email);
                    $("#editForm input[name='mobile']").val(data.user.mobile);
                    $("#editForm input[name='dob']").val(data.user.dob);
                    $("#editForm input[name='doj']").val(data.user.employee.doj);
                    $("#editForm input[name='present_address']").val(data.user.employee.present_address);
                    $("#editForm input[name='permanent_address']").val(data.user.employee.permanent_address);
                    data.user.gender == 'm' ? $("#editForm input[name='gender'][value='m']").prop("checked", true) : $("#editForm input[name='gender'][value='f']").prop("checked", true) ;
                    data.user.employee.is_ot == 'y' ? $("#editForm input[name='is_ot'][value='y']").prop("checked", true) : $("#editForm input[name='is_ot'][value='n']").prop("checked", true) ;
                    data.user.employee.is_divyang == 'y' ? $("#editForm input[name='is_divyang'][value='y']").prop("checked", true) : $("#editForm input[name='is_divyang'][value='n']").prop("checked", true) ;
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
            var url = "{{ route('employees.update', ':model_id') }}";
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
                            window.location.href = '{{ route('employees.index') }}';
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
        var url = "{{ route('employees.show', ':model_id') }}";

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

<!-- Get Ward wise departments -->
<script>
    $("#addForm select[name='ward_id']").change( function(e) {
        e.preventDefault();

        var model_id = $(this).val();
        var url = "{{ route('wards.departments', ':model_id') }}";

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
                    $("select[name='department_id']").html(data.departmentHtml);
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