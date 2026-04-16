<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('trans_order', function (Blueprint $group) {
            //
            $group->double('discount_amount')->default(0)->after('total');
            $group->double('tax_amount')->default(0)->after('discount_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trans_order', function (Blueprint $group) {
            //
            $group->dropColumn(['discount_amount', 'tax_amount']);
        });
    }
};
