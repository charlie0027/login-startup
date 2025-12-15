<?php

namespace App\Http\Controllers\Reports;

use App\Exports\UserReportExport;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\UserDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\JcTable;
use QuickChart;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Converter;

class UserReportController extends Controller
{
    public function index()
    {
        $summary_users = User::with('userDetail.roles.permissions')->paginate(12);

        return Inertia::render('Reports/UserSummary', [
            'summary_users' => $summary_users,
        ]);
    }

    public function export_user_summary()
    {
        // dd("Hello world");
        return Excel::download(new UserReportExport(), 'user_summary.xlsx');
    }

    public function export_user_summary_pdf()
    {
        $chartBase64 = $this->generateChart();


        $query = User::with(['userDetail.roles.permissions'])->get();

        // Render a Blade view into HTML
        $html = view('exports.user_summary_pdf', compact('query', 'chartBase64'))->render();

        // Load the HTML into DomPDF
        $pdf = Pdf::loadHTML($html)->setPaper('a4', 'portrait')->setWarnings(false);

        // Stream it back to the browser
        return $pdf->stream('user_summary.pdf');
    }

    public function export_user_summary_word()
    {
        $query = User::with(['userDetail.roles.permissions'])->get();
        $chartBase64 = $this->generateChart();

        $phpWord = new PhpWord();

        //600 ≈ 0.5 inch, 2000 ≈ ~1.7 inch,, section is the whole page

        $section = $phpWord->addSection([
            'pageSizeW' => Converter::inchToTwip(8.27), // width in inches
            'pageSizeH' => Converter::inchToTwip(11.69),  // height in inches
            // 'orientation' => 'portrait', // or 'landscape'
            'marginTop'    => 0,   // distance from top of page to body (twips)
            'marginBottom' => 0,   // distance from bottom of page to body
            'marginLeft'   => 1200,
            'marginRight'  => 1200,
            'headerHeight' => 1200,  // space reserved for header
            'footerHeight' => 500,   // space reserved for footer
        ]);

        // <w:br/> breakline
        $header = $section->addHeader();
        // $header->addText(
        //     "THIS IS MY APP <w:br/> Sample App <w:br/> charlielomiwesd@gmail.com | 09777759113",
        //     [
        //         'spaceBefore' => 0,
        //         'spaceAfter'  => 0,
        //         'lineHeight'  => 1.0, // optional: tighter line spacing
        //         'bold' => true,
        //         'marginRight' => 0,
        //         'marginLeft' => 0,
        //     ],
        //     [
        //         'alignment'   => 'center',
        //     ]
        // );

        // Create a 3-column table
        $table = $header->addTable([
            'alignment' => JcTable::CENTER,
            'cellMargin' => 0,
            'borderSize' => 0,
        ]);

        $table->addRow();

        // LEFT CELL: Sample Logo
        $leftCell = $table->addCell(Converter::inchToTwip(1.0));
        $leftCell->addImage(public_path('img/sample_logo.png'), [
            'width' => 60,
            'height' => 60,
            'alignment' => 'left',
        ]);

        // CENTER CELL: stacked text
        $centerCell = $table->addCell(Converter::inchToTwip(5.5));
        $centerRun = $centerCell->addTextRun(['alignment' => 'center']);
        $centerRun->addText('THIS IS MY APP <w:br/> Sample App <w:br/> charlielomiwesd@gmail.com | 09777759113', [
            'spaceBefore' => 0,
            'spaceAfter'  => 0,
            // 'lineHeight'  => 1.0, // optional: tighter line spacing
            'bold' => true,
            'marginRight' => 0,
            'marginLeft' => 0,
        ], [
            'alignment' => 'center'
        ]);

        // RIGHT CELL: Another Sample Logo
        $rightCell = $table->addCell(Converter::inchToTwip(1.0));
        $rightCell->addImage(public_path('img/sample_logo.png'), [
            'width' => 60,
            'height' => 60,
            'alignment' => 'right',
        ]);


        $footer = $section->addFooter();
        $footer->addText('Sample Footer (c) 2025', [
            'marginRight' => 0,
            'marginLeft' => 0,
        ], ['alignment' => 'center']);

        $section->addText('User Summary Report', ['bold' => true], ['alignment' => 'center']);
        $section->addListItem('Below are the list of Users together with its respective Roles and Permissions');

        $table = $section->addTable(
            [
                'borderSize' => 6,
                'borderColor' => '999999',
                'cellMargin' => 80,
            ],
        );

        // Add a header row
        $table->addRow();
        $table->addCell(3000)->addText('Name', ['bold' => true], ['alignment' => 'center']);
        $table->addCell(3000)->addText('Email', ['bold' => true], ['alignment' => 'center']);
        $table->addCell(3000)->addText('Username', ['bold' => true], ['alignment' => 'center']);
        $table->addCell(3000)->addText('Role', ['bold' => true], ['alignment' => 'center']);
        $table->addCell(3000)->addText('Permisssions', ['bold' => true], ['alignment' => 'center']);

        foreach ($query as $data) {
            // Add a data row
            $table->addRow();
            $table->addCell(3000)->addText(strtoupper($data->last_name) . ', ' . strtoupper($data->first_name) . ' ' . strtoupper($data->middle_name) . ' ' . strtoupper($data->name_extension));
            $table->addCell(3000)->addText($data->email);
            $table->addCell(3000)->addText($data->username);

            $role_cell = $table->addCell(3000);
            $roles = $data->userDetail?->roles ?? [];
            $roleNames = collect($roles)->pluck('role_name')->implode(', ');
            $role_cell->addText($roleNames);


            $permission_cell = $table->addCell(3000);
            $permissions = collect($data->userDetail?->roles ?? [])
                ->flatMap(fn($role) => $role->permissions ?? [])
                ->pluck('name')
                ->unique()
                ->implode(', ');
            $permission_cell->addText($permissions);
        }

        $section->addPageBreak();

        $section->addText('Gender Per User', ['bold' => true], ['alignment' => 'center']);
        $chartData = str_replace('data:image/png;base64,', '', $chartBase64);
        // Decode the base64 string
        $chartBinary = base64_decode($chartData);
        $section->addImage(
            $chartBinary,
            [
                'width' => 400,
                'height' => 200,
                'alignment' => 'center'
            ],
        );

        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');

        // Clear output buffer to avoid corruption
        ob_end_clean();

        // Set headers so browser treats it as a download
        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="user_summary.docx"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');

        // Stream directly to browser
        $objWriter->save("php://output");
        exit;
    }

    private function generateChart()
    {
        $counts = UserDetail::select('gender', DB::raw('COUNT(*) as total_count'))
            ->groupBy('gender')->get();

        $labels = [];
        $data = [];

        foreach ($counts as $row) {
            if ($row->gender == 0) {
                $labels[] = 'Female';
            } elseif ($row->gender == 1) {
                $labels[] = 'Male';
            } else {
                $labels[] = 'Not Set';
            }
            $data[] = $row->total_count;
        }

        $config = [
            'type' => 'pie',
            'data' => [
                'labels' => $labels,
                'datasets' => [[
                    'data' => $data,
                    'backgroundColor' => ['#36A2EB', '#FF6384', '#FFCE56'],
                ]]
            ],
            'options' => [
                'plugins' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Gender Distribution'
                    ]
                ]
            ]
        ];

        $qc = new QuickChart();
        $qc->setWidth(400);
        $qc->setHeight(200);
        $qc->setConfig(json_encode($config));
        return 'data:image/png;base64,' . base64_encode($qc->toBinary());
    }
}
