<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigrateDataToPostgres extends Command
{
    protected $signature = 'db:migrate-data';
    protected $description = 'Migrate data from SQLite to PostgreSQL';

    public function handle()
    {
        $this->info('Starting database data migration from sqlite to pgsql...');
        
        try {
            $sqlite = DB::connection('sqlite');
            $pgsql = DB::connection('pgsql');
            
            $tables = $sqlite->select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
            
            $pgsql->statement('SET session_replication_role = replica;');
            
            foreach ($tables as $t) {
                $table = $t->name;
                $this->info("Migrating table: {$table}");
                
                $pgsql->table($table)->delete();
                
                $rows = $sqlite->table($table)->get()->map(function($row) {
                    return (array) $row;
                })->toArray();
                
                if (count($rows) > 0) {
                    $chunks = array_chunk($rows, 200);
                    foreach ($chunks as $chunk) {
                        $pgsql->table($table)->insert($chunk);
                    }
                }
                
                if (Schema::connection('pgsql')->hasColumn($table, 'id')) {
                    try {
                        $pgsql->statement("SELECT setval(pg_get_serial_sequence('\"{$table}\"', 'id'), COALESCE(MAX(id), 1) + 1, false) FROM \"{$table}\";");
                    } catch (\Exception $e) {
                        // Ignore sequence errors
                    }
                }
            }
            
            $pgsql->statement('SET session_replication_role = DEFAULT;');
            
            $this->info('Data migration complete!');
        } catch (\Exception $e) {
            $this->error('Migration failed: ' . $e->getMessage());
        }
    }
}
