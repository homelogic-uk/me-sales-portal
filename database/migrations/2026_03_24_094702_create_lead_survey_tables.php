<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create the questions table
        Schema::create('lead_survey_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->index();
            $table->text('question_text');
            $table->string('question_type', 50)->default('radio');
            $table->timestamps();
        });

        // 2. Create the answers table
        Schema::create('lead_survey_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('lead_survey_questions')->onDelete('cascade');
            $table->string('option_letter', 2);
            $table->text('answer_text');
            $table->timestamps();
        });

        $now = Carbon::now();

        // 3. Insert the Questions
        DB::table('lead_survey_questions')->insert([
            ['id' => 1, 'product_id' => 1, 'question_text' => 'What is the primary roof construction type?', 'question_type' => 'radio', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'product_id' => 1, 'question_text' => 'What is the primary external roof covering?', 'question_type' => 'radio', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'product_id' => 1, 'question_text' => 'What substrate was the foam applied directly onto?', 'question_type' => 'radio', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'product_id' => 1, 'question_text' => 'What type of foam has been installed?', 'question_type' => 'radio', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 5, 'product_id' => 1, 'question_text' => 'What is the average depth/thickness of the foam application?', 'question_type' => 'radio', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 6, 'product_id' => 1, 'question_text' => 'What is the level of timber encapsulation?', 'question_type' => 'radio', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 7, 'product_id' => 1, 'question_text' => 'What is the average Wood Moisture Equivalent (WME) reading of the accessible timbers?', 'question_type' => 'radio', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 8, 'product_id' => 1, 'question_text' => 'Are there visible signs of timber decay or fungal growth?', 'question_type' => 'checkbox', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 9, 'product_id' => 1, 'question_text' => 'What is the state of the existing roof space ventilation?', 'question_type' => 'radio', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 10, 'product_id' => 1, 'question_text' => 'Are there electrical hazards present within the spray foam?', 'question_type' => 'radio', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 11, 'product_id' => 1, 'question_text' => 'Is there any visible or suspected Asbestos Containing Material (ACM) in the work area?', 'question_type' => 'radio', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 12, 'product_id' => 1, 'question_text' => 'How would you rate the internal access for the removal operatives?', 'question_type' => 'radio', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 13, 'product_id' => 1, 'question_text' => 'Did the homeowner receive a comprehensive handover pack at the time of installation?', 'question_type' => 'radio', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 14, 'product_id' => 1, 'question_text' => 'Is there a documented, property-specific Condensation Risk Assessment (CRA) available?', 'question_type' => 'radio', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 15, 'product_id' => 1, 'question_text' => 'Is there evidence that the installed spray foam is a BBA (British Board of Agrément) or KIWA accredited product?', 'question_type' => 'radio', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 16, 'product_id' => 1, 'question_text' => 'Does the homeowner possess a valid manufacturer or installer warranty?', 'question_type' => 'radio', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 17, 'product_id' => 1, 'question_text' => 'Is there an active Insurance Backed Guarantee (IBG) in place for the installation?', 'question_type' => 'radio', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 18, 'product_id' => 1, 'question_text' => 'Are there pre-installation photographs available showing the condition of the roof structure before the foam was applied?', 'question_type' => 'radio', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 19, 'product_id' => 1, 'question_text' => 'Surveyor Assessment: Based on the available paperwork, does the original installation meet the PCA Inspection Protocol for retainment?', 'question_type' => 'radio', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 4. Insert the Answers
        DB::table('lead_survey_answers')->insert([
            // Q1
            ['question_id' => 1, 'option_letter' => 'A', 'answer_text' => 'Cut timber roof (traditional rafters and purlins)', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 1, 'option_letter' => 'B', 'answer_text' => 'Trussed rafter roof (modern fink trusses)', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 1, 'option_letter' => 'C', 'answer_text' => 'Vaulted ceiling / Room in roof (plasterboard attached to rafters)', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 1, 'option_letter' => 'D', 'answer_text' => 'Flat roof (timber joists)', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 1, 'option_letter' => 'E', 'answer_text' => 'Scottish sarking (timber boards over rafters)', 'created_at' => $now, 'updated_at' => $now],

            // Q2
            ['question_id' => 2, 'option_letter' => 'A', 'answer_text' => 'Interlocking concrete tiles', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 2, 'option_letter' => 'B', 'answer_text' => 'Natural slate', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 2, 'option_letter' => 'C', 'answer_text' => 'Clay tiles', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 2, 'option_letter' => 'D', 'answer_text' => 'Plain tiles', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 2, 'option_letter' => 'E', 'answer_text' => 'Other (e.g., metal, felt)', 'created_at' => $now, 'updated_at' => $now],

            // Q3
            ['question_id' => 3, 'option_letter' => 'A', 'answer_text' => 'Direct to the underside of tiles/slates (no underlay present)', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 3, 'option_letter' => 'B', 'answer_text' => 'Bitumen/1F non-breathable felt', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 3, 'option_letter' => 'C', 'answer_text' => 'Breathable membrane', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 3, 'option_letter' => 'D', 'answer_text' => 'Timber sarking boards', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 3, 'option_letter' => 'E', 'answer_text' => 'Plasterboard / Lath and plaster', 'created_at' => $now, 'updated_at' => $now],

            // Q4
            ['question_id' => 4, 'option_letter' => 'A', 'answer_text' => 'Open-cell (low density, spongy, easily compressed)', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 4, 'option_letter' => 'B', 'answer_text' => 'Closed-cell (high density, rigid, hard)', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 4, 'option_letter' => 'C', 'answer_text' => 'Combination / Layered (e.g., closed-cell over open-cell)', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 4, 'option_letter' => 'D', 'answer_text' => 'Cannot determine on-site without lab testing', 'created_at' => $now, 'updated_at' => $now],

            // Q5
            ['question_id' => 5, 'option_letter' => 'A', 'answer_text' => 'Under 50mm', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 5, 'option_letter' => 'B', 'answer_text' => '50mm – 100mm', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 5, 'option_letter' => 'C', 'answer_text' => '100mm – 150mm', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 5, 'option_letter' => 'D', 'answer_text' => 'Over 150mm', 'created_at' => $now, 'updated_at' => $now],

            // Q6
            ['question_id' => 6, 'option_letter' => 'A', 'answer_text' => 'Partial: Sprayed between rafters only (rafter faces fully visible and clear)', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 6, 'option_letter' => 'B', 'answer_text' => 'Moderate: Overspray on rafter faces, but shape of timber is clearly defined', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 6, 'option_letter' => 'C', 'answer_text' => 'Total: Timbers are completely encapsulated (cannot see or locate rafters easily)', 'created_at' => $now, 'updated_at' => $now],

            // Q7
            ['question_id' => 7, 'option_letter' => 'A', 'answer_text' => 'Dry: < 15% WME', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 7, 'option_letter' => 'B', 'answer_text' => 'Borderline / Damp: 16% – 19% WME', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 7, 'option_letter' => 'C', 'answer_text' => 'Wet / At Risk of Decay: 20% – 28% WME', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 7, 'option_letter' => 'D', 'answer_text' => 'Saturated: > 28% WME', 'created_at' => $now, 'updated_at' => $now],

            // Q8
            ['question_id' => 8, 'option_letter' => 'A', 'answer_text' => 'None visible', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 8, 'option_letter' => 'B', 'answer_text' => 'Wet rot (soft, spongy, localized darkening)', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 8, 'option_letter' => 'C', 'answer_text' => 'Dry rot (cuboidal cracking, mycelium, pancake fruiting bodies)', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 8, 'option_letter' => 'D', 'answer_text' => 'Frass or boreholes (active/historic wood-boring insect attack)', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 8, 'option_letter' => 'E', 'answer_text' => 'Delamination of manufactured timbers (e.g., OSB/plywood splitting)', 'created_at' => $now, 'updated_at' => $now],

            // Q9
            ['question_id' => 9, 'option_letter' => 'A', 'answer_text' => 'Adequate: Clear airflow at eaves, ridge, or via tile vents', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 9, 'option_letter' => 'B', 'answer_text' => 'Blocked: Eaves blocked by foam overspray or insulation', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 9, 'option_letter' => 'C', 'answer_text' => 'None: No cross-ventilation present in the void', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 9, 'option_letter' => 'D', 'answer_text' => 'Unvented system (but failing to perform as a warm roof)', 'created_at' => $now, 'updated_at' => $now],

            // Q10
            ['question_id' => 10, 'option_letter' => 'A', 'answer_text' => 'None visible', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 10, 'option_letter' => 'B', 'answer_text' => 'Lighting cables encapsulated in foam', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 10, 'option_letter' => 'C', 'answer_text' => 'Power cables (e.g., shower, solar PV, ring main) encapsulated in foam', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 10, 'option_letter' => 'D', 'answer_text' => 'Junction boxes buried in foam (High risk)', 'created_at' => $now, 'updated_at' => $now],

            // Q11
            ['question_id' => 11, 'option_letter' => 'A', 'answer_text' => 'No suspected ACMs', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 11, 'option_letter' => 'B', 'answer_text' => 'Cement flue pipe passing through the loft', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 11, 'option_letter' => 'C', 'answer_text' => 'Artex/Textured coating visible on ceilings below', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 11, 'option_letter' => 'D', 'answer_text' => 'Asbestos cement roof tiles/slates', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 11, 'option_letter' => 'E', 'answer_text' => 'Asbestos insulating board (AIB) present (e.g., firebreaks)', 'created_at' => $now, 'updated_at' => $now],

            // Q12
            ['question_id' => 12, 'option_letter' => 'A', 'answer_text' => 'Good: Large hatch, boarded walkways, full standing height at apex', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 12, 'option_letter' => 'B', 'answer_text' => 'Fair: Standard hatch, unboarded (joist walking required), limited head height', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 12, 'option_letter' => 'C', 'answer_text' => 'Poor: Very tight hatch (< 500mm), restrictive truss webs, shallow pitch (crawling only)', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 12, 'option_letter' => 'D', 'answer_text' => 'Restricted: Large obstructions (e.g., header tanks, chimney stacks, MVHR units) blocking access', 'created_at' => $now, 'updated_at' => $now],

            // Q13
            ['question_id' => 13, 'option_letter' => 'A', 'answer_text' => 'Yes, full documentation pack is available for review.', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 13, 'option_letter' => 'B', 'answer_text' => 'Partial documentation only (e.g., just an invoice or a basic leaflet).', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 13, 'option_letter' => 'C', 'answer_text' => 'No documentation available / Paperwork lost.', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 13, 'option_letter' => 'D', 'answer_text' => 'Unsure (installed by previous owner with no records passed on).', 'created_at' => $now, 'updated_at' => $now],

            // Q14
            ['question_id' => 14, 'option_letter' => 'A', 'answer_text' => 'Yes, a property-specific CRA is present and appears robust.', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 14, 'option_letter' => 'B', 'answer_text' => 'Yes, but it appears to be a generic/templated document rather than property-specific.', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 14, 'option_letter' => 'C', 'answer_text' => 'No Condensation Risk Assessment was provided.', 'created_at' => $now, 'updated_at' => $now],

            // Q15
            ['question_id' => 15, 'option_letter' => 'A', 'answer_text' => 'Yes, BBA or KIWA certificate number is clearly documented for the specific product used.', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 15, 'option_letter' => 'B', 'answer_text' => 'The product is named, but no formal accreditation certificate is included.', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 15, 'option_letter' => 'C', 'answer_text' => 'Product is unnamed / No evidence of BBA or KIWA accreditation.', 'created_at' => $now, 'updated_at' => $now],

            // Q16
            ['question_id' => 16, 'option_letter' => 'A', 'answer_text' => 'Yes, Manufacturer Product Warranty (usually 20-25 years).', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 16, 'option_letter' => 'B', 'answer_text' => 'Yes, Installer Workmanship Guarantee.', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 16, 'option_letter' => 'C', 'answer_text' => 'Yes, both Manufacturer and Installer warranties are present.', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 16, 'option_letter' => 'D', 'answer_text' => 'No warranty paperwork is present.', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 16, 'option_letter' => 'E', 'answer_text' => 'The original installation company has ceased trading (installer warranty is likely void).', 'created_at' => $now, 'updated_at' => $now],

            // Q17
            ['question_id' => 17, 'option_letter' => 'A', 'answer_text' => 'Yes, a valid, independent IBG certificate is present.', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 17, 'option_letter' => 'B', 'answer_text' => 'No IBG was provided or is present.', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 17, 'option_letter' => 'C', 'answer_text' => 'Unsure / Cannot verify from the paperwork provided.', 'created_at' => $now, 'updated_at' => $now],

            // Q18
            ['question_id' => 18, 'option_letter' => 'A', 'answer_text' => 'Yes, comprehensive and clear photos showing healthy timbers and a dry roof space.', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 18, 'option_letter' => 'B', 'answer_text' => 'Yes, but limited in scope, blurry, or missing key structural areas.', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 18, 'option_letter' => 'C', 'answer_text' => 'No pre-installation photographs are available.', 'created_at' => $now, 'updated_at' => $now],

            // Q19 (Non-Compliant option removed)
            ['question_id' => 19, 'option_letter' => 'A', 'answer_text' => 'Fully Compliant: All PCA criteria met (CRA, IBG, BBA/KIWA, Photos). Foam may potentially remain if physically sound.', 'created_at' => $now, 'updated_at' => $now],
            ['question_id' => 19, 'option_letter' => 'B', 'answer_text' => 'Partially Compliant: Missing critical elements (e.g., no CRA or no IBG), but product is accredited. Further investigation required, may require removal.', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answers');
        Schema::dropIfExists('questions');
    }
};
