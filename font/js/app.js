//Logica da rotina

//Identificar o click no menuba
//Verificar o item que foi clicado e fazer referencia com o alvo
//Verificar a distancia entre o alvo e o topo
//Animar o scroll atéo alvo

$('nav a').click(function(e){
	e.preventDefault()
	var iclicado = $(this).attr('href'),
	targetOffset = $(iclicado).offset().top,
	menuHeight = $('nav').innerHeight();
	$('html,body').animate({
		scrollTop:targetOffset -menuHeight
	}, 500);
});