<?php
use Illuminate\Support\Facades\Schema;
Schema::table('laporan', function ($table) {
    $table->dropColumn('user_id');
});
echo "Column dropped.";
