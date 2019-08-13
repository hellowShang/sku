<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//        $schedule->call(function(){
//            DB::table("student")->delete();
//        })->everyMinute();
        $schedule->call(function(){
            $response = $this->getOpenId();
            file_put_contents("/wwwroot/month12/laravel/logs/weixin.log",$response."\n\r",FILE_APPEND);
        })->everyMinute();

//        $schedule->command("echo:time")->everyMinute();
//        $schedule->exec('curl "http://wechar.lab993.com/send"')->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    protected function getOpenId(){
        // 获取openId
        $openid = DB::table("wechar_userinfo")->select("openid")->get();
        $openid = array_column(json_decode(json_encode($openid),true),'openid');

        // 群发内容
        $content = file_get_contents('/wwwroot/month12/laravel/public/text.log');
        $arr2 = explode(',',$content);
        $num = rand(1,176);
        $data = json_encode([
            "touser" => [
                $openid
            ],
            "msgtype" => 'text',
            "text" => [
                "content" => trim($arr2[$num])
            ]
        ],JSON_UNESCAPED_UNICODE);

        // 群发
        $url = "https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=".env('ACCESS_TOKEN');

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        curl_setopt($ch,CURLOPT_HTTPHEADER,['Content-type:text/plain']);

        $num = curl_errno($ch);
        if($num>0){
            echo "CURL错误码：".$num;exit;
        }

        $str = curl_exec($ch);

        return $str;

        curl_close($ch);
    }
}
