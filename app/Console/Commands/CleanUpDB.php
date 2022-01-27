<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Exception;
use DB;
use Carbon\Carbon;

class CleanUpDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'DB:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Move Old Data To Another Database at 2:15 am at a time in 24 hours';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            DB::transaction(function(){
                // Copy data from sys_email_pool to skoda_docyard.sys_email.pool
                // Consider entries with 5 or more attempts from stellar_skoda_db.sys_email_pool
                // $max_attempt = config('CONSTANT.MAX_EMAIL_ATTEMPT');
                // $emailNotifications = SysEmailPool::where('attempts','>=',$max_attempt)->get()->toArray();
                // if($emailNotifications){
                //     SysEmailPool::on('mysql_backup')->insert($emailNotifications);
                //     $delatable_email_pool_ids = array_column($emailNotifications, 'id');
                //     SysEmailPool::whereIn('id',$delatable_email_pool_ids)->delete();
                // }
            });
        } catch(Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
