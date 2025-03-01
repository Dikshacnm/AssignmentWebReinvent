<?php 

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Task;

class TaskService{


    public function index(){
              try{
                
                $allTasks = Task::orderBy('id', 'desc')->get();
                return $allTasks;
            }
            catch(QueryException $e){
                DB::rollBack();
                throw $e;
            }
    }

    
    public function create($data){
      
       try{
                DB::beginTransaction();
 
                $created = Task::create([
                    'task_detail'=>$data['task_detail'],
                    //'task_created_by'=>$data['task_created_by'],
                    'task_date_time'=>$data['task_date_time'],
                ]);

                DB::Commit();
                $allTasks = Task::where('task_status', 'notcompleted')->orderBy('id', 'desc')->get();
            return $allTasks;
            }
            catch(QueryException $e){
                DB::rollBack();
                throw $e;
            }
    }

    
    public function update($data){
        try{
                DB::beginTransaction();
 
                $task = Task::findOrFail($data['task_id']);
                $task->update(['task_status' => 'completed']);

                DB::Commit();
                
                $allTasks = Task::where('task_status', 'notcompleted')->orderBy('id', 'desc')->get();
            return $allTasks;
            }
            catch(QueryException $e){
                DB::rollBack();
                throw $e;
            }
    }

    
    public function delete($task_id){
         try{
                $task = Task::findOrFail($task_id);
                $task->delete();
                return true;
            }
            catch(QueryException $e){
                DB::rollBack();
                throw $e;
            }
    }
}