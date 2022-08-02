<?php

namespace App\Http\Controllers\Procedures\AcuteHemodialysis;

use App\Actions\Procedures\AcuteHemodialysis\OrderExportAction;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use OpenSpout\Common\Exception\InvalidArgumentException;
use OpenSpout\Common\Exception\IOException;
use OpenSpout\Common\Exception\UnsupportedTypeException;
use OpenSpout\Writer\Exception\WriterNotOpenedException;
use Rap2hpoutre\FastExcel\FastExcel;
use Rap2hpoutre\FastExcel\SheetCollection;

class OrderExportController extends Controller
{
    /**
     * @throws WriterNotOpenedException
     * @throws IOException
     * @throws UnsupportedTypeException
     * @throws InvalidArgumentException
     */
    public function __invoke(Request $request, User $user)
    {
        $sheets = new SheetCollection((new OrderExportAction)($request->input('date_note'), $request->user()));

        return (new FastExcel($sheets))->download("acute_hd_order_{$request->input('date_note')}.xlsx");
    }
}
