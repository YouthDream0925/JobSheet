<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $table = 'questions';

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $fillable = [
        'id',
        'create_user',
        'job_id',
        'content',
        'type',
        'order'
    ];

    public function answers()
    {
        return $this->hasMany(Answer::class, 'question_id', 'id');
    }

    public function answers_list()
    {
        $answers = Answer::all();
        $list = ['' => ''];
        foreach($answers as $key => $value) {
            if($value->question_id == $this->id)
                $list[$value->id] = $value->answer;
        }
        return $list;
    }

    public function correct_answer()
    {
        $answers = Answer::all();
        $correct_answer = null;
        foreach($answers as $key => $value) {
            if($value->question_id == $this->id && $value->correct == 1)
                $correct_answer = $value->id;
        }
        return $correct_answer;
    }

    public function correct_answer_string()
    {
        $answers = Answer::all();
        $answer = null;
        foreach($answers as $key => $value) {
            if($value->question_id == $this->id && $value->correct == 1)
                $answer = $value->answer;
        }
        return $answer;
    }

    public function jobcard()
    {
        return $this->belongTo(JobCard::class, 'job_id', id);
    }
}