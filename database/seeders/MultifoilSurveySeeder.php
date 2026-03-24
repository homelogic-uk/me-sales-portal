<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MultifoilSurveySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        $productId = 2; // Product 2: Multifoil Insulation

        // 1. Insert Questions
        DB::table('lead_survey_questions')->insert([
            ['id' => 20, 'product_id' => $productId, 'question_text' => 'What is the primary roof construction type?', 'question_type' => 'radio', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 21, 'product_id' => $productId, 'question_text' => 'What is the type and condition of the existing roof membrane (underlay)?', 'question_type' => 'radio', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 22, 'product_id' => $productId, 'question_text' => 'What existing insulation is currently present?', 'question_type' => 'checkbox', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 23, 'product_id' => $productId, 'question_text' => 'What is the visual condition of the roof timbers?', 'question_type' => 'radio', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 24, 'product_id' => $productId, 'question_text' => 'What is the state of the existing roof space ventilation?', 'question_type' => 'checkbox', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 25, 'product_id' => $productId, 'question_text' => 'What is the available rafter depth for counter-battening?', 'question_type' => 'radio', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 26, 'product_id' => $productId, 'question_text' => 'What is the homeowner\'s intended use for the loft space post-installation?', 'question_type' => 'radio', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 27, 'product_id' => $productId, 'question_text' => 'How is the physical access to the loft space?', 'question_type' => 'radio', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 28, 'product_id' => $productId, 'question_text' => 'Based on the survey, what is the required installation method for the multifoil?', 'question_type' => 'radio', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 2. Insert Answers
        DB::table('lead_survey_answers')->insert([
            // Q1 (ID 20)
            ['question_id' => 20, 'option_letter' => 'A', 'answer_text' => 'Cut timber roof (traditional rafters and purlins)', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 20, 'option_letter' => 'B', 'answer_text' => 'Trussed rafter roof (modern fink trusses)', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 20, 'option_letter' => 'C', 'answer_text' => 'Room in roof / Vaulted ceiling (requires insulating over existing plasterboard or stripping back)', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 20, 'option_letter' => 'D', 'answer_text' => 'Flat roof (timber joists)', 'created_at' => $now, 'updated_at' => $now],

            // Q2 (ID 21)
            ['question_id' => 21, 'option_letter' => 'A', 'answer_text' => 'Breathable membrane (good condition)', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 21, 'option_letter' => 'B', 'answer_text' => 'Bitumen / 1F non-breathable felt (good condition)', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 21, 'option_letter' => 'C', 'answer_text' => 'Bitumen / 1F non-breathable felt (tearing, degrading, or missing)', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 21, 'option_letter' => 'D', 'answer_text' => 'Timber sarking boards (common in Scotland)', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 21, 'option_letter' => 'E', 'answer_text' => 'No underlay (tiles visible)', 'created_at' => $now, 'updated_at' => $now],

            // Q3 (ID 22 - Checkbox)
            ['question_id' => 22, 'option_letter' => 'A', 'answer_text' => 'None', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 22, 'option_letter' => 'B', 'answer_text' => 'Mineral wool / Fiberglass between floor joists', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 22, 'option_letter' => 'C', 'answer_text' => 'Rigid PIR boards (e.g., Celotex/Kingspan) between rafters', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 22, 'option_letter' => 'D', 'answer_text' => 'Old multifoil or bubble-wrap insulation', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 22, 'option_letter' => 'E', 'answer_text' => 'Spray foam insulation (Requires removal before proceeding)', 'created_at' => $now, 'updated_at' => $now],

            // Q4 (ID 23)
            ['question_id' => 23, 'option_letter' => 'A', 'answer_text' => 'Dry and structurally sound', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 23, 'option_letter' => 'B', 'answer_text' => 'Minor water staining (historic, currently dry)', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 23, 'option_letter' => 'C', 'answer_text' => 'Active dampness or condensation on timbers/felt', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 23, 'option_letter' => 'D', 'answer_text' => 'Visible wood rot or active wood-boring insect damage (Requires treatment first)', 'created_at' => $now, 'updated_at' => $now],

            // Q5 (ID 24 - Checkbox)
            ['question_id' => 24, 'option_letter' => 'A', 'answer_text' => 'Soffit / Eaves vents present and clear', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 24, 'option_letter' => 'B', 'answer_text' => 'Ridge ventilation present', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 24, 'option_letter' => 'C', 'answer_text' => 'Tile/Slate vents installed', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 24, 'option_letter' => 'D', 'answer_text' => 'No visible cross-ventilation (Action required if installing below non-breathable felt)', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 24, 'option_letter' => 'E', 'answer_text' => 'Eaves blocked by existing floor insulation', 'created_at' => $now, 'updated_at' => $now],

            // Q6 (ID 25)
            ['question_id' => 25, 'option_letter' => 'A', 'answer_text' => 'Less than 50mm', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 25, 'option_letter' => 'B', 'answer_text' => '50mm - 75mm', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 25, 'option_letter' => 'C', 'answer_text' => '75mm - 100mm', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 25, 'option_letter' => 'D', 'answer_text' => 'Over 100mm', 'created_at' => $now, 'updated_at' => $now],

            // Q7 (ID 26)
            ['question_id' => 26, 'option_letter' => 'A', 'answer_text' => 'No access required (Insulation only)', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 26, 'option_letter' => 'B', 'answer_text' => 'Light storage (Requires raised boarding above the joist insulation)', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 26, 'option_letter' => 'C', 'answer_text' => 'Clean, usable hobby room (Requires foil stapled to rafters + boarding)', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 26, 'option_letter' => 'D', 'answer_text' => 'Full habitable loft conversion (Subject to building regulations)', 'created_at' => $now, 'updated_at' => $now],

            // Q8 (ID 27)
            ['question_id' => 27, 'option_letter' => 'A', 'answer_text' => 'Good: Large hatch, drop-down ladder, good head height at the apex', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 27, 'option_letter' => 'B', 'answer_text' => 'Fair: Standard push-up hatch, joist walking required', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 27, 'option_letter' => 'C', 'answer_text' => 'Poor: Very tight hatch, heavily restricted head height, complex truss webs', 'created_at' => $now, 'updated_at' => $now],

            // Q9 (ID 28)
            ['question_id' => 28, 'option_letter' => 'A', 'answer_text' => 'Stapled directly beneath rafters (taut) + counter-battened', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 28, 'option_letter' => 'B', 'answer_text' => 'Draped between rafters (to maintain minimum 50mm air gap below non-breathable felt)', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 28, 'option_letter' => 'C', 'answer_text' => 'Rolled across floor joists (to supplement existing mineral wool)', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
