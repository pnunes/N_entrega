  //ROTINA PARA INCLUIR LINHA DE INPUT NA TABELA

  //Pega o botao que inclui novo registro
var botaoAdicionar = document.querySelector("#insere_registro");
   
   //coloca um escutador do evento click no botão de incluir
botaoAdicionar.addEventListener("click", function(event) {
	
	//inibe o comportamento normal do submit
    event.preventDefault();
    
	// cria uma linha TR para adicionar a tabela
    var registroTr = document.createElement("tr");
	
	// Processo de criação de cada TD com os respectivos inputs
	
	// cria uma td para o nome 
    var nomectd  = document.createElement("td");
	
	// cria o select para a TD nome
	var elemento_nome = document.createElement("select");
	elemento_nome.setAttribute("type", "select");
	elemento_nome.setAttribute("name", "noclise");
	elemento_nome.setAttribute("id", "noclitd");
	
	//adiciona os elementos do input a TD nome 
	nomectd.appendChild(elemento_nome);
	registroTr.appendChild(nomectd);
	
	// repete o processo para a tD serviço
    var servetd  = document.createElement("td");
	
	var elemento_servi = document.createElement("select");
	elemento_servi.setAttribute("type", "select");
	elemento_servi.setAttribute("name", "servise");
	elemento_servi.setAttribute("id", "servetd");
	
	servetd.appendChild(elemento_servi);
	registroTr.appendChild(servetd);
	
	// repete o processo para a TD classe CEP
	var clacetd  = document.createElement("td");
	
	var elemento_clac = document.createElement("select");
	elemento_clac.setAttribute("type", "select");
	elemento_clac.setAttribute("name", "clacese");
	elemento_clac.setAttribute("id", "clatd");
	
	clacetd.appendChild(elemento_clac);
	registroTr.appendChild(clacetd);
	
	// repete o processo para a TD valorcli
	var vlclitd  = document.createElement("td");
	
	var elemento_vcli = document.createElement("input");
	elemento_vcli.setAttribute("type", "text");
	elemento_vcli.setAttribute("name", "valorcli");
	elemento_vcli.setAttribute("id", "vclitd");
	
	vlclitd.appendChild(elemento_vcli);
	registroTr.appendChild(vlclitd);
	
	// repete o processo para a TD nome fornecedor
	var nofortd  = document.createElement("td");
	
	var elemento_nfor = document.createElement("select");
	elemento_nfor.setAttribute("type", "select");
	elemento_nfor.setAttribute("name", "noforse");
	elemento_nfor.setAttribute("id", "nofortd");
	
	nofortd.appendChild(elemento_nfor);
	registroTr.appendChild(nofortd);
	
	// repete o processo para a TD valor fornecedor
	var vlfortd  = document.createElement("td");
	
	var elemento_vfor = document.createElement("input");
	elemento_vfor.setAttribute("type", "text");
	elemento_vfor.setAttribute("name", "valorfor");
	elemento_vfor.setAttribute("id", "vfortd");
	
	vlfortd.appendChild(elemento_vfor);
	registroTr.appendChild(vlfortd);
	
	// repete o processo para a TD Situaçõo
	var situatd  = document.createElement("td");
	
	var elemento_situ = document.createElement("input");
	elemento_situ.setAttribute("type", "text");
	elemento_situ.setAttribute("name", "situacao");
	elemento_situ.setAttribute("id", "situad");
	
	situatd.appendChild(elemento_situ);
	registroTr.appendChild(situatd);
	
	/////////////////////////////////////////////////////////////////////
    
	var grava  = document.createElement("td");
	var botao = document.createElement("button");
	botao.setAttribute('type','button');
	botao.setAttribute('ID','alteratd');
	botao.setAttribute('class','glyphicon glyphicon-pencil text-primary');
	
	grava.appendChild(botao);
	registroTr.appendChild(grava);
	
	var deleta  = document.createElement("td");
	var botaod = document.createElement("button");
	botaod.setAttribute('type','button');
	botaod.setAttribute('ID','excluitd');
	botaod.setAttribute('class','glyphicon glyphicon-minus-sign text-danger');
	
	deleta.appendChild(botaod);
	registroTr.appendChild(deleta);
	/////////////////////////////////////////////////////////////////////
	
	//insere a nova linha com as respectivas celulas dentro da tabela.
    //appendChild - indere a linha no final da tabela
	// prepend - adiciona linha no inicio da tabela
	
	tabela_01.prepend(registroTr);
	console.log(registroTr);
	
	//Carrega o select com os dados da tabela clifor.
	 
	var comboCli = document.querySelector("#noclitd");
	//console.log(comboCli);
	$(document).ready(function () {
		$.post('listar_dados_select.php?tipo=cli', function(retorna){	
			$("#noclitd").html(retorna);
			var lista = retorna.split("*");			
			var lista_nova = lista.filter(function(ele){
				return ele !== "";
			});
			for (var i=0;i < lista_nova.length; i++) {
				var opt0 = document.createElement("option");
				opt0.text = lista_nova[i];
				comboCli.add(opt0, comboCli.options[i]);
			}
		});
		
	});
		
	////////////////////////////////////////////////
	
	//Carrega o select com os dados da tabela serviço.
	
	var comboSer = document.querySelector("#servetd");
	//console.log(comboCli);
	$(document).ready(function () {
		$.post('listar_dados_select.php?tipo=ser', function(retorna){	
			$("#servetd").html(retorna);
			var lista = retorna.split("*");			
			var lista_nova = lista.filter(function(ele){
				return ele !== "";
			});
			for (var i=0;i < lista_nova.length; i++) {
				var opt0 = document.createElement("option");
				opt0.text = lista_nova[i];
				comboSer.add(opt0, comboSer.options[i]);
			}
		});
		
	});
	
		
	////////////////////////////////////////////////
	
	//Carrega o select com os dados da tabela classe cep.
	
	var comboCep = document.querySelector("#clatd");
	//console.log(comboCli);
	$(document).ready(function () {
		$.post('listar_dados_select.php?tipo=cep', function(retorna){	
			$("#clatd").html(retorna);
			var lista = retorna.split("*");			
			var lista_nova = lista.filter(function(ele){
				return ele !== "";
			});
			for (var i=0;i < lista_nova.length; i++) {
				var opt0 = document.createElement("option");
				opt0.text = lista_nova[i];
				comboCep.add(opt0, comboCep.options[i]);
			}
		});
		
	});
		
	////////////////////////////////////////////////
	
	//Carrega o select com os dados da tabela cli_for para fornecedor.
	
	var comboFor = document.querySelector("#nofortd");
	//console.log(comboCli);
	$(document).ready(function () {
		$.post('listar_dados_select.php?tipo=for', function(retorna){	
			$("#nofortd").html(retorna);
			var lista = retorna.split("*");			
			var lista_nova = lista.filter(function(ele){
				return ele !== "";
			});
			for (var i=0;i < lista_nova.length; i++) {
				var opt0 = document.createElement("option");
				opt0.text = lista_nova[i];
				comboFor.add(opt0, comboFor.options[i]);
			}
		});
		
	});
		
	////////////////////////////////////////////////
});



