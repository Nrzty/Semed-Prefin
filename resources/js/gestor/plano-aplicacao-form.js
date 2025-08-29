window.planoAplicacaoForm = function(initialData = {}) {
    return {
        novoItem: {
            descricao: '',
            categoria_despesa: 'Custeio',
            unidade: 'Un',
            quantidade: 1,
            valor_unitario: 0,
        },
        itens: initialData.itens || [],
        get totalCusteio() {
            return this.itens
                .filter(item => item.categoria_despesa === 'Custeio')
                .reduce((total, item) => total + (item.quantidade * parseFloat(item.valor_unitario)), 0);
        },
        get totalCapital() {
            return this.itens
                .filter(item => item.categoria_despesa === 'Capital')
                .reduce((total, item) => total + (item.quantidade * parseFloat(item.valor_unitario)), 0);
        },
        get totalGeral() {
            return this.totalCusteio + this.totalCapital;
        },
        adicionarItem() {
            if (!this.novoItem.descricao.trim() || this.novoItem.quantidade <= 0 || this.novoItem.valor_unitario <= 0) {
                alert('Por favor, preencha a descrição, quantidade e valor do item corretamente.');
                return;
            }
            this.itens.push({ ...this.novoItem });
            this.resetarNovoItem();
        },
        removerItem(index) {
            this.itens.splice(index, 1);
        },
        resetarNovoItem() {
            this.novoItem.descricao = '';
            this.novoItem.categoria_despesa = 'Custeio';
            this.novoItem.quantidade = 1;
            this.novoItem.valor_unitario = 0;
            this.$nextTick(() => document.getElementById('descricao').focus());
        },
        submitForm(event) {
            if (this.itens.length === 0) {
                alert('É necessário adicionar pelo menos um item ao plano antes de submeter.');
                return;
            }
            const form = event.target;
            const hiddenInput = form.querySelector('input[name="itens_json"]');
            hiddenInput.value = JSON.stringify(this.itens);
            form.submit();
        }
    };
}
