import { Controller } from '@hotwired/stimulus';
import { Datepicker } from 'flowbite-datepicker';

export default class extends Controller {
    static values = {
        min: String,
        max: String,
        autohide: { type: Boolean, default: true },
        format: { type: String, default: 'yyyy-mm-dd' }, // fallback
    }

    connect() {
        this.picker = new Datepicker(this.element, {
            autohide: this.autohideValue,
            format: this.formatValue,
            minDate: this.minValue ? new Date(this.minValue) : null,
            maxDate: this.maxValue ? new Date(this.maxValue) : null,
        });
    }

    disconnect() {
        if (this.picker) {
            this.picker.destroy();
        }
    }
}
