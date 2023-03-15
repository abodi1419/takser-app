<?php

namespace App\Exports;

use App\Models\Group;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class GroupsExportExample implements ToModel
{

    public function model(array $row)
    {

    }
}
