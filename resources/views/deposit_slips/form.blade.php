@include('backend::components.errors')

<x-backend-form-foreign :resource="$resource ?? null" name="bank_account_id" required
    foreign="bank_accounts" :values="$bank_accounts" foreign-add-label="banking::bank_accounts.add"

    show="bank.name account_number"
    append="decimals:currency.decimals,symbol:currency.code"

    label="banking::deposit_slip.bank_account_id.0"
    placeholder="banking::deposit_slip.bank_account_id._"
    {{-- helper="banking::deposit_slip.bank_account_id.?" --}} />

<x-backend-form-text name="document_number" required
    :resource="$resource ?? null" :default="$highs['document_number'] ?? null"

    label="banking::deposit_slip.document_number.0"
    placeholder="banking::deposit_slip.document_number._"
    {{-- helper="banking::deposit_slip.document_number.?" --}} />

<x-backend-form-datetime name="transacted_at" required
    :resource="$resource ?? null" default="{{ now() }}"

    label="banking::deposit_slip.transacted_at.0"
    placeholder="banking::deposit_slip.transacted_at._"
    {{-- helper="banking::deposit_slip.transacted_at.?" --}} />

<x-backend-form-amount name="cash_amount"
    :resource="$resource ?? null"
    currency="[name=bank_account_id]"

    :prepend="currency(isset($resource) ? $resource->bankAccount->currency_id : backend()->currency()?->id ?? 1)->code"

    label="banking::deposit_slip.cash_amount.0"
    placeholder="banking::deposit_slip.cash_amount._"
    {{-- helper="banking::deposit_slip.cash_amount.?" --}}>

    <x-backend-form-foreign :resource="$resource ?? null" name="cash_book_id" secondary
        foreign="cash_books" :values="$cash_books" foreign-add-label="banking::cash_books.add"
        :default="isset($resource) ? $resource->cash?->cash_book_id : null"

        label="banking::deposit_slip.cash_book_id.0"
        placeholder="banking::deposit_slip.cash_book_id._"
        {{-- helper="banking::deposit_slip.cash_book_id.?" --}} />

</x-backend-form-amount>

<x-backend-form-multiple name="checks"
    :values="$checks" :selecteds="isset($resource) ? $resource->checks : []"
    contents-view="banking::deposit_slips.form.check" data-type="deposit_slip"
    card="bg-white" class="my-2"
    label="banking::deposit_slip.checks.0" >

    <x-slot name="card-footer">
        <div class="row">
            <div class="col-9 col-xl-10 offset-1">
                <div class="row">
                    <div class="col-3 offset-9">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text font-weight-bold px-3">{{ currency(isset($resource) ? $resource->bankAccount->currency_id : backend()->currency()?->id ?? 1)->code }}</span>
                            </div>
                            <input name="total" type="number" min="0" thousand readonly
                                value="{{ old('total', isset($resource) ? number($resource->total, currency($resource->bankAccount->currency_id)->decimals) : null) }}" tabindex="-1"
                                data-currency-by="[name=bank_account_id]" data-keep-id="true" data-decimals="0"
                                class="form-control form-control-lg text-right font-weight-bold"
                                placeholder="@lang('banking::deposit_slip.total.0')">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

</x-backend-form-multiple>

<x-backend-form-controls
    submit="banking::deposit_slips.save"
    cancel="banking::deposit_slips.cancel"
        cancel-route="{{ isset($resource)
            ? 'backend.deposit_slips.show:'.$resource->id
            : 'backend.deposit_slips' }}" />
