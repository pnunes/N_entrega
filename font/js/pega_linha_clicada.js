// Esta rotina pega os dados da linha da tabela onde houve um clique sobre o icone pencil
	
    var selectv = document.querySelector('#noclitds');
	var cliente = selectv.options[selectv.selectedIndex].value;
	//alert(cliente); // pt
	
	/*var selectt = document.getElementById('#noclitds');
	var texto = selectt.options[selectt.selectedIndex].text;
	console.log(texto); // Português*/
	
	var tabela = document.querySelector("#tabela_01");
    
	tabela.addEventListener("dblclick", function(m){		
	   var selectcli = document.querySelector('#noclitds');
	   var cliente = selectcli.options[selectcli.selectedIndex].value;
	   /////////////////////////////////////////////////////////////////
	   var selectser = document.querySelector('#servese');
	   var servico = selectser.options[selectser.selectedIndex].value;
	   ////////////////////////////////////////////////////////////////
	   var selectcep = document.querySelector('#clase');
	   var classecp = selectcep.options[selectcep.selectedIndex].value;
	   ///////////////////////////////////////////////////////////////
	   //var servico  = document.querySelector("#servese").textContent;
	   //var classecp = document.querySelector("#clase").textContent;
	   
	   var vlr_cli  = document.querySelector("#vclitd").textContent;
	   //////////////////////////////////////////////////////////////
	   var selectfor = document.querySelector('#nofortds');
	   var fornece  = selectfor.options[selectfor.selectedIndex].value;
	   //////////////////////////////////////////////////////////////
	   //var fornece  = document.querySelector("#nofortds").textContent;
	   var vlr_for  = document.querySelector("#vfortd").textContent;
	   var situa    = document.querySelector("#situtd").textContent;
       alert(cliente+" - "+servico+" - "+classecp+" - "+vlr_cli+" - "+fornece+" - "+vlr_for+" - "+situa);
	});
	 