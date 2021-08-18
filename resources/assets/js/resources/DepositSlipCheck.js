import DocumentLine from '../../../../../backend-module/resources/assets/js/resources/DocumentLine';

export default class DepositSlipLine extends DocumentLine {

    #check;

    constructor(document, container) {
        super(document, container);
        this.#check = this.container.querySelector('[name="checks[]"]');
    }

    get amount() {
        // return 0 (zero) on empty
        if (!this.#check.selectedOptions[0].value) return 0;
        // return amount without decimals
        return this.undecimalize(this.#check.selectedOptions[0].dataset.amount ?? 0, this.document.decimals);
    }

}
