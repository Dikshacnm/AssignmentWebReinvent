@extends('layouts.app')

@section('content')
<div class="card border rounded p-3">
    <div class="form-group d-flex align-items-center">
        <input type="checkbox" id="showAllTasks" onclick="getAllTasks()" class="mr-2">
        <label for="showAllTasks" class="mb-0">Show All Tasks</label>
    </div>

    <div class="form-group">
        <form id="taskForm" enctype="multipart/form-data" class="d-flex align-items-center border rounded p-2">
      
            <div class="input-group mb-3">
                    <table class="table table-bordered table-hover">
                        <tbody>
                            <tr>
                                <td>
                                        <label for="profile_picture" class="cursor-pointer">
                                        <img src="{{ asset('storage/profile_images/profile_picture_default.png') }}" id="profile_picture_preview" class="profile-picture" alt="Profile Picture">
                                        </label>
                                        <input type="file" name="profile_picture" id="profile_picture" class="d-none">
                                        
                                </td>
                                <td>
                                        <input type="text" class="form-control" id="newTask" placeholder="Project # To Do">
                                </td>
                                <td>
                                    <div class="input-group-append">
                                        <button class="btn btn-success" type="button" id="addTaskButton">Add</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
            </div>
            
        </form>
        <span id="taskError" class="text-danger"></span>
    </div>

    <table class="table table-bordered table-hover">
        <thead>
        </thead>
        <tbody class="task-table-body">
           
        </tbody>
    </table>
</div>

@endsection


