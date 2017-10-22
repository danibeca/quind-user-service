<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientRecord extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('oauth_clients')->insert(
            array(
                array(
                    'id'   => 1,
                    'name' => 'quind-front',
                    'secret' => 'PU8KCsFQKkxaPGfwq2zrtYVHFpwwvgSaYlKNm4zX',
                    'redirect' => 'http://localhost',
                    'password_client' => 1,
                    'created_at' => '2017-10-22 01:34:38'
                )
            ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
