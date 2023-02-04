<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class JobCard extends Model
{
    use HasFactory;

    protected $perPage = 10;

    protected $table = 'job_cards';

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $fillable = [
        'id',
        'title',
        'create_user',
        'assign_users',
        'status',
        'coordinater',
        'university',
        'special',
        'order'
    ];

    public function scopePopular($query, $per_page)
    {
        $user = Auth::user();
        $userId = $user->id;

        if(!empty($user->getRoleNames()) && $user->hasExactRoles('SimpleUser'))
        {
            $query->with('tasks')
                ->whereHas('tasks', function($query) {
                    $query->with('subtasks')
                        ->whereHas('subtasks', function($query) {
                            $query->where('assign_users', 'like', '%,' .Auth::user()->id. ',%');
                        })
                        ->orWhere('assign_users', 'like', '%,' .Auth::user()->id. ',%');
                })
                ->orWhere('assign_users', 'like', '%,' .Auth::user()->id. ',%');
        }
        
        if($per_page != null)
            return $query->orderBy('order', 'asc')->orderBy('updated_at', 'desc')->paginate($per_page)->appends(['per_page' => $per_page]);
        else
           return $query->orderBy('order', 'asc')->orderBy('updated_at', 'desc')->paginate(config('pagination.per_page'))->appends(['per_page' => config('pagination.per_page')]);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'type_id', 'id')->where('type', 'job');
    }

    public function descriptions()
    {
        return $this->hasMany(Description::class, 'type_id', 'id')->where('type', 'job');
    }

    public function question_description()
    {
        return $this->hasOne(Description::class, 'type_id', 'id')->where('type', 'question');
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'job_id', 'id')->orderBy('order', 'asc')->orderBy('updated_at', 'desc');
    }

    public function multiquestions()
    {
        return $this->hasMany(Question::class, 'job_id', 'id')->where('type', 0);
    }

    public function datequestions()
    {
        return $this->hasMany(Question::class, 'job_id', 'id')->where('type', 1);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'job_id', 'id')->orderBy('order', 'asc')->orderBy('updated_at', 'desc');
    }

    public function simple_tasks()
    {
        $user = Auth::user();
        $tasks = Task::where('job_id', $this->id)->get();
        $simple_tasks = [];
        foreach($tasks as $key => $task) {
            $task_assign_check = false;
            foreach($task->assign() as $key => $value) {
                if($value == $user->email)
                    $task_assign_check = true;
            }
            if($task_assign_check == true)
                array_push($simple_tasks, $task);
        }
        // array_unique($simple_tasks);
        return $simple_tasks;
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
}