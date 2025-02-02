<?php

namespace App\Http\Controllers\Clinics\PostKT;

use App\Actions\Clinics\PostKT\AnnualUpdateAction;
use App\Actions\Clinics\PostKT\CaseDestroyAction;
use App\Actions\Clinics\PostKT\CaseEditAction;
use App\Actions\Clinics\PostKT\CaseIndexAction;
use App\Actions\Clinics\PostKT\CaseIndexByMothAction;
use App\Actions\Clinics\PostKT\CaseStoreAction;
use App\Actions\Clinics\PostKT\CaseUpdateAction;
use App\Actions\Clinics\PostKT\ExportFUSchedule;
use App\Actions\Clinics\PostKT\ExportSummaryCases;
use App\Actions\Clinics\PostKT\PrintCaseFolderLabel;
use App\Actions\Clinics\PostKT\PrintCaseFrontCover;
use App\Actions\Clinics\PostKT\ShowCase;
use App\Actions\Clinics\PostKT\TimestampUpdateAction;
use App\Actions\Clinics\PostKT\TimestampUpdateByCrAction;
use App\Http\Controllers\Controller;
use App\Traits\AppLayoutSessionFlashable;
use Illuminate\Http\Request;
use Inertia\Inertia;
use OpenSpout\Common\Exception\InvalidArgumentException;
use OpenSpout\Common\Exception\IOException;
use OpenSpout\Common\Exception\UnsupportedTypeException;
use OpenSpout\Writer\Exception\WriterNotOpenedException;
use Rap2hpoutre\FastExcel\FastExcel;

class CaseRecordController extends Controller
{
    use AppLayoutSessionFlashable;

    public function __construct(Request $request)
    {
        if (! $request->wantsJson()) {
            $this->middleware(['remember', 'page-transition', 'locale', 'no-in-app-allow'])->only(['index']);
            $this->middleware(['page-transition', 'locale', 'no-in-app-allow'])->only(['edit']);
        }
    }

    public function index(Request $request, CaseIndexAction $action)
    {
        $data = $action($request->all(), $request->user(), $request->route()->getName());

        if ($request->wantsJson()) {
            return $data;
        }

        $this->setFlash($data['flash']);
        unset($data['flash']);

        return Inertia::render('Clinics/PostKT/Index', [...$data]);
    }

    public function store(Request $request, CaseStoreAction $action)
    {
        $case = $action($request->all(), $request->user());

        if ($request->wantsJson()) {
            return $case;
        }

        return redirect()->route('clinics.post-kt.edit', $case['key']);
    }

    public function edit(string $hashedKey, Request $request, CaseEditAction $action)
    {
        $data = $action($hashedKey, $request->user());

        if ($request->wantsJson()) {
            return $data;
        }

        $this->setFlash($data['flash']);
        unset($data['flash']);

        return Inertia::render('Clinics/PostKT/Edit', [...$data]);
    }

    public function update(string $hashedKey, Request $request, CaseUpdateAction $action)
    {
        $message = $action($hashedKey, $request->all(), $request->user());

        if ($request->wantsJson()) {
            return $message;
        }

        return redirect()->route('clinics.post-kt.index')->with('message', $message);
    }

    public function destroy(string $hashedKey, Request $request, CaseDestroyAction $action)
    {
        $message = $action($hashedKey, $request->user());

        if ($request->wantsJson()) {
            return $message;
        }

        return redirect()->route('clinics.post-kt.index')->with('message', $message);
    }

    public function annualUpdate(string $hashedKey, Request $request, AnnualUpdateAction $action)
    {
        $data = $action($hashedKey, $request->user());

        if ($request->wantsJson() || $request->method() == 'POST') {
            return $data;
        }

        return redirect()->route('clinics.post-kt.edit', $hashedKey);
    }

    public function annualUpdateByCr(string $hashedKey, Request $request, AnnualUpdateAction $action)
    {
        $data = $action($hashedKey, $request->user(), true);

        if ($request->wantsJson()) {
            return $data;
        }

        return redirect()->route('clinics.post-kt.edit', $hashedKey);
    }

    public function timestampUpdate(string $hashedKey, Request $request, TimestampUpdateAction $action)
    {
        $message = $action($hashedKey, $request->all(), $request->user());

        if ($request->wantsJson()) {
            return $message;
        }

        return redirect()->route('clinics.post-kt.index')->with('message', $message);
    }

    public function timestampUpdateByCr(string $hashedKey, Request $request, TimestampUpdateByCrAction $action)
    {
        $data = $action($hashedKey, $request->user());

        if ($request->wantsJson()) {
            return $data;
        }

        return redirect()->route('clinics.post-kt.edit', $hashedKey);
    }

    public function monthCases(string $month, Request $request, CaseIndexByMothAction $action)
    {
        return $action($month, $request->user());
    }

    public function show(string $hashedKey, Request $request, ShowCase $action)
    {
        return $action($hashedKey, $request->user());
    }

    /**
     * @throws IOException
     * @throws WriterNotOpenedException
     * @throws UnsupportedTypeException
     * @throws InvalidArgumentException
     */
    public function fuSchedule(string $hashedKey, Request $request, ExportFUSchedule $action)
    {
        $data = $action($hashedKey, $request->user());
        if ($request->wantsJson()) {
            return $data;
        }
        return (new FastExcel($data['sheet']))->download($data['filename']);
    }

    public function printFrontCover(string $hashedKey, Request $request, PrintCaseFrontCover $action)
    {
        $data = $action($hashedKey, $request->user());

        return Inertia::render('Clinics/PostKT/Printout/CaseFrontCover', ['data' => $data]);
    }

    public function printFolderLabel(string $hashedKey, Request $request, PrintCaseFolderLabel $action)
    {
        $data = $action($hashedKey, $request->user());

        if ($request->wantsJson()) {
            return $data;
        }

        $this->setFlash($data['flash']);

        return Inertia::render('Clinics/PostKT/Printout/CaseFolderLabel', ['data' => $data['data']]);
    }

    /**
     * @throws IOException
     * @throws WriterNotOpenedException
     * @throws UnsupportedTypeException
     * @throws InvalidArgumentException
     */
    public function exportSummaryCases(Request $request, ExportSummaryCases $action)
    {
        $data = $action($request->user());
        if ($request->wantsJson()) {
            return $data;
        }

        return (new FastExcel($data['sheet']))->download($data['filename']);
    }
}
