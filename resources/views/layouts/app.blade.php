<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <title>Task Management</title>
    <style>
 
        .task-item {
            display: table;
            width: 100%;
        }
        .task-item > div {
            display: table-cell;
            vertical-align: middle;
            padding: 10px;
            border: 1px solid #dee2e6;
        }
        .task-item img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
        }
    </style>
   
</head>
<body>
    <div class="container mt-5">
        @yield('content')
    </div>

    
     <script>
        $(document).ready(function() {
           
            $('#addTaskButton').click(function() {
                var task = $('#newTask').val();
                var currentDate = new Date();
                var taskDateTime = currentDate.toISOString().slice(0, 19).replace('T', ' ');

                $.ajax({
                    url: '{{ route("task.add") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        task_detail: task,
                        task_date_time: taskDateTime
                    },
                    success: function(response) {
                
                    $('#taskError').text(''); 
                    $('.list-group').empty();

                    response.data.forEach(function(task) {
                        // console.log(response);
                    var newTask = `
                        <li class="list-group-item task-item" id="task_${task.id}">
                           
                                <div>
                                    <input type="checkbox" id="chk_id_${task.id}" onclick="handleCheckboxClick(this)" data-id="${task.id}">
                                </div>
                                <div>
                                    <span>${task.task_detail}</span>
                                </div>
                                <div>
                                    <small class="text-muted">${task.task_date_time}</small>
                                </div>
                                <div>
                                    <img src=""" alt="Profile Picture">
                                </div>
                                <div>
                                     <button class="btn btn-danger btn-sm" onclick="confirmTaskDelete(${task.id})"><i class="fas fa-trash-alt"></i></button>
                                </div>
                            
                        </li>`;
                            $('.list-group').append(newTask);
                    });
                       
                    },
                     error: function(xhr, status, error) {
                        var errorMessage = xhr.responseJSON ? xhr.responseJSON.data : 'An error occurred';
                        $('#taskError').text(errorMessage); 
                    }
                });
            });

        });

    

                function handleCheckboxClick(checkbox) 
                {
                        var taskId = $(checkbox).data('id');
                        if (checkbox.checked) {
                            markTaskComplete(taskId);
                        }
                } 


              //task status update function
                function markTaskComplete(id)
                {
                            $.ajax({
                                        url: '{{ route("task.complete") }}',
                                        type: 'POST',
                                        data: {
                                            _token: '{{ csrf_token() }}',
                                            task_id:id
                                        },
                                        success: function(response) {
                                        
                                            if (response.success) {
                                                $('#task_' + id).remove(); 
                                            } else {
                                                alert('Error: ' + response.message); 
                                            }
                                        },
                                        error: function(xhr, status, error) {
                                        // var errorMessage = xhr.responseJSON ? xhr.responseJSON.message : 'An error occurred';
                                            alert('AJAX Error: ' + errorMessage); 
                                        }
                                });
                }


                function getAllTasks() 
                {
                    if ($('#showAllTasks').is(':checked')) {
                        fetchAllTasks();  
                    } else {
                        console.log('Checkbox is unchecked, no action taken.');
                    }
                }

                function fetchAllTasks()
                {
                        $.ajax({
                                        url: '{{ route("get.tasks") }}',
                                        type: 'GET',
                                        data: {
                                            _token: '{{ csrf_token() }}',
                                        },
                                        success: function(response) {
                                    

                                            if (response.success) {
                                                $('#taskError').text(''); 
                                                    $('.list-group').empty();

                                                        response.data.forEach(function(task) {
                                                            // console.log(response);
                                                        var newTask = `
                                                            <li class="list-group-item task-item" id="task_${task.id}">
                                                            
                                                                    <div>
                                                                        <input type="checkbox" id="chk_id_${task.id}" onclick="handleCheckboxClick(this)" data-id="${task.id}">
                                                                    </div>
                                                                    <div>
                                                                        <span>${task.task_detail}</span>
                                                                    </div>
                                                                    <div>
                                                                        <small class="text-muted">${task.task_date_time}</small>
                                                                    </div>
                                                                    <div>
                                                                        <span>${task.task_status}</span>
                                                                    </div>
                                                                    <div>
                                                                        <img src=""" alt="Profile Picture">
                                                                    </div>
                                                                    <div>
                                                                        <button class="btn btn-danger btn-sm" onclick="confirmTaskDelete(${task.id})"><i class="fas fa-trash-alt"></i></button>
                                                                    </div>
                                                                
                                                            </li>`;
                                                                $('.list-group').append(newTask);
                                                        });
                                            } else {
                                                alert('Error: ' + response.message); 
                                            }
                                        },
                                        error: function(xhr, status, error) {
                                            var errorMessage = xhr.responseJSON ? xhr.responseJSON.message : 'An error occurred';
                                            alert('AJAX Error: ' + errorMessage); 
                                        }
                                });
                }

                function confirmTaskDelete(taskId) 
                {
                        Swal.fire({
                            title: 'Are you sure to delete this task ?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Delete'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                deleteTask(taskId);
                            }
                        });
                }

                function deleteTask(taskId) 
                {

                    $.ajax({
                        url: '/delete-task/' + taskId,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {

                            if (response.success) {
                               $('#task_' + taskId).remove(); 
                                Swal.fire('Deleted!', 'Your task has been deleted.', 'success');
                            } else {
                                Swal.fire('Error!', response.message, 'error');
                            }
                        },
                        error: function(xhr, status, error) {
                            var errorMessage = xhr.responseJSON ? xhr.responseJSON.message : 'An error occurred';
                            Swal.fire('AJAX Error!', errorMessage, 'error');
                        }
                    });
                }

       
    </script>



</body>
</html>
