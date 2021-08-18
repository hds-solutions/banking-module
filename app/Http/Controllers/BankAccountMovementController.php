<?php

namespace HDSSolutions\Laravel\Http\Controllers;

use App\Http\Controllers\Controller;
use HDSSolutions\Laravel\Http\Request;
use HDSSolutions\Laravel\Models\BankAccount;
use HDSSolutions\Laravel\Models\BankAccountMovement as Resource;

class BankAccountMovementController extends Controller {

    public function __construct() {
        // check resource Policy
        $this->authorizeResource(Resource::class, 'resource');
    }

    public function create(Request $request) {
        // load bank_accounts
        $bank_accounts = BankAccount::with([
            'bank',
        ])->get();
        // get selected BankAccount
        $bank_account = $bank_accounts->firstWhere('id', $request->bank_account);

        // show create form
        return view('banking::bank_account_movements.create', compact('bank_accounts', 'bank_account'));
    }

    public function store(Request $request) {
        // cast to boolean
        // $request->merge([ 'show_home' => $request->show_home == 'on' ]);

        // create resource
        $resource = new Resource( $request->input() + [ 'confirmed' => true ] );

        // save resource
        if (!$resource->save())
            // redirect with errors
            return back()->withInput()
                ->withErrors( $resource->errors() );

        // check return type
        return $request->has('only-form') ?
            // redirect to popup callback
            view('backend::components.popup-callback', compact('resource')) :
            // redirect to resources list
            redirect()->route('backend.bank_accounts.show', $resource->bankAccount);
    }

}
