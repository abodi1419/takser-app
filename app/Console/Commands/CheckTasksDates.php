<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Models\TelegramSquential;
use Illuminate\Console\Command;
use NotificationChannels\Telegram\TelegramUpdates;

class CheckTasksDates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
//        $tasksStarted->update(['status'=>2]);
        try {
            $tasksOverdue = Task::where("end","<=",date("Y-m-d H:i"))->whereNot("status",'=',3)->update(['status'=>4]);
            $tasksStarted = Task::where("start",'<=',date("Y-m-d H"))->where("end",">=",date("Y-m-d H"))->where("status","=",1)->update(['status'=>'2']);

//        $tasksOverdue->update(['status'=>'4']);
//            Task::updateMany($tasksOverdue,);
        }catch (\Exception $e){
            \Illuminate\Support\Facades\Log::build([
                'driver' => 'single',
                'path' => storage_path('logs/tasks-schedule.log'),
            ])->info($e->getMessage());
        }

        return Command::SUCCESS;
    }
}
