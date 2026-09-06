<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CollectionsExport;
use App\Exports\CustomersExport;
use App\Exports\FinancialExport;
use App\Exports\WasteExport;
use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    private const TYPES = ['customers', 'collections', 'waste', 'financial'];

    public function __construct(private ReportService $reports)
    {
    }

    public function index(): View
    {
        $this->authorize('manage-settings');

        return view('admin.reports.index');
    }

    public function show(Request $request, string $type): View
    {
        $this->authorize('manage-settings');

        abort_unless(in_array($type, self::TYPES), 404);

        [$from, $to] = $this->resolveRange($request);
        $data = $this->reports->{$type}($from, $to);

        return view("admin.reports.{$type}", array_merge($data, [
            'from' => $from,
            'to' => $to,
        ]));
    }

    public function export(Request $request, string $type, string $format): BinaryFileResponse|StreamedResponse|\Illuminate\Http\Response
    {
        $this->authorize('manage-settings');

        abort_unless(in_array($type, self::TYPES), 404);
        abort_unless(in_array($format, ['pdf', 'excel']), 404);

        [$from, $to] = $this->resolveRange($request);
        $data = $this->reports->{$type}($from, $to);
        $filename = "{$type}-report-{$from->format('Y-m-d')}-to-{$to->format('Y-m-d')}";

        if ($format === 'excel') {
            $exportClass = match ($type) {
                'customers' => CustomersExport::class,
                'collections' => CollectionsExport::class,
                'waste' => WasteExport::class,
                'financial' => FinancialExport::class,
            };

            return Excel::download(new $exportClass($data['rows']), "{$filename}.xlsx");
        }

        $pdf = Pdf::loadView("admin.reports.pdf.{$type}", array_merge($data, ['from' => $from, 'to' => $to]));

        return $pdf->download("{$filename}.pdf");
    }

    private function resolveRange(Request $request): array
    {
        $from = $request->filled('from') ? Carbon::parse($request->date('from')) : now()->startOfMonth();
        $to = $request->filled('to') ? Carbon::parse($request->date('to')) : now()->endOfMonth();

        return [$from, $to];
    }
}
