<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Remove corrupted records with empty method_type (caused by invalid enum values)
        DB::table('survey_ai_recommendations')->where('method_type', '')->delete();

        DB::statement("ALTER TABLE survey_ai_recommendations MODIFY COLUMN method_type ENUM('SUS', 'TAM', 'NASA-TLX', 'VisAWI-S') NOT NULL");
    }

    public function down(): void
    {
        DB::table('survey_ai_recommendations')
            ->whereIn('method_type', ['NASA-TLX', 'VisAWI-S'])
            ->delete();

        DB::statement("ALTER TABLE survey_ai_recommendations MODIFY COLUMN method_type ENUM('SUS', 'TAM') NOT NULL");
    }
};
