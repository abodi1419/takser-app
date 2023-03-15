<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;

class GroupsExport implements FromView
{
    public function view(): View
    {
        return view('exports.groups', [
            'groups' => Auth::user()->groups,
        ]);
    }
}
