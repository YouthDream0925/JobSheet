<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $table = 'answers';

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $fillable = [
        'id',
        'question_id',
        'answer',
        'selected'
    ];

    public function question()
    {
        return $this->belongTo(Question::class, 'question_id', 'id');
    }
}