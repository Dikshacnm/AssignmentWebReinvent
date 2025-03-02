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

    /****************************Default Image for Profile************************/

    function resetProfilePicturePreview() {
        const img = document.getElementById("profile_picture_preview");
        img.src = "storage/profile_images/profile_picture_default.png";
    }
    /****************************Default Image Ends here ************************/

    /****************************Profile Image Preview setting function************************/
    document
        .getElementById("profile_picture")
        .addEventListener("change", function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const img = document.getElementById(
                        "profile_picture_preview"
                    );
                    img.src = e.target.result;
                    img.style.display = "block";
                };
                reader.readAsDataURL(file);
            } else {
                resetProfilePicturePreview();
            }
        });
    /****************************Profile Image Preview setting function ends here**************/

    /****************************Add New Task into the list ************************/
    $("#addTaskButton").click(function () {
        var task = $("#newTask").val();
        var taskImage = $("#profile_picture")[0].files[0];
        var currentDate = new Date();
        var taskDateTime = currentDate
            .toISOString()
            .slice(0, 19)
            .replace("T", " ");

        var formData = new FormData();
        formData.append("task_detail", task);
        formData.append("task_date_time", taskDateTime);
        if (taskImage) {
            formData.append("task_created_by", taskImage);
        }

        $.ajax({
            url: "/add-task/",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                $("#taskError").text("");
                $(".task-table-body").empty();
                $("#showAllTasks").prop("checked", false);

                response.data.forEach(function (task) {
                    // console.log(response);

                    var imageUrl = task.task_created_by
                        ? "storage/profile_images/" + task.task_created_by
                        : "storage/profile_images/profile_picture_default.png";
                    var newTask = `<tr id="task_${task.id}">

                                                <td>
                                                    <input type="checkbox" id="chk_id_${
                                                        task.id
                                                    }" onclick="handleTaskCheckboxClick(this)" data-id="${
                        task.id
                    }" ${task.task_status === "completed" ? "checked" : ""}>
                                                </td>

                                                <td>${
                                                    task.task_detail
                                                }&nbsp;&nbsp;<small class="text-muted">${
                        task.task_date_time
                    }</small></td>

                                                <td><img src="${imageUrl}" class="profile-picture" ></td>

                                                <td>
                                                    <button class="btn btn-danger btn-sm" onclick="confirmTaskDelete(${
                                                        task.id
                                                    })">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </td>

                                            </tr>`;

                    $(".task-table-body").append(newTask);
                    resetProfilePicturePreview();
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

    /****************************Add New Task ends here ************************/
});

/****************************Mark task Complete on checkbox check ************************/
function handleTaskCheckboxClick(checkbox) {
    var taskId = $(checkbox).data("id");
    if (checkbox.checked) {
        markTaskComplete(taskId);
    }
}

/****************************Task status update on Checkbox check ************************/
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
/****************************Task status update ends here************************/

/****************************Mark task Complete ends here ************************/

/****************************Get All the tasks on Show All Tasks click and to load tasks by default on page load************************/
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
                        var imageUrl = task.task_created_by
                            ? "storage/profile_images/" + task.task_created_by
                            : "storage/profile_images/profile_picture_default.png";
                        var task = `<tr id="task_${task.id}">

                                                <td>
                                                    <input type="checkbox" id="chk_id_${
                                                        task.id
                                                    }" onclick="handleTaskCheckboxClick(this)" data-id="${
                            task.id
                        }" ${task.task_status === "completed" ? "checked" : ""}>
                                                </td>

                                                <td>${
                                                    task.task_detail
                                                }&nbsp;&nbsp;<small class="text-muted">${
                            task.task_date_time
                        }</small></td>

                                                <td><img src="${imageUrl}" class="profile-picture" ></td>

                                                <td>
                                                    <button class="btn btn-danger btn-sm" onclick="confirmTaskDelete(${
                                                        task.id
                                                    })">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </td>

                                            </tr>`;
                    } else {
                        var task = `<tr id="task_${task.id}">

                                                <td>${task.task_detail}</td>

                                                <td>${task.task_status}</td>

                                                <td>
                                                    <button class="btn btn-danger btn-sm" onclick="confirmTaskDelete(${task.id})">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </td>

                                            </tr>`;
                    }

                    $(".task-table-body").append(task);
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

/****************************Get All the tasks ends here*******************************************************************************/

/***********************************Delete Tasks ****************************************/
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

/***********************************Delete Tasks Ends here*******************************/
