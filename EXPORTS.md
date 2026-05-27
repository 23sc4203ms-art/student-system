Export setup and usage
======================

This project now includes server-side endpoints and helper classes to export Students and Teachers as Excel and PDF files.

Required Composer packages
- `maatwebsite/excel` (for Excel exports)
- `barryvdh/laravel-dompdf` (for PDF generation)

Install them with:

```bash
composer require maatwebsite/excel
composer require barryvdh/laravel-dompdf
```

Notes:
- Both packages support Laravel package auto-discovery on modern Laravel versions. If your app uses manual registration, add the service providers and aliases as the packages document.
- `StudentsExport` and `TeachersExport` classes are under `app/Exports/` and are used by the `TeacherController` export methods.
- PDF views are under `resources/views/teacher/exports/` (`students_pdf.blade.php`, `teachers_pdf.blade.php`). The PDF generator uses these views.

Routes
- Excel: `/teacher/management/export/students/excel` and `/teacher/management/export/teachers/excel`
- PDF: `/teacher/management/export/students/pdf` and `/teacher/management/export/teachers/pdf`

Blade UI
- Export buttons were added to `resources/views/teacher/management.blade.php`.

Troubleshooting
- If downloads fail, check the web server logs and ensure the packages are installed. For DomPDF, ensure fonts are available if you need non-latin characters.
