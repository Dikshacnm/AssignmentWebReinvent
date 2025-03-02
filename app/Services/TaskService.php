<?php 

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Task;

class TaskService{


    public function index($filter = 'all'){
              try{
                if($filter == 'incomplete'){
                    $allTasks = Task::where('task_status', 'notcompleted')->orderBy('id', 'desc')->get();
                }
                else{
                    $allTasks = Task::orderBy('id', 'desc')->get();
                }
                
                return $allTasks;
            }
            catch(QueryException $e){
                DB::rollBack();
                throw $e;
            }
    }

    
    public function create($data,$req){
      
       try{
                DB::beginTransaction();
                if ($req->hasFile('task_created_by')) {
                    $file = $req->file('task_created_by');
                    $filename = $file->getClientOriginalName();
                    $path = $file->storeAs('profile_images', $filename, 'public');
                    $data['task_created_by'] = $filename;
                }

                $task = new Task();
                $task->task_detail = $data['task_detail'];
                $task->task_date_time = $data['task_date_time'];
                $task->task_created_by = $data['task_created_by'] ?? null;
                $task->save();

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