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
                ),
                array(
                    'id'   => 2,
                    'name' => 'quind-client',
                    'secret' => 'PXEQA7HEEn4O8ZdAqqDUDcZxhE2OA41ZGovL3IRo',
                    'redirect' => 'http://localhost',
                    'password_client' => null,
                    'created_at' => '2017-10-22 01:34:38'
                ),
                array(
                    'id'   => 3,
                    'name' => 'wanda-client',
                    'secret' => 'iw6g3EWUSKMZi8K2L97WK0qJIuXezRHRr1AQma9o',
                    'redirect' => 'http://localhost',
                    'password_client' => null,
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
