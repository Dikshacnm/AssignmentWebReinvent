<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\{AddTaskRequest,TaskUpdateRequest};
use App\Services\TaskService;
use App\Http\Resources\TaskResource;

class TaskController extends Controller
{
    protected $taskService;
    public function __construct(TaskService $taskService){
        $this->taskService = $taskService;
    }

    public function getTasks(){
        
        try{
            $result = $this->taskService->index('all'); 
        
            return sendSuccessResponse('Tasklist fetched successfully',TaskResource::collection($result),201);
        }
        catch(\Exception $e){

            return sendErrorResponse($e->getMessage(),400);
        }
    }

    public function getIncompleteTasks(){
         try{
            $result = $this->taskService->index('incomplete'); 
        
            return sendSuccessResponse('Tasklist fetched successfully',TaskResource::collection($result),201);
        }
        catch(\Exception $e){

            return sendErrorResponse($e->getMessage(),400);
        }
    }

    public function addTask(AddTaskRequest $req){
  
        $validatedData = $req->validated();
      
        try{
            $result = $this->taskService->create($validatedData); 
        
            return sendSuccessResponse('Task added successfully',TaskResource::collection($result),201);
        }
        catch(\Exception $e){

            return sendErrorResponse($e->getMessage(),400);
        }

    }

    public function updateTask(TaskUpdateRequest $req){

         $validatedData = $req->validated();

         try{
            $result = $this->taskService->update($validatedData); 
        
            return sendSuccessResponse('Task status updated successfully',TaskResource::collection($result),200);
        }
        catch(\Exception $e){

            return sendErrorResponse($e->getMessage(),400);
        }

    }

    public function deleteTask($task_id){

         try{
            $result = $this->taskService->delete($task_id); 
        
            return sendSuccessResponse('Task deleted.','',200);
        }
        catch(\Exception $e){

            return sendErrorResponse($e->getMessage(),400);
        }

    }
}
