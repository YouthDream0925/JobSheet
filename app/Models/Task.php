<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks';

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $fillable = [
        'id',
        'job_id',
        'title',
        'create_user',
        'assign_users',
        'status',
        'coordinater',
        'university',
    ];

    public function scopePopular($query)
    {
        $user = Auth::user();
        $userId = $user->id;

        if(!empty($user->getRoleNames()) && $user->hasExactRoles('SimpleUser'))
        {
            $query->with('subtasks')
                ->whereHas('subtasks', function($query) {
                    $query->where('assign_users', 'like', '%,' .Auth::user()->id. ',%');
                })
                ->orWhere('assign_users', 'like', '%,' .Auth::user()->id. ',%');
        }

        return $query->get();
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

    public function jobcard(){
        return $this->belongsTo(JobCard::class, 'job_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'type_id', 'id')->where('type', 'task');
    }

    public function descriptions()
    {
        return $this->hasMany(Description::class, 'type_id', 'id')->where('type', 'task');
    }

    public function search_comments($phase)
    {
        $comments = Comment::where('type', 'task')->where('type_id', $this->id)->where('phase', $phase)->get();
        return $comments;
    }

    public function subtasks()
    {
        return $this->hasMany(SubTask::class, 'task_id', 'id')->orderBy('updated_at', 'desc');
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
