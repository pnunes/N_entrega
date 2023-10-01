<!DOCTYPE html>
<html lang="pt_br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="css/estilo.css">
    <title>Pagina Inicial</title>
</head>
<body>
    <header class="container">  
        <nav class="navbar navbar-default" id="menu">
			<!--<div>-->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" 
					data-target="#collapse-navbar" aria-expanded="false">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="#">Transportadora Free Jazz</a>
				</div>
				<div class="collapse navbar-collapse col-lg-6" id="collapse-navbar">
					<ul class="nav navbar-nav mr-auto">
						<li><a href="#sobre-nos">Sobre Nós</a></li>
						<li><a href="#nossos-servicos">Nossos Serviços</a></li>
						<li><a href="#onde-estamos">Onde Estamos</a></li>
						<li><a href="#contato">Contato</a></li>
						<li><a href="#login" id="login"><span class="glyphicon glyphicon-log-in"></span></a></li>
					</ul>
				</div>
                <!--<a href="#"><div class="col-lg-6" id="login"><i class="fa-duotone fa-user-tie-hair"></i></div></a>-->
			<!--</div>-->
		</nav>
		<form class="form-inline" action="../php_poo/confere_acesso.php" method="post" id="">
			<div class="row">
			<div class="container freejazz-bannerWrapper">
				<div class="freejazz-banner">
					<h1>Transportadora Free Jazz</h1>
					<p>Prestando serviços de qualidade e agilidade desde 2001.</p>
					<button class="btn btn-primary btn-lg">Rastrear entrega</button>
				</div>
			</div>
			<div id="acesso" class="form-group">
				<input type="text" class="form-control" name="usuario" size="15" maxlength="15" id="usuario" placeholder="usuario"/>
				<input type="password" class="form-control" name="senha" size="15" maxlength="15" id="senha" placeholder="senha"/>
				<button type="submit" class="btn btn-default" id="input_entrar">Entrar</button>
			</div>
			</div>
		</form>
    </header>
	<section id="sobre-nos" class="container">
		<div class="container" id="texto-sobrenos">
		    <div id="div-sn">Sobre Nós</div>
				<p id="sn-parag-1" class="texto-empresa">Há mais de dez anos no mercado, a <b>TRANSPORTES FREE JAZZ LTDA-ME</b> 
					está instalada em Curitiba/PR atendendo todo o mercado paranaense, e, através 
					de seus parceiros, atua fortemente no estado de Santa Catarina. <b>A TRANPORTES 
					FREE JAZZ LTDA-ME</b> é uma empresa de soluções integradas em logística e serviços 
					personalizados. Formada por duas divisões, atua na prestação de serviços de 
					manuseio, coleta e entregas zelando pela segurança, agilidade e eficiência em seus processos. 
					Além disso, oferece mão de obra especializada para execução de serviços diferenciados 
					e personalizados.</p>
					
				<p id="sn-parag-2" class="texto-empresa">Oferecemos soluções específicas para tipo de 
					negócio, com alto nível de eficácia, adequando e otimizando os serviços, 
					para que ocorram em tempo, condições e forma adequada. Buscando sempre 
					a excelência na prestação de serviços, nossas soluções contribuem positivamente com 
					os resultados dos nossos clientes.</p>
				
				<p id="sn-parag-3" class="texto-empresa">Para o sucesso no transporte dos produtos dos seus 
					clientes, a <b>TRANPORTES FREE JAZZ LTDA-ME</b> conta com métodos 
					modernos e adequados de atendimento, primando pela eficiência em todos os sentidos, 
					pois conta com uma equipe de profissionais altamente qualificados.</p>
		</div>
    </section>
	<section id="nossos-servicos" class="container">
		<div class="container" id="texto-nossos-servicos">
		    <div id="div-serv">Nossos Serviços</div>
				<p id="serv-parag-1" class="texto-servico">Preocupada em proporcionar aos seus 
					clientes soluções logísticas adequadas às suas necessidades, 
					a <b>TRANPORTES FREE JAZZ LTDA-ME</b> oferece diversos formatos para a melhor 
					utilização de sua estrutura, bem como otimização de seus serviços. 
					Para isto, dispomos de motocicletas e veículos apropriados. Acreditamos 
					que a excelência de nossa prestação de serviços seja conseqüência direta 
					da maneira como tratamos as particularidades de cada operação, por isso, além dos 
					serviços aqui descritos, nos comprometemos em oferecer qualquer 
					solução logística que seja mais adequada.</p>

				<p id="serv-parag-2" class="texto-servico">Primamos pelo bom desempenho 
					do nosso trabalho, bem como pela maximização dos esforços dos clientes, 
					visando contribuir de maneira positiva e eficaz com o desempenho das 
					atividades executadas.</p>
				<br>
				<div class="row" id="descricao-servicos">
					<div class="col-sm-6">	
						<p id="serv-parag-3" class="glyphicon glyphicon-ok item-servico"> Manuseio de documentos/correspondências;</p>
						<p id="serv-parag-4" class="glyphicon glyphicon-ok item-servico"> Manuseio de vales transportes,brindes e produtos;</p>
						<p id="serv-parag-5" class="glyphicon glyphicon-ok item-servico"> Transporte de malotes;</p>
						<p id="serv-parag-6" class="glyphicon glyphicon-ok item-servico"> Coleta, transporte/entrega de documentos (ou similares);</p>
						<p id="serv-parag-7" class="glyphicon glyphicon-ok item-servico"> Serviços de coleta, transporte e entrega de cargas (utilizando-se de utilitários);</p>
					</div>
					<div class="col-sm-6">
						<p id="serv-parag-8" class="glyphicon glyphicon-ok item-servico"> Entregas departamentalizadas e Monitoradas;</p>
						<p id="serv-parag-9" class="glyphicon glyphicon-ok item-servico"> Veículos exclusivos (cargas expressas/Urgentes);</p>
						<p id="serv-parag-10" class="glyphicon glyphicon-ok item-servico"> Entregas urgentes (pequeno porte);</p>
						<p id="serv-parag-11" class="glyphicon glyphicon-ok item-servico"> Guarda de Malotes, Documentos/Brindes;</p>
						<p id="serv-parag-12" class="glyphicon glyphicon-ok item-servico"> Entre outros.</p>								
					</div>
				</div>
		</div>
    </section>
	<section id="onde-estamos" class="container">
		<div class="container" id="texto-onde-estamos">
		    <div id="div-oe">Onde Estamos</div>
			<div id="endereco">
			    <p id="ende-cidade">Curitiba - PR</p>
				<p id="ende-rua" class="detalhe-ende">Rua Conselheiro Laurido, 809</p>
				<p id="ende-comple" class="detalhe-ende">Sala 704 - Centro</p>
				<p id="ende-cep" class="detalhe-ende">CEP 80060-100</p>
			</div>
			<div id="mapa">
			<iframe width="90%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" 
			src="http://maps.google.com.br/maps?f=q&amp;source=s_q&amp;hl=pt-BR&amp;geocode=&amp;
			q=Rua+Conselheiro+Laurindo,+809,+Curitiba+-+Paran%C3%A1&amp;sll=-14.239424,-53.186502&amp;
			sspn=44.966072,79.013672&amp;ie=UTF8&amp;hq=&amp;hnear=R.+Conselheiro+Laurindo,
			+809+-+Centro,+Curitiba+-+Paran%C3%A1,+80060-100&amp;t=m&amp;ll=-25.433819,-49.263296&amp;
			spn=0.019921,0.032959&amp;z=14&amp;iwloc=A&amp;output=embed"></iframe><br /><small>
				<a href="http://maps.google.com.br/maps?f=q&amp;source=embed&amp;hl=pt-BR&amp;
				geocode=&amp;q=Rua+Conselheiro+Laurindo,+809,+Curitiba+-+Paran%C3%A1&amp;sll=-14.239424,
				-53.186502&amp;sspn=44.966072,79.013672&amp;ie=UTF8&amp;hq=&amp;
				hnear=R.+Conselheiro+Laurindo,+809+-+Centro,+Curitiba+-+Paran%C3%A1,+80060-100&amp;
				t=m&amp;ll=-25.433819,-49.263296&amp;spn=0.019921,0.032959&amp;z=14&amp;iwloc=A" 
				style="color:#0000FF;text-align:left">Exibir mapa ampliado</a></small></div>
			</div>		
		</div>
    </section>
	<section id="contato" class="container">
		<div class="container" id="dados-contato">
		    <div id="div-titulo-contato">Contato</div>
			<div class="row" id="linha">
				<div class="col-sm-3" id="div-dados-contato">
					<br>
					<p class="fones-contato cidade">Curitiba /PR</p><br>
					<p class="fones-contato outros">Fone: (41) 3078 6864</p><br>
					<p class="fones-contato outros">Cel:  (41) 7814 9150</p><br>
					<p class="fones-contato outros">Nextel: 80*44343</p><br><br><br>

					<p class="fones-contato cidade">São José /SC</p><br>
					<p class="fones-contato outros">Fone: (48) 3034 3160</p><br>
					<p class="fones-contato outros">Cel:  (48) 7811 3160</p><br>
					<p class="fones-contato outros">Nextel: 80*44340</p><br>
				</div>
				<div class="col-sm-9" id="div-form-contato">
					<form action="#" class="form-inline" role="form">	
							<label for="nome">Nome</label>
							<div class="input-group col-lg-12">
									<input class="form-control" type="text" name="nome" id="nome" placeholder="Nome">
							</div>
							<label for="email">Email</label>
							<div class="input-group col-lg-12">
							<span class="input-group-addon">@</span>  
							    <input type="email" id="email" name="email" class="form-control" placeholder="Email">
						    </div>
							<label for="telefone">Telefone</label>
							<div class="input-group col-lg-12">  
							    <input type="text" id="telefone" name="telefone" class="form-control" placeholder="Telefone com codigo de area">
						    </div>
							<label for="mensagem">Mnesagem</label>
							<div class="input-group col-lg-12">  
							    <textarea id="mensagem" name="mensagem" class="form-control" cols="60" rows="4" placeholder="Mensagem"></textarea>
						    </div>
							<button class="btn btn-primary" type="submit" id="btn-enviar">Enviar</button>
					</form>
				</div>
			</div>	
		</div>
    </section>
	<footer class="container">
		<address>
			<strong>Transportadora Free Jazz</strong><br>
			Rua Conselheiro Laurindo, 809, Centro<br>
			Curitiba - PR<br>
			Tel. (41)3078 - 6864
		</address>
		<address>
			Email:contato@transportesfj.com.br<br>
		</address>
	</footer>
    <script src="js/jquery.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>
	<script src="js/navbar-animation-fix.js"></script> 
	<script>
			var clique = document.getElementById("login");
			var div = document.getElementById("acesso");
			div.style.display = "none";
			clique.onclick = function (e) {
				 e.preventDefault();
				 if(div.style.display == "none") {
					div.style.display = "block";
				} else {
					div.style.display = "none";
				}
			};
    </script>  
</body>
</html>