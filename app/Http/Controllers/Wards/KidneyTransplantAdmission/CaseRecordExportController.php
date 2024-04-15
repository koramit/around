<?php

namespace App\Http\Controllers\Wards\KidneyTransplantAdmission;

use App\Actions\Wards\KidneyTransplantAdmission\CaseRecordExportAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OpenSpout\Common\Exception\InvalidArgumentException;
use OpenSpout\Common\Exception\IOException;
use OpenSpout\Common\Exception\UnsupportedTypeException;
use OpenSpout\Writer\Exception\WriterNotOpenedException;

class CaseRecordExportController extends Controller
{
    /**
     * @throws WriterNotOpenedException
     * @throws IOException
     * @throws UnsupportedTypeException
     * @throws InvalidArgumentException
     */
    public function __invoke(Request $request, CaseRecordExportAction $action)
    {
        return $action($request->user());
    }
}
