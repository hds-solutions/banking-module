import Document from '../../../../../backend-module/resources/assets/js/resources/Document';
import DepositSlipCheck from './DepositSlipCheck';

export default class DepositSlip extends Document {

    #bankAccount;
    #decimals = 0;

    #cashAmount = 0;
    #cashSymbol;
    #cashBook;

    #total = 0;
    #totalSymbol;

    constructor() {
        super();
        this.#bankAccount = document.querySelector('[name="bank_account_id"]');

        this.#cashAmount = document.querySelector('[name="cash_amount"]');
        this.#cashSymbol = this.#cashAmount.closest('.input-group').querySelector('.input-group-text');
        this.#cashBook = document.querySelector('[name="cash_book_id"]');

        this.#total = document.querySelector('[name="total"]');
        this.#totalSymbol = this.#total.closest('.input-group').querySelector('.input-group-text');

        this.init();
    }

    get bankAccount() { return this.#bankAccount.selectedOptions[0]; }
    get decimals() { return this.bankAccount.dataset.decimals; }
    get cashAmount() { return this.undecimalize(this.#cashAmount.value, this.decimals); }

    _getContainerInstance(container) {
        // return line container
        return (new DepositSlipCheck(this, container))
            // capture line update and update total amount
            .updated(e => this.updateTotal(e))
            .removed(e => this.updateTotal(e))
    }

    init() {
        // capture bank_account change
        this.#bankAccount.addEventListener('change', e =>
            // update currency symbol
            this.#cashSymbol.textContent = this.#totalSymbol.textContent = this.bankAccount.dataset.symbol);
        // capture cash_amount change
        this.#cashAmount.addEventListener('change', e => {
            // update required on cashBook
            this.#cashBook.removeAttribute('required');
            if (this.cashAmount > 0) this.#cashBook.setAttribute('required', true);

            // update total amount
            this.updateTotal(e);
        });
    }

    updateTotal(event) {
        // total acumulator
        let total = this.cashAmount;
        // foreach lines
        this.lines.forEach(line => total += line.amount);
        // set total
        this.#total.value = total > 0 ? this.decimalize(total, this.decimals) : '';
        // fire format
        if (total > 0) this.fire('blur', this.#total);
    }

}
