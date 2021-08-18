@include('backend::components.errors')

<x-backend-form-foreign :resource="$resource ?? null" name="bank_id" required
    foreign="banks" :values="$banks" foreign-add-label="banking::banks.add"

    label="banking::bank_account.bank_id.0"
    placeholder="banking::bank_account.bank_id._"
    {{-- helper="banking::bank_account.bank_id.?" --}} />

<x-backend-form-select :resource="$resource ?? null" name="account_type" required
    :values="\HDSSolutions\Laravel\Models\BankAccount::ACCOUNT_TYPES" {{-- default="123" --}}

    label="banking::bank_account.account_type.0"
    placeholder="banking::bank_account.account_type._"
    {{-- helper="banking::bank_account.account_type.?" --}} />

<x-backend-form-text :resource="$resource ?? null" name="account_number" required
    label="banking::bank_account.account_number.0"
    placeholder="banking::bank_account.account_number._"
    {{-- helper="banking::bank_account.account_number.?" --}} />

<x-backend-form-text :resource="$resource ?? null" name="iban"
    label="banking::bank_account.iban.0"
    placeholder="banking::bank_account.iban.optional"
    {{-- helper="banking::bank_account.iban.?" --}} />

<x-backend-form-text :resource="$resource ?? null" name="description"
    label="banking::bank_account.description.0"
    placeholder="banking::bank_account.description.optional"
    {{-- helper="banking::bank_account.description.?" --}} />

<x-backend-form-foreign :resource="$resource ?? null" name="currency_id" required
    foreign="currencies" :values="backend()->currencies()"
    request="currency" default="{{ backend()->currency()?->id }}"

    append="decimals"

    foreign-add-label="cash::currencies.add"
    {{-- show="cashBook.name" --}}

    label="banking::bank_account.currency_id.0"
    placeholder="banking::bank_account.currency_id._"
    {{-- helper="banking::bank_account.currency_id.?" --}} />

<x-backend-form-amount :resource="$resource ?? null" name="credit_limit" required
    currency="[name=currency_id]"

    label="banking::bank_account.credit_limit.0"
    placeholder="banking::bank_account.credit_limit._"
    {{-- helper="banking::bank_account.credit_limit.?" --}} />

<x-backend-form-controls
    submit="banking::bank_accounts.save"
    cancel="banking::bank_accounts.cancel" cancel-route="backend.bank_accounts" />
