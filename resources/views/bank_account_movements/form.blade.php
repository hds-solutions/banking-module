@include('backend::components.errors')

<x-backend-form-foreign :resource="$resource ?? null" name="bank_account_id" required readonly
    foreign="bank_accounts" :values="$bank_accounts" request="bank_account"
    {{-- default="123" --}}

    {{-- foreign-add-label="banking::bank_accounts.add" --}}
    show="bank.name account_number"

    label="banking::bank_account_movement.bank_account_id.0"
    placeholder="banking::bank_account_movement.bank_account_id._"
    {{-- helper="banking::bank_account_movement.bank_account_id.?" --}} />

<x-backend-form-datetime name="transacted_at" required
    :resource="$resource ?? null" default="{{ now() }}"

    label="banking::bank_account_movement.transacted_at.0"
    placeholder="banking::bank_account_movement.transacted_at._"
    {{-- helper="banking::bank_account_movement.transacted_at.?" --}} />

<x-backend-form-select :resource="$resource ?? null" name="movement_type" required
    :values="BankAccountMovement::MOVEMENT_TYPES" {{-- default="123" --}}

    label="banking::bank_account_movement.movement_type.0"
    placeholder="banking::bank_account_movement.movement_type._"
    {{-- helper="banking::bank_account_movement.movement_type.?" --}} />

<x-backend-form-text :resource="$resource ?? null" name="description" required

    label="banking::bank_account_movement.description.0"
    placeholder="banking::bank_account_movement.description._"
    {{-- helper="banking::bank_account_movement.description.?" --}} />

<x-backend-form-amount :resource="$resource ?? null" name="amount" required
    :data-decimals="$bank_account->currency->decimals"

    label="banking::bank_account_movement.amount.0"
    placeholder="banking::bank_account_movement.amount._"
    {{-- helper="banking::bank_account_movement.amount.?" --}} />

<x-backend-form-controls
    submit="banking::bank_account_movements.save"
    cancel="banking::bank_account_movements.cancel" cancel-route="backend.bank_accounts.show:{{ $bank_account->id }}" />
