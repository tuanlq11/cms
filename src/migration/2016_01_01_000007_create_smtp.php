<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmtp extends Migration
{
    protected $table_name = 'smtps';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table_name, function ($table) {
            $table->increments("id");
            $table->enum("driver", ["smtp", "mail", "sendmail", "mailgun", "mandrill", "ses", "sparkpost", "log"]);
            $table->string("name");
            $table->string("host");
            $table->integer("port");
            $table->string("from");
            $table->enum("encryption", ["tls", "ssl"]);
            $table->string("username");
            $table->string("password");
            $table->boolean("cms_default")->default(false);
        });

        DB::connection()->getPdo()->exec(<<<SQL
        
         CREATE OR REPLACE FUNCTION sync_smtp_default() RETURNS TRIGGER AS $$
            BEGIN
            
              IF NEW.cms_default = TRUE THEN 
                  UPDATE smtp
                  SET cms_default = FALSE 
                  WHERE id <> NEW.id;
              END IF;
            
              RETURN NEW;
            
            END;
        $$ LANGUAGE plpgsql;
        
        CREATE TRIGGER sync_smtp_default
        AFTER UPDATE OF cms_default ON smtp
        FOR EACH ROW WHEN (pg_trigger_depth() < 1) EXECUTE PROCEDURE sync_smtp_default();

SQL
        );

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::connection()->getPdo()->exec(<<<SQL
          
          DROP TRIGGER IF EXISTS sync_smtp_default ON smtp;
          DROP FUNCTION IF EXISTS sync_smtp_default();

SQL
        );

        Schema::dropIfExists($this->table_name);
    }
}
