<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE cards MODIFY COLUMN status ENUM('available', 'in_negotiation', 'sold', 'unavailable') DEFAULT 'available'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE cards MODIFY COLUMN status ENUM('available', 'in_negotiation', 'sold') DEFAULT 'available'");
    }
};