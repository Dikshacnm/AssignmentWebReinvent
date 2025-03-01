<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource 
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //return parent::toArray($request);

         return [  
            'id' => isset($this->id)?(string)$this->id:'',
            'task_detail' => isset($this->task_detail)?(string)$this->task_detail:'',
            'task_status' => isset($this->task_status)?(string)$this->task_status:'',
            'task_created_by' => isset($this->task_created_by)?(string)$this->task_created_by:'',
            'task_date_time' => isset($this->task_date_time)?(string)$this->task_date_time->diffForHumans():'',
        ];
    }
}
