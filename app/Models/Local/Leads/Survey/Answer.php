<?php

namespace App\Models\Local\Leads\Survey;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $table = 'lead_survey_answers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'question_id',
        'option_letter',
        'answer_text',
    ];

    /**
     * Get the question that owns this answer.
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
