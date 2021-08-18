<?php

namespace HDSSolutions\Laravel\Http\Controllers;

use App\Http\Controllers\Controller;
use HDSSolutions\Laravel\DataTables\DepositSlipDataTable as DataTable;
use HDSSolutions\Laravel\Http\Request;
use HDSSolutions\Laravel\Models\DepositSlip as Resource;
use HDSSolutions\Laravel\Models\DepositSlipCheck;
use HDSSolutions\Laravel\Models\BankAccount;
use HDSSolutions\Laravel\Models\CashBook;
use HDSSolutions\Laravel\Models\Cash;
use HDSSolutions\Laravel\Models\Check;
use HDSSolutions\Laravel\Traits\CanProcessDocument;
use Illuminate\Support\Facades\DB;

class DepositSlipController extends Controller {
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
        return 'backend.deposit_slips.show';
    }

    public function index(Request $request, DataTable $dataTable) {
        // check only-form flag
        if ($request->has('only-form'))
            // redirect to popup callback
            return view('backend::components.popup-callback', [ 'resource' => new Resource ]);

        // load resources
        if ($request->ajax()) return $dataTable->ajax();

        // return view with dataTable
        return $dataTable->render('banking::deposit_slips.index', [
            'count'                 => Resource::count(),
            'show_company_selector' => !backend()->companyScoped(),
        ]);
    }

    public function create(Request $request) {
        // force company selection
        if (!backend()->companyScoped()) return view('backend::layouts.master', [ 'force_company_selector' => true ]);

        // load bank accounts
        $bank_accounts = BankAccount::all();
        // load cash books
        $cash_books = CashBook::all();
        // load checks
        $checks = Check::deposited(false)->get();

        $highs = [
            'document_number'   => Resource::nextDocumentNumber(),
        ];

        // show create form
        return view('banking::deposit_slips.create', compact(
            'bank_accounts',
            'cash_books',
            'checks',
            'highs',
        ));
    }

    public function store(Request $request) {
        // start a transaction
        DB::beginTransaction();

        $request->merge([
            // set amount to 0 (zero) if not specified
            'cash_amount'   => $request->cash_amount ?? 0,
            // load open Cash
            'cash_id'       => $request->cash_book_id ? Cash::open($request->cash_book_id)->first()?->id : null
        ]);
        // check if no open cash is found
        if ($request->cash_book_id && !$request->cash_id)
            // redirect with errors
            return back()->withInput()
                ->withErrors([
                    'cash_book_id' => __('banking::deposit_slip.no-open-cash', [
                        'cash_book' => CashBook::find($request->cash_book_id)?->name,
                    ])
                ]);

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

        // update deposit slip total
        $resource->refresh()->update([
            // add checks amount to cash amount
            'total' => $resource->cash_amount
                // add checks amount
                + $resource->checks()->sum('payment_amount') ]);

        // confirm transaction
        DB::commit();

        // check return type
        return $request->has('only-form') ?
            // redirect to popup callback
            view('backend::components.popup-callback', compact('resource')) :
            // redirect to resource details
            redirect()->route('backend.deposit_slips.show', $resource);
    }

    public function show(Request $request, Resource $resource) {
        // load receipment data
        $resource->load([
            'bankAccount.bank',
            'cash.cashBook',
            'movement',
            'checks' => fn($check) => $check->with([
                'depositSlipCheck.movement',
                'bank',
            ]),
        ]);

        // redirect to list
        return view('banking::deposit_slips.show', compact('resource'));
    }

    public function edit(Request $request, Resource $resource) {
        // check if document is already approved or processed
        if ($resource->isApproved() || $resource->isProcessed())
            // redirect to show route
            return redirect()->route('backend.deposit_slips.show', $resource);

        // load bank accounts
        $bank_accounts = BankAccount::all();
        // load cash books
        $cash_books = CashBook::all();
        // load checks
        $checks = Check::deposited(false)->get();

        // load resource relations
        $resource->load([
            'checks',
        ]);

        // show edit form
        return view('banking::deposit_slips.edit', compact('resource',
            'bank_accounts',
            'cash_books',
            'checks',
        ));
    }

    public function update(Request $request, Resource $resource) {
        // start a transaction
        DB::beginTransaction();

        $request->merge([
            // set amount to 0 (zero) if not specified
            'cash_amount'   => $request->cash_amount ?? 0,
            // load open Cash
            'cash_id'       => $request->cash_book_id ? Cash::open($request->cash_book_id)->first()?->id : null
        ]);
        // check if no open cash is found
        if ($request->cash_book_id && !$request->cash_id)
            // redirect with errors
            return back()->withInput()
                ->withErrors([
                    'cash_book_id' => __('banking::deposit_slip.no-open-cash', [
                        'cash_book' => CashBook::find($request->cash_book_id)?->description,
                    ])
                ]);

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

        // update deposit slip total
        $resource->refresh()->update([
            // add checks amount to cash amount
            'total' => $resource->cash_amount
                // add checks amount
                + $resource->checks()->sum('payment_amount') ]);

        // confirm transaction
        DB::commit();

        // redirect to resource details
        return redirect()->route('backend.deposit_slips.show', $resource);
    }

    public function destroy(Request $request, Resource $resource) {
        // delete resource
        if (!$resource->delete())
            // redirect with errors
            return back()
                ->withErrors($resource->errors()->any() ? $resource->errors() : [ $resource->getDocumentError() ]);

        // redirect to list
        return redirect()->route('backend.deposit_slips');
    }

}
