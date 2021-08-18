<?php

namespace HDSSolutions\Laravel\Http\Controllers;

use App\Http\Controllers\Controller;
use HDSSolutions\Laravel\DataTables\BankAccountDataTable as DataTable;
use HDSSolutions\Laravel\Http\Request;
use HDSSolutions\Laravel\Models\BankAccount as Resource;
use HDSSolutions\Laravel\Models\Bank;
use HDSSolutions\Laravel\Models\Check;
use HDSSolutions\Laravel\Models\CashLine;

class BankAccountController extends Controller {

    public function __construct() {
        // check resource Policy
        $this->authorizeResource(Resource::class, 'resource');
    }

    public function index(Request $request, DataTable $dataTable) {
        // check only-form flag
        if ($request->has('only-form'))
            // redirect to popup callback
            return view('backend::components.popup-callback', [ 'resource' => new Resource ]);

        // load resources
        if ($request->ajax()) return $dataTable->ajax();

        // get available banks
        $banks = Bank::ordered()->get();

        // return view with dataTable
        return $dataTable->render('banking::bank_accounts.index', compact('banks') + [
            'count'                 => Resource::count(),
            'show_company_selector' => !backend()->companyScoped(),
        ]);
    }

    public function create(Request $request) {
        // force company selection
        if (!backend()->companyScoped()) return view('backend::layouts.master', [ 'force_company_selector' => true ]);

        // load banks
        $banks = Bank::all();

        // show create form
        return view('banking::bank_accounts.create', compact('banks'));
    }

    public function store(Request $request) {
        // create resource
        $resource = new Resource( $request->input() );

        // save resource
        if (!$resource->save())
            // redirect with errors
            return back()->withInput()
                ->withErrors( $resource->errors() );

        // check return type
        return $request->has('only-form') ?
            // redirect to popup callback
            view('backend::components.popup-callback', compact('resource')) :
            // redirect to resource details
            redirect()->route('backend.bank_accounts.show', $resource);
    }

    public function show(Request $request, Resource $resource) {
        // load data
        $resource->load([
            'bank',
            'movements' => fn($movement) => $movement
                ->with([
                    'referable' => fn($referable) => $referable->with([]),
                ]),
        ]);
        // load movements referables polymorphic relations
        $resource->movements->loadMorph('referable', [
            // TransferIn/Out refers to BankAccount
            Resource::class     => [ 'bank' ],
            // CashDeposit refers to CashLine
            CashLine::class     => [ 'cash.cashBook' ],
            // CheckDeposit refers to DepositSlip
            Check::class        => [ 'bank' ],
        ]);
        $resource->setRelation('movements', $resource->movements->transform(fn($movement) =>
            // set Movement.bankAccount relation manually to avoid more queries
            $movement->setRelation('bankAccount', $resource)
        ));

        // redirect to list
        return view('banking::bank_accounts.show', compact('resource'));
    }

    public function edit(Request $request, Resource $resource) {
        // load banks
        $banks = Bank::all();

        // show edit form
        return view('banking::bank_accounts.edit', compact('banks', 'resource'));
    }

    public function update(Request $request, Resource $resource) {
        // save resource
        if (!$resource->update( $request->input() ))
            // redirect with errors
            return back()->withInput()
                ->withErrors( $resource->errors() );

        // redirect to list
        return redirect()->route('backend.bank_accounts');
    }

    public function destroy(Request $request, Resource $resource) {
        // delete resource
        if (!$resource->delete())
            // redirect with errors
            return back()
                ->withErrors( $resource->errors() );

        // redirect to list
        return redirect()->route('backend.bank_accounts');
    }

}
