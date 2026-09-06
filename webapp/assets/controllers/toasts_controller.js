import { Controller } from '@hotwired/stimulus';
import * as bootstrap from 'bootstrap';

export default class extends Controller {
    static targets = [
        'toast',
    ];

    connect() {
        for (const toastTarget of this.toastTargets) {
            const toast = new bootstrap.Toast(toastTarget);
            toast.show();
        }
    }
}
