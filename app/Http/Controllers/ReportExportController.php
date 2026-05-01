<?php

namespace App\Http\Controllers;

use App\Services\ReportExportService;
use Illuminate\Http\Request;

class ReportExportController extends Controller
{
    public function export(Request $request, ReportExportService $service)
    {
        $request->validate([
            'format' => 'required|in:pdf,excel',
            'start' => 'required|date_format:Y-m',
            'end' => 'required|date_format:Y-m|after_or_equal:start',
            'user' => 'nullable|integer',
            'categories' => 'nullable|array',
            'categories.*' => 'in:income,expense,fund',
        ]);

        $format = $request->input('format');
        $startMonth = $request->input('start');
        $endMonth = $request->input('end');
        $userId = $request->input('user');
        $categories = $request->input('categories', ['income', 'expense', 'fund']);

        if ($format === 'pdf') {
            return $service->exportPdf($startMonth, $endMonth, $userId, $categories);
        }

        return $service->exportExcel($startMonth, $endMonth, $userId, $categories);
    }
}
