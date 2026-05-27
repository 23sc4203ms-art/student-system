<?php

namespace App\Exports;

use App\Models\UserAccount;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TeachersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return UserAccount::where('Role', 'teacher')->get()->map(function($u){
            return [
                'ID' => $u->id,
                'Username' => $u->username,
                'Email' => $u->email,
                'Created At' => $u->created_at ? $u->created_at->toDateTimeString() : null,
            ];
        });
    }

    public function headings(): array
    {
        return ['ID', 'Username', 'Email', 'Created At'];
    }
}
