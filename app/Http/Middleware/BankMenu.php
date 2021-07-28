<?php

namespace HDSSolutions\Laravel\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;

class BankMenu {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        // create a submenu
        $sub = backend()->menu()
            ->add(__('bank::banks.nav'), [
                // 'icon'  => 'cogs',
            ])->data('priority', 700);

        $this
            // append items to submenu
            ->banks($sub);

        // continue witn next middleware
        return $next($request);
    }

    private function banks(&$menu) {
        if (Route::has('backend.banks'))
            $menu->add(__('bank::banks.nav'), [
                'route'     => 'backend.banks',
                'icon'      => 'banks'
            ]);

        return $this;
    }

}
