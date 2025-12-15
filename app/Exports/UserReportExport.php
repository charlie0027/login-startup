<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class UserReportExport implements FromView
{

    public function view(): View
    {
        $query = User::with(['userDetail.roles.permissions'])->get();
        // dd($query->first()->userDetail->roles);
        return view('exports.user_summary', [
            'query' => $query
        ]);
    }
}
