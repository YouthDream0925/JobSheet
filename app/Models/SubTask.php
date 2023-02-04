<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SubTask extends Model
{
    use HasFactory;

    protected $table = 'sub_tasks';

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $fillable = [
        'id',
        'task_id',
        'title',
        'create_user',
        'assign_users',
        'status',
        'coordinater',
        'university',
        'order'
    ];

    public function scopePopular($query)
    {
        $user = Auth::user();
        $userId = $user->id;
        if(!empty($user->getRoleNames()) && $user->hasExactRoles('SimpleUser'))
        {
            $query->where('assign_users', 'like', '%,' .Auth::user()->id. ',%');
        }
        return $query->orderBy('order', 'asc')->orderBy('updated_at', 'desc')->get();
    }

    public function scopeSearch($query, $assigned, $status, $title)
    {
        if($assigned != null && $status != null)
            $query->where('assign_users', 'like', '%,'.$assigned.',%')->where('status', $status)->where('title', 'like', '%'.$title.'%');
        else if($assigned != null && $status == null)
            $query->where('assign_users', 'like', '%,'.$assigned.',%')->where('title', 'like', '%'.$title.'%');
        else if($assigned == null && $status != null)
            $query->where('status', $status)->where('title', 'like', '%'.$title.'%');
        else if($assigned == null && $status == null)
            $query->where('title', 'like', '%'.$title.'%');
        else
            $query->where('title', 'like', '%'.$title.'%');
        return $query->get();
    }
    
    public function task(){
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'type_id', 'id')->where('type', 'subTask');
    }

    public function descriptions()
    {
        return $this->hasMany(Description::class, 'type_id', 'id')->where('type', 'subTask');
    }

    public function creator()
    {
        $user = User::find($this->create_user);
        return $user->email;
    }

    public function name()
    {
        $user = User::find($this->create_user);
        $name = $user->firstname.' '.$user->lastname; 
        return $name;
    }

    public function assign()
    {
        $users = User::pluck('email','id')->all();
        $assign_users = [];

        foreach(explode(',', $this->assign_users) as $item) {
            foreach($users as $key => $user) {
                if($key == $item) {
                    $assign_users[$key] = $user;
                }
            }
        }
        
        return $assign_users;
    }

    public function job()
    {
        $task = Task::find($this->task_id);
        $job_card = JobCard::find($task->job_id);

        return $job_card->id;
    }
}
