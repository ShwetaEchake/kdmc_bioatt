<x-admin.admin-layout>
    <x-slot name="title">{{ auth()->user()->tenant_name }} - Shift Roster</x-slot>

    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">


                <!-- Add Form -->
                <div class="row" id="addContainer" style="display:none;">
                    <div class="col-sm-12">
                        <div class="card">
                            <form class="theme-form" name="addForm" id="addForm" enctype="multipart/form-data">
                                @csrf

                                <div class="card-body">

                                    <div class="mb-3 row">
                                        <div class="col-md-4">
                                            <a href="{{ route('rosters.sample') }}" class="btn btn-secondary">Download Sample <i class="fa fa-file-excel"></i></a>
                                        </div>
                                    </div>

                                    <div class="mb-3 row mt-5">
                                        <div class="col-md-4">
                                            <label class="col-form-label" for="ward_id">Office <span class="text-danger">*</span> </label>
                                            <select class="js-example-basic-single col-sm-12" id="ward_id" name="ward_id">
                                                <option value="">--Select Office--</option>
                                                @foreach ($wards as $ward)
                                                    <option value="{{ $ward->id }}">{{ $ward->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger error-text ward_id_err"></span>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="col-form-label" for="department_id">Department <span class="text-danger">*</span> </label>
                                            <select class="js-example-basic-single col-sm-12" id="department_id" name="department_id">
                                                <option value="">--Select Department--</option>
                                            </select>
                                            <span class="text-danger error-text department_id_err"></span>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <label class="col-form-label" for="from_date">From Date <span class="text-danger">*</span> </label>
                                            <input class="form-control" id="from_date" name="from_date" required type="date">
                                            <span class="text-danger error-text from_date_err"></span>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="col-form-label" for="to_date">To Date <span class="text-danger">*</span> </label>
                                            <input class="form-control" id="to_date" name="to_date" required type="date">
                                            <span class="text-danger error-text to_date_err"></span>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="col-form-label" for="file">Upload Excel File <span class="text-danger">*</span> </label>
                                            <input class="form-control" id="file" name="file" type="file" required accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                            <span class="text-danger error-text file_err"></span>
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


                <div class="row">
                    <div class="col-sm-12">

                        <h3>Shift Roster</h3>

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
                                        <button id="addToTable" class="btn btn-primary">Add <i class="fa fa-plus"></i></button>
                                        <button id="btnCancel" class="btn btn-danger" style="display:none;">Cancel</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table-bordered" id="advance-1">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Employee Code</th>
                                            <th>Employee Name</th>
                                            <th>Department</th>
                                            <th>Shift</th>
                                            <th>WeekOff</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $user)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $user->emp_code }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->department->name }}</td>
                                                <td>
                                                    <strong> {{ $user->employee->shift->name }} </strong> <br>
                                                    <strong> From </strong> {{ Carbon\Carbon::parse($user->employee->shift->from_time)->format('h:i A') }} <br>
                                                    <strong> To </strong> {{ Carbon\Carbon::parse($user->employee->shift->to_time)->format('h:i A') }}
                                                </td>
                                                <td>
                                                    <span class="badge round-badge-success"> {{ ucfirst($user->weekoff?->weekoff_1) }} </span> <br>
                                                    <span class="badge round-badge-success"> {{ ucfirst($user->weekoff?->weekoff_2) }} </span>
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
        <!-- Container-fluid Ends -->
    </div>


</x-admin.admin-layout>

{{-- Add --}}
<script>
    $("#addForm").submit(function(e) {
        e.preventDefault();
        $("#addSubmit").prop('disabled', true);

        var formdata = new FormData(this);
        $.ajax({
            url: '{{ route('rosters.import') }}',
            type: 'POST',
            data: formdata,
            contentType: false,
            processData: false,
            success: function(data) {
                $("#addSubmit").prop('disabled', false);
                if (!data.error2)
                    swal("Successful!", data.success, "success")
                    .then((action) => {
                        window.location.reload();
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
                $('.' + key + '_err').text('');
                $('#' + key).removeClass('is-invalid');
                $('#' + key).addClass('is-valid');
            }
        }

        function printErrMsg(msg) {
            $.each(msg, function(key, value) {
                $('.' + key + '_err').text(value);
                $('#' + key).addClass('is-invalid');
                $('#' + key).removeClass('is-valid');
            });
        }

    });
</script>




<!-- Get Ward wise departments -->
<script>
    $("select[name='ward_id']").change( function(e) {
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