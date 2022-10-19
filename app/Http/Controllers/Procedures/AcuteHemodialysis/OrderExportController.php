<?php

namespace App\Http\Controllers\Procedures\AcuteHemodialysis;

use App\Actions\Procedures\AcuteHemodialysis\OrderExportAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OpenSpout\Common\Exception\InvalidArgumentException;
use OpenSpout\Common\Exception\IOException;
use OpenSpout\Common\Exception\UnsupportedTypeException;
use OpenSpout\Writer\Exception\WriterNotOpenedException;

class OrderExportController extends Controller
{
    /**
     * @throws WriterNotOpenedException
     * @throws IOException
     * @throws UnsupportedTypeException
     * @throws InvalidArgumentException
     */
    public function __invoke(Request $request, OrderExportAction $action)
    {
        return $action($request->input('date_note'), $request->user());
    }
}
