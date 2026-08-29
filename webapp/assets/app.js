import './stimulus_bootstrap.js';

import * as bootstrap from 'bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap-icons/font/bootstrap-icons.min.css';

import './styles/app.css';


document.addEventListener('turbo:load', e => {
    document.querySelectorAll('.toast').forEach(toastElement => {
        const toast = new bootstrap.Toast(toastElement);
        toast.show();
    });
});
