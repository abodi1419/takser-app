<?php

namespace App\Http\Controllers;

use http\Env\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function editAccount(){
        $user = Auth::user();
        return view("auth.edit", compact('user'));
    }

    public function updateAccount(\Illuminate\Http\Request $request){
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'chat_id' => ['nullable', 'numeric'],
        ]);
        if($request->has('password')&&$request->password != null){
            $request->validate([
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);
            Auth::user()->password=bcrypt($request->password);
        }

        Auth::user()->name = $validatedData['name'];
        Auth::user()->chat_id = $validatedData['chat_id'];
        Auth::user()->save();
        return redirect()->back()->with(['message'=>"Updated successfully"]);
    }
}
