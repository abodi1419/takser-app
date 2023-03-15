<?php

use App\Models\TelegramSquential;
use Illuminate\Support\Facades\Route;
use NotificationChannels\Telegram\TelegramUpdates;
use \Maatwebsite\Excel\Facades\Excel;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get("user/edit",[\App\Http\Controllers\Controller::class, 'editAccount'])->name("user.edit");
Route::patch("user/update",[\App\Http\Controllers\Controller::class, 'updateAccount'])->name("user.update");

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::resource('groups',\App\Http\Controllers\GroupController::class);
Route::patch('groups/add/participant/{group}',[\App\Http\Controllers\GroupController::class,'addParticipant'])->name("groups.add.participant");
Route::get('join/group/{group}',[\App\Http\Controllers\GroupController::class,'joinView'])->name('groups.invitation');
Route::get('groups/join/{group}',[\App\Http\Controllers\GroupController::class,'join'])->name('groups.join');
Route::get('groups/reject/{group}',[\App\Http\Controllers\GroupController::class,'reject'])->name('groups.reject');
Route::get('groups/leave/{group}',[\App\Http\Controllers\GroupController::class,'leave'])->name('groups.leave');
Route::get('groups/leave/{user}/{group}',[\App\Http\Controllers\GroupController::class,'kick'])->name('groups.kick');

Route::resource('tasks',\App\Http\Controllers\TaskController::class);
Route::get('tasks/select/{task}',[\App\Http\Controllers\TaskController::class,'selectTask'])->name('tasks.select');
Route::get('tasks/deselect/{task}',[\App\Http\Controllers\TaskController::class,'deselectTask'])->name('tasks.deselect');
Route::get('tasks/complete/{task}',[\App\Http\Controllers\TaskController::class,'complete'])->name('tasks.complete');
Route::get('tasks/uncomplete/{task}',[\App\Http\Controllers\TaskController::class,'uncomplete'])->name('tasks.uncomplete');


Route::post('/42yUojv1YQPOssPEpn5i3q6vjdhh7hl7djVWDIAVhFDRMAwZ1tj0Og2v4PWyj4PZ/webhook', function (\Illuminate\Http\Request $request) {
    try {
        $text = $request['message']['text'];
        $chat_id = $request['message']['chat']['id'];
        $user = \App\Models\User::where('chat_id',$chat_id)->first();
        if($user!=null){
            if($text=="/start"){
                \NotificationChannels\Telegram\TelegramMessage::create("Welcome ".$user->name)->to($chat_id)->send();
            }elseif ($text=="/get_groups"){
                $text = "<strong>These are your groups: </strong>\n\n";
                $index=1;
                $index1=1;
                foreach ($user->groups as $group){
                    $text.=($index++).". ";
                    $text .= $group->name."\n\n";
                    $text.= "<strong>Tasks:</strong>\n\n";
                    foreach ($group->tasks as $task){
                        $text.="Task ".($index1++)."\n";
                        $text.= "Title:\n";
                        $text.="<strong>".$task->title."</strong>\n";
                        $text.= "Description:\n";
                        $text.= "<strong>".$task->description."</strong>\n";
                        $text.= "Start:\n";
                        $text.= "<strong>".date("d M, Y",strtotime($task->start))."</strong>"."\n";
                        $text.= "End:\n";
                        $text.= "<strong>".date("d M, Y",strtotime($task->end))."</strong>"."\n";
                        $text.="\n\n";
                    }
                    $text.="\n\n";
                }
                \NotificationChannels\Telegram\TelegramMessage::create($text)->to($chat_id)->options(['parse_mode' => 'HTML'])->send();
            }elseif ($text=="موسى"){
                $text="توكل";
                \NotificationChannels\Telegram\TelegramMessage::create($text)->to($chat_id)->options(['parse_mode' => 'HTML'])->send();

            }elseif ("/get_excel"){
                $file = Excel::download(new \App\Exports\GroupsExport, 'groups.xlsx');
                \NotificationChannels\Telegram\TelegramFile::create()->content("This is your excel")->file($file)->to($chat_id)->options(['parse_mode' => 'HTML'])->send();

            }
        }
        else{
            if($text=="/start"){
                \NotificationChannels\Telegram\TelegramMessage::create("You chat id is: ")->to($chat_id)->send();
                \NotificationChannels\Telegram\TelegramMessage::create($chat_id)->to($chat_id)->send();
            }else{
                \NotificationChannels\Telegram\TelegramMessage::create("Wrong choice!")->to($chat_id)->send();

            }
        }

    } catch (Exception $e) {
        return response()->json([
            'code'     => $e->getCode(),
            'message'  => 'Accepted with error: \'' . $e->getMessage() . '\'',
        ], 202);
    }

    \Illuminate\Support\Facades\Log::build([
        'driver' => 'single',
        'path' => storage_path('logs/webhook.log'),
    ])->info($request->all());

    return response('Success', 200);
});
Route::get("telegram",function (){
    $updates = TelegramUpdates::create()
        // (Optional). Get's the latest update. NOTE: All previous updates will be forgotten using this method.
//         ->latest()

        // (Optional). Limit to 2 updates (By default, updates starting with the earliest unconfirmed update are returned).
        ->limit(2)

        // (Optional). Add more params to the request.
        ->options([
//            'timeout' => 0,
//            'offset'=>260534366,
        ])
        ->get();

//    $latest = TelegramSquential::find(1);
//    if($latest==null){
//        $latest = TelegramSquential::create(['latest_id'=>0]);
//    }
    dd($updates['result']);
});

Route::middleware("auth")->get("get/excel",function (){
//    $groups = \Illuminate\Support\Facades\Auth::user()->groups;
//    return view('exports.groups',compact('groups'));
    return Excel::download(new \App\Exports\GroupsExport, 'groups.xlsx');

});

Route::get('change/language/{locale}',function ($locale){
    if(in_array($locale,['ar','en'])){
        \Illuminate\Support\Facades\App::setLocale($locale);
        // Session
        session()->put('locale', $locale);

        return redirect()->back();
    }else{
        abort(404,'Language not found!');
    }
})->name('language.change');
