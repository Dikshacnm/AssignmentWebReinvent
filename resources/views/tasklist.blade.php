@extends('layouts.app')

@section('content')
                <div class="card border rounded p-3">
    <div class="form-group d-flex align-items-center">
        <input type="checkbox" id="showAllTasks" onclick="getAllTasks()" class="mr-2">
        <label for="showAllTasks" class="mb-0">Show All Tasks</label>
    </div>

    <div class="form-group">
        <label for="newTask">Project # To Do</label>
        <div class="input-group mb-3">
             <input type="file" class="form-control" id="taskProfilePicture" accept="image/*">
            <input type="text" class="form-control" id="newTask" placeholder="Enter new task">
            <div class="input-group-append">
                <button class="btn btn-success" type="button" id="addTaskButton">Add</button>
            </div>
        </div>
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


