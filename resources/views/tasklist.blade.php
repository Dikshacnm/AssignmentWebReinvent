@extends('layouts.app')

@section('content')
                <div class="form-group">
                    <input type="checkbox" id="showAllTasks" onClick="getAllTasks()">
                    <label for="showAllTasks">Show All Tasks</label>
                </div>

                <div class="form-group">
                    <label for="newTask">Project # To Do</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="newTask" placeholder="Enter new task">
                        <div class="input-group-append">
                            <button class="btn btn-success" type="button" id="addTaskButton">Add</button>
                        </div>
                    </div>
                    <span id="taskError" class="text-danger"></span>
                </div>

                    <ul class="list-group">
                       
                       
                    </ul>
@endsection


