<?php

namespace HDSSolutions\Laravel\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;

class BankingMenu extends Base\Menu {

    public function handle($request, Closure $next) {
        // create a submenu
        $sub = backend()->menu()
            ->add(__('banking::banking.nav'), [
                'icon'  => 'university',
            ])->data('priority', 700);

        $this
            // append items to submenu
            ->banks($sub)
            ->bank_accounts($sub)
            ->deposit_slips($sub)
            ->reconciliations($sub);

        // continue witn next middleware
        return $next($request);
    }

    private function banks(&$menu) {
        if (Route::has('backend.banks') && $this->can('banks.crud.index'))
            $menu->add(__('banking::banks.nav'), [
                'route'     => 'backend.banks',
                'icon'      => 'building'
            ]);

        return $this;
    }

    private function bank_accounts(&$menu) {
        if (Route::has('backend.bank_accounts') && $this->can('bank_accounts.crud.index'))
            $menu->add(__('banking::bank_accounts.nav'), [
                'route'     => 'backend.bank_accounts',
                'icon'      => 'wallet'
            ]);

        return $this;
    }

    private function deposit_slips(&$menu) {
        if (Route::has('backend.deposit_slips') && $this->can('deposit_slips.crud.index'))
            $menu->add(__('banking::deposit_slips.nav'), [
                'route'     => 'backend.deposit_slips',
                'icon'      => 'donate'
            ]);

        return $this;
    }

    private function reconciliations(&$menu) {
        if (Route::has('backend.reconciliations') && $this->can('reconciliations.crud.index'))
            $menu->add(__('banking::reconciliations.nav'), [
                'route'     => 'backend.reconciliations',
                'icon'      => 'signature'
            ]);

        return $this;
    }

}
