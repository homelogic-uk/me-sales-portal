<?php

namespace App\Models\Local\Leads\Survey;

use App\Models\CRM\Lead;
use App\Models\CRMUser;
use App\Models\Local\Products\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\LaravelPdf\Facades\Pdf;

class Survey extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lead_survey';

    protected $connection = 'mysql';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'lead_id',
        'user_id',
        'product_id',
        'answers',
        'rep_signature',
        'client_signature'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'answers' => 'array', // Automatically converts JSON string to array
    ];

    /**
     * Get the lead that owns the survey.
     */
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    /**
     * Get the user who submitted the survey.
     */
    public function user()
    {
        return $this->belongsTo(CRMUser::class, 'user_id', 'user_id');
    }

    /**
     * Get the product associated with the survey.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function generate()
    {
        $fileName = Str::uuid7() . '.pdf';
        $survey = $this;

        Pdf::view('pdf.survey', compact('survey'))
            ->withBrowsershot(function ($browsershot) {
                $browsershot->setChromePath('/opt/puppeteer-chrome/chrome')
                    ->noSandbox()
                    ->addArgs([
                        '--disable-setuid-sandbox',
                        '--disable-dev-shm-usage',
                        '--no-zygote',
                        '--disable-crash-reporter', // Directly fixes the crashpad error
                        '--disable-gpu',            // Prevents graphics engine crashes on Linux
                        '--single-process'          // Runs everything in one process (good for restricted servers)
                    ]);
            })
            ->disk('local')
            ->save($fileName);

        return $fileName;
    }
}
