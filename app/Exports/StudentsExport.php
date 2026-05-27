<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Student::with('UserAccount', 'degree')->get()->map(function($s){
            return [
                'ID' => $s->id,
                'Username' => optional($s->UserAccount)->username,
                'Email' => $s->Email,
                'First Name' => $s->Fname,
                'Middle Name' => $s->Mname,
                'Last Name' => $s->Lname,
                'Degree' => optional($s->degree)->name,
                'Contact' => $s->Contactno,
                'Created At' => $s->created_at ? $s->created_at->toDateTimeString() : null,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID', 'Username', 'Email', 'First Name', 'Middle Name', 'Last Name', 'Degree', 'Contact', 'Created At'
        ];
    }
}
