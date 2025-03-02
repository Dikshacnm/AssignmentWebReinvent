$(document).ready(function () {
    let csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": csrfToken,
        },
    });
    fetchAllTasks("not_completed");

    $("#addTaskButton").click(function () {
        var task = $("#newTask").val();
        var currentDate = new Date();
        var taskDateTime = currentDate
            .toISOString()
            .slice(0, 19)
            .replace("T", " ");

        //var url = $(this).data("url");

        $.ajax({
            url: "/add-task/",
            type: "POST",
            data: {
                task_detail: task,
                task_date_time: taskDateTime,
            },
            success: function (response) {
                $("#taskError").text("");
                $(".task-table-body").empty();

                response.data.forEach(function (task) {
                    // console.log(response);

                    var newTask = `<tr id="task_${task.id}">

                                        <td>
                                            <input type="checkbox" id="chk_id_${
                                                task.id
                                            }" onclick="handleCheckboxClick(this)" data-id="${
                        task.id
                    }" ${task.task_status === "completed" ? "checked" : ""}>
                                        </td>

                                        <td>${task.task_detail}</td>

                                        <td><small class="text-muted">${
                                            task.task_date_time
                                        }</small></td>

                                        <td><img src="" alt="Profile Picture" width="30"></td>

                                        <td>
                                            <button class="btn btn-danger btn-sm" onclick="confirmTaskDelete(${
                                                task.id
                                            })">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>

                                    </tr>`;

                    $(".task-table-body").append(newTask);
                });
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.responseJSON
                    ? xhr.responseJSON.data
                    : "An error occurred";
                $("#taskError").text(errorMessage);
            },
        });
    });
});

function handleCheckboxClick(checkbox) {
    var taskId = $(checkbox).data("id");
    if (checkbox.checked) {
        markTaskComplete(taskId);
    }
}

//task status update function
function markTaskComplete(id) {
    $.ajax({
        url: "/complete-task",
        type: "POST",
        data: {
            task_id: id,
        },
        success: function (response) {
            if (response.success) {
                $("#task_" + id).remove();
            } else {
                alert("Error: " + response.message);
            }
        },
        error: function (xhr, status, error) {
            // var errorMessage = xhr.responseJSON ? xhr.responseJSON.message : 'An error occurred';
            alert("AJAX Error: " + errorMessage);
        },
    });
}

function getAllTasks() {
    if ($("#showAllTasks").is(":checked")) {
        fetchAllTasks("all");
    } else {
        fetchAllTasks("not_completed");
    }
}

function fetchAllTasks(filter = "all") {
    let url = "/all-tasks";

    if (filter === "not_completed") {
        url = "/not-completed-tasks";
    }
    $.ajax({
        url: url,
        type: "GET",
        success: function (response) {
            if (response.success) {
                $("#taskError").text("");
                $(".task-table-body").empty();

                response.data.forEach(function (task) {
                    // console.log(response);
                    if (filter === "not_completed") {
                        var newTask = `<tr id="task_${task.id}">

                                        <td>
                                            <input type="checkbox" id="chk_id_${
                                                task.id
                                            }" onclick="handleCheckboxClick(this)" data-id="${
                            task.id
                        }" ${task.task_status === "completed" ? "checked" : ""}>
                                        </td>

                                        <td>${task.task_detail}</td>

                                        <td><small class="text-muted">${
                                            task.task_date_time
                                        }</small></td>

                                        <td><img src="" alt="Profile Picture" width="30"></td>

                                        <td>
                                            <button class="btn btn-danger btn-sm" onclick="confirmTaskDelete(${
                                                task.id
                                            })">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>

                                    </tr>`;
                    } else {
                        var newTask = `<tr id="task_${task.id}">

                                        <td>${task.task_detail}</td>

                                        <td>${task.task_status}</td>

                                        <td>
                                            <button class="btn btn-danger btn-sm" onclick="confirmTaskDelete(${task.id})">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>

                                    </tr>`;
                    }

                    $(".task-table-body").append(newTask);
                });
            } else {
                alert("Error: " + response.message);
            }
        },
        error: function (xhr, status, error) {
            var errorMessage = xhr.responseJSON
                ? xhr.responseJSON.message
                : "An error occurred";
            alert("AJAX Error: " + errorMessage);
        },
    });
}

function confirmTaskDelete(taskId) {
    Swal.fire({
        title: "Are you sure to delete this task ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Delete",
    }).then((result) => {
        if (result.isConfirmed) {
            deleteTask(taskId);
        }
    });
}

function deleteTask(taskId) {
    $.ajax({
        url: "/delete-task/" + taskId,
        type: "DELETE",
        success: function (response) {
            if (response.success) {
                $("#task_" + taskId).remove();
                Swal.fire("Deleted!", "Your task has been deleted.", "success");
            } else {
                Swal.fire("Error!", response.message, "error");
            }
        },
        error: function (xhr, status, error) {
            var errorMessage = xhr.responseJSON
                ? xhr.responseJSON.message
                : "An error occurred";
            Swal.fire("AJAX Error!", errorMessage, "error");
        },
    });
}
