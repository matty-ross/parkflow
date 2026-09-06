import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = [
        'navLink',
    ];

    static classes = [
        'active',
    ];

    connect() {
        for (const navLinkTarget of this.navLinkTargets) {
            if (navLinkTarget.pathname === location.pathname) {
                navLinkTarget.classList.add(...this.activeClasses);
            }
        }
    }
}
