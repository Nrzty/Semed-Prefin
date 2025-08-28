function planoAplicacaoForm() {
    return {
        novoItem: {
            descricao: '',
            categoria_despesa: 'Custeio',
            unidade: 'UND',
            quantidade: 1,
            valor_unitario: 0
        },
        itens: [],

        get totalCusteio() {
            return this.itens.filter(i => i.categoria_despesa === 'Custeio').reduce((total, item) => total + (item.quantidade * item.valor_unitario), 0);
        },
        get totalCapital() {
            return this.itens.filter(i => i.categoria_despesa === 'Capital').reduce((total, item) => total + (item.quantidade * item.valor_unitario), 0);
        },
        get totalGeral() {
            return this.totalCusteio + this.totalCapital;
        },

        adicionarItem() {
            if (!this.novoItem.descricao.trim() || this.novoItem.quantidade <= 0 || this.novoItem.valor_unitario <= 0) {
                alert('Por favor, preencha todos os campos do item corretamente.');
                return;
            }
            this.itens.push({ ...this.novoItem });
            this.resetNovoItem();
        },

        removerItem(index) {
            this.itens.splice(index, 1);
        },

        resetNovoItem() {
            this.novoItem.descricao = '';
            this.novoItem.quantidade = 1;
            this.novoItem.valor_unitario = 0;
            document.getElementById('descricao').focus();
        },

        submitForm(event) {
            if (this.itens.length === 0) {
                alert('Adicione pelo menos um item ao plano antes de submeter.');
                return;
            }
            event.target.submit();
        }
    }
}

export default planoAplicacaoForm;
