<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskList extends Model
{
	public $timestamps = false;
    protected $table = 'task_list';    
    protected $fillable = [
        'task', 'status', 'completed'
    ];

}
