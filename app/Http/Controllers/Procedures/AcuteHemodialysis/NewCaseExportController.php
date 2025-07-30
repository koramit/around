<?php

namespace App\Http\Controllers\Procedures\AcuteHemodialysis;

use App\Actions\Procedures\AcuteHemodialysis\ReportNewCaseRecordAction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use OpenSpout\Common\Exception\InvalidArgumentException;
use OpenSpout\Common\Exception\IOException;
use OpenSpout\Common\Exception\UnsupportedTypeException;
use OpenSpout\Writer\Exception\WriterNotOpenedException;
use Rap2hpoutre\FastExcel\FastExcel;

class NewCaseExportController
{
    /**
     * @throws IOException
     * @throws WriterNotOpenedException
     * @throws UnsupportedTypeException
     * @throws InvalidArgumentException
     */
    public function __invoke(Request $request, ReportNewCaseRecordAction $action)
    {
        $data = $action(
            ['ref_date' => $request->input('ref_date', Carbon::now()->format('Y-m-d'))],
            $request->user()
        );

        if ($request->wantsJson()) {
            return $data;
        }

        return (new FastExcel($data['sheet']))->download($data['filename']);
    }
}
