<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class EchoDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'echo:time';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'echo beijing now time';

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
     * @return mixed
     */
    public function handle()
    {
        // 输出当前时间 并写入日志中
        $time = date("Y-m-d H:i:s",time());
        echo $time."\n";
        is_dir("logs") or mkdir("logs",0777,true);
        file_put_contents("logs/time.log",$time."\n\r",FILE_APPEND);
    }
}
