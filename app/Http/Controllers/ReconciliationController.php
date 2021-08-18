<?php

namespace HDSSolutions\Laravel\Http\Controllers;

use App\Http\Controllers\Controller;
use HDSSolutions\Laravel\DataTables\ReconciliationDataTable as DataTable;
use HDSSolutions\Laravel\Http\Request;
use HDSSolutions\Laravel\Models\Reconciliation as Resource;
use HDSSolutions\Laravel\Models\ReconciliationCheck;
use HDSSolutions\Laravel\Models\Check;
use HDSSolutions\Laravel\Traits\CanProcessDocument;
use Illuminate\Support\Facades\DB;

class ReconciliationController extends Controller {
    use CanProcessDocument;

    public function __construct() {
        // check resource Policy
        $this->authorizeResource(Resource::class, 'resource');
    }

    protected function documentClass():string {
        // return class
        return Resource::class;
    }

    protected function redirectTo():string {
        // go to resource view
        return 'backend.reconciliations.show';
    }

    public function index(Request $request, DataTable $dataTable) {
        // check only-form flag
        if ($request->has('only-form'))
            // redirect to popup callback
            return view('backend::components.popup-callback', [ 'resource' => new Resource ]);

        // load resources
        if ($request->ajax()) return $dataTable->ajax();

        // return view with dataTable
        return $dataTable->render('banking::reconciliations.index', [
            'count'                 => Resource::count(),
            'show_company_selector' => !backend()->companyScoped(),
        ]);
    }

    public function create(Request $request) {
        // force company selection
        if (!backend()->companyScoped()) return view('backend::layouts.master', [ 'force_company_selector' => true ]);

        // load deposited checks that aren't paid
        $checks = Check::deposited()->paid(false)->get();

        $highs = [
            'document_number'   => Resource::nextDocumentNumber(),
        ];

        // show create form
        return view('banking::reconciliations.create', compact(
            'checks',
            'highs',
        ));
    }

    public function store(Request $request) {
        // start a transaction
        DB::beginTransaction();

        // create resource
        $resource = new Resource( $request->input() );
        // save resource
        if (!$resource->save())
            // redirect with errors
            return back()->withInput()
                ->withErrors( $resource->errors() );

        // sync resource checks
        if ($request->has('checks')) $resource->checks()->sync(
            // get checks as collection
            $checks = collect($request->get('checks'))
                // filter empty checks
                ->filter(fn($check) => $check !== null)
            );

        // confirm transaction
        DB::commit();

        // check return type
        return $request->has('only-form') ?
            // redirect to popup callback
            view('backend::components.popup-callback', compact('resource')) :
            // redirect to resource details
            redirect()->route('backend.reconciliations.show', $resource);
    }

    public function show(Request $request, Resource $resource) {
        // load receipment data
        $resource->load([
            'checks' => fn($check) => $check->with([
                'bank',
                'movement',
            ]),
        ]);

        // redirect to list
        return view('banking::reconciliations.show', compact('resource'));
    }

    public function edit(Request $request, Resource $resource) {
        // check if document is already approved or processed
        if ($resource->isApproved() || $resource->isProcessed())
            // redirect to show route
            return redirect()->route('backend.reconciliations.show', $resource);

        // load deposited checks that aren't paid
        $checks = Check::deposited()->paid(false)->get();

        // load resource relations
        $resource->load([
            'checks',
        ]);

        // show edit form
        return view('banking::reconciliations.edit', compact('resource',
            'checks',
        ));
    }

    public function update(Request $request, Resource $resource) {
        // start a transaction
        DB::beginTransaction();

        // save resource
        if (!$resource->update( $request->input() ))
            // redirect with errors
            return back()->withInput()
                ->withErrors( $resource->errors() );

        // sync resource checks
        if ($request->has('checks')) $resource->checks()->sync(
            // get checks as collection
            $checks = collect($request->get('checks'))
                // filter empty checks
                ->filter(fn($check) => $check !== null)
            );

        // confirm transaction
        DB::commit();

        // redirect to resource details
        return redirect()->route('backend.reconciliations.show', $resource);
    }

    public function destroy(Request $request, Resource $resource) {
        // delete resource
        if (!$resource->delete())
            // redirect with errors
            return back()
                ->withErrors($resource->errors()->any() ? $resource->errors() : [ $resource->getDocumentError() ]);

        // redirect to list
        return redirect()->route('backend.reconciliations');
    }

}
