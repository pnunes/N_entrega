//Esta rotina tem a função de filtrar os dados da tabela
    
	
    var campoFiltro = document.querySelector("#filtrar-dados");

	campoFiltro.addEventListener("input", function() {
		var clientes = document.querySelectorAll(".linhas");
        if (this.value.length > 0 ) {
			for (var i = 0; i < clientes.length; i++) {
				var cliente = clientes[i];
				//console.log(cliente);
				var tdNome = cliente.querySelector("#noclitd");
				var nome = tdNome.textContent;
				var expressao = new RegExp(this.value, "i");
				console.log(nome);
				console.log(this.value);
				if (!expressao.test(nome)) {
                    cliente.classList.add("invisivel");
				} else {
					cliente.classList.remove("invisivel");
				}
			}
		}else {
			for (var i=0; i < clientes.length; i++) {
				var cliente = clientes[i];
				cliente.classList.remove("invisivel");
			}
		}
	});
