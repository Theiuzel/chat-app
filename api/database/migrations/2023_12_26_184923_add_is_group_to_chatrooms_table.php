<?php

// database/migrations/xxxx_xx_xx_add_is_group_to_chatrooms_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsGroupToChatroomsTable extends Migration
{
    public function up()
    {
        Schema::table('chatrooms', function (Blueprint $table) {
            $table->tinyInteger('is_group')->default(0)->after('name');
        });
    }

    public function down()
    {
        Schema::table('chatrooms', function (Blueprint $table) {
            $table->dropColumn('is_group');
        });
    }
}
