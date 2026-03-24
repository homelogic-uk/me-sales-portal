<?php

namespace App\Models\Local\Leads\Survey;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = 'lead_survey_questions';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'question_text',
        'question_type',
    ];

    /**
     * Get the answers associated with the question.
     */
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
