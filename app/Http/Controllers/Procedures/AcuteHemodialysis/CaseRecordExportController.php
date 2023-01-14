<?php

namespace App\Http\Controllers\Procedures\AcuteHemodialysis;

use App\Actions\Procedures\AcuteHemodialysis\CaseRecordExportAction;
use App\Http\Controllers\Controller;
use App\Traits\FirstNameAware;
use Illuminate\Http\Request;
use OpenSpout\Common\Exception\InvalidArgumentException;
use OpenSpout\Common\Exception\IOException;
use OpenSpout\Common\Exception\UnsupportedTypeException;
use OpenSpout\Writer\Exception\WriterNotOpenedException;

class CaseRecordExportController extends Controller
{
    use FirstNameAware;

    /**
     * @throws IOException
     * @throws WriterNotOpenedException
     * @throws UnsupportedTypeException
     * @throws InvalidArgumentException
     */
    public function __invoke(Request $request, CaseRecordExportAction $action)
    {
        return $action($request->only(['scope', 'search']), $request->user());
    }
}
