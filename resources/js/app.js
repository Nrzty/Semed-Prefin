import './bootstrap';

import Alpine from 'alpinejs';

import './admin-charts.js';

import planoAplicacaoForm from './gestor/plano-aplicacao-form';

document.addEventListener('alpine:init', () => {
    Alpine.data('planoAplicacaoForm', planoAplicacaoForm)
})

window.Alpine = Alpine;

Alpine.start();

