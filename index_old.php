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
    <header>  
        <nav class="navbar navbar-default" id="menu">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" 
				data-target="#collapse-navbar" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#"><b>Paulo Roberto Nunes</b></a>
			</div>
			<div class="collapse navbar-collapse col-lg-6" id="collapse-navbar">
				<ul class="nav navbar-nav mr-auto">
					<li><a href="#sobre-nos">Sobre Mim</a></li>
					<li><a href="#nossos-servicos">Meu Curriculo</a></li>
					<li><a href="#onde-estamos">Onde Moro</a></li>
					<li><a href="#contato">Contato</a></li>
					<li><a href="#login" id="login"><img src="img/login_sistema.png"></a></li>
					<li><a href="#app_baixa" id="bt_baixa_app"><img src="img/celular_v_cel.png"></a></li>
				</ul>
			</div>
		</nav>
		<!--formulario para baixa do app -->
		<form name="form1" action="baixar_app.php" method="post" id="">
            <div id="dados_baixa">
				<p><input type="text" name="usua" size="15" maxlength="15" id="usua" placeholder="usuario"/></p>
				<p><input type="password" name="sena" size="15" maxlength="15" id="sena" placeholder="senha"/></p>
				<p><button type="submit" id="input_baixar">Baixa App</button></p>
			</div>
		</form>
		
		<!--formulario para entrada no sistema -->
		
		<form name="formu0" action="confere_acesso.php" method="post" id="">
			<div class="row">
				<div class="container freejazz-bannerWrapper">
					<div class="freejazz-banner">
						<p id="empresa">Paulo Roberto Nunes</p>
						<p id="propaganda">Prestando serviços de qualidade desde 1980.</p>
						<button class="btn btn-primary btn-lg" id="rastrear">Rastrear entrega</button>
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
	<section id="sobre-nos" >
		   <div id="div-sn" class="container">Sobre Mim</div>
				<div id="texto-sobrenos" class="container">
				    <p class="texto-sn">Meu nome é <b>Paulo Roberto Nunes</b> 
					natuaral de Florianópolis - SC. Resido na Rua dos Ipês, numero 431, no municipio de  
					São José. <b>Sou Formado em Administração de empresas pela UFSC - Universidade Federal de  
					Santa Catarina (1986). Sou Mestre em Administração, tambem pela UFSC (2006), e fui professor  
					daquela Universidade por um ano como professor substituto (2001). </b>Lecionei na ocasioao adiciplina
					de introdução a informatica, para alunos do curso de Contabilidade e Administração.</p>
					<p class="texto-sn">Dentro da minha formação de Administrador trabalhei 80% do meu tempo como especialista 
					em Organização Sistema e Métodos - O&M. Os outros 20% da minha vida profissional trabalhei como chefe
					das áreas de O&M e informática. A partir de 1998, aproximadamente, comecei minha caminhada como programador (trabalho paralelo). 
					Comecei a programar com DBASE depois CLIPPER, e hoje desenvolvo aplicações para web, usando HTML PHP, JAVASCRIP e BOOTSTRAP.</p>
					
					<p class="texto-sn">Nos anos 90 Ministrei pela SUCESU/SC vários cursos fechados para profissionais da área
					de informática (computação), ensinando-os técnicas que permitissem ter uma melhor postura diante do usuário, objetivando
					sucesso tanto na fase de levantamento de dados, para a construção da aplicação, como também para fase 
					de implantação da mesma e treinamento do usuario.</p>
				</div>							
    </section>
	<section id="nossos-servicos">
		<div class="container" id="texto-nossos-servicos">
		    <div id="div-serv">Meu Curriculo</div>
			    <br>
				<p class="texto-servico">Como ficou claro na descrição feita nos itens acima, 
					minha formação acadêmica é Administração de empresas. Entretanto no decorrer 
					da minha vida profissional busquei conhecimento na área de tecnologia da  
					informação, porque o que eu fazia, na verdade, era desenvolver e implantar  
					procedimentos e sistemáticas de trabalho, só que na forma manual.
					Como o conhecimento da tecnologia da informação tinha tudo a ver com que 
					eu fazia, me envolvi com programação, em paralelo a minha atividade 
					principal nas organizações em que trabalhei.</p>

				<p class="texto-servico">Minha formação e principais ativiades ligada a tecnologia da informação são:</p>
				<br>
				<div class="row" id="descricao-servicos">
					<div class="col-sm-6">	
						<p id="serv-parag-3" class="glyphicon glyphicon-ok item-servico"> Formado em Administração de Empresa - UFSC - 1980;</p>
						<p id="serv-parag-4" class="glyphicon glyphicon-ok item-servico"> Mestrado em Administração -  UFSC - 2007;</p>
						<p id="serv-parag-5" class="glyphicon glyphicon-ok item-servico"> PRODASC (Atual CIASC) Chefe da área de O&M - 1980 - 1983;</p>
						<p id="serv-parag-6" class="glyphicon glyphicon-ok item-servico"> USATI-PORTOBELLO - Chefe da área de O&M - 1983 - 1988;</p>
						<p id="serv-parag-7" class="glyphicon glyphicon-ok item-servico"> SESI-SC - Chefe da área de O&M e Informática - 1989 - 1994;</p>
					</div>
					<div class="col-sm-6">
						<p id="serv-parag-8" class="glyphicon glyphicon-ok item-servico"> UFSC - Professor Substituto - Introdução a Informática - 1995;</p>
						<p id="serv-parag-9" class="glyphicon glyphicon-ok item-servico"> Secrataria de Saude do Estado de SC - programador (Contratado) 1996 - 1997;</p>
						<p id="serv-parag-10" class="glyphicon glyphicon-ok item-servico"> FITOFARMA Distribuidora - Diretor Administrativo - 1999 - 2003;</p>
						<p id="serv-parag-11" class="glyphicon glyphicon-ok item-servico"> PROSALUTE Distribuidora - Diretor Administrativo -2004 - 07/2006;</p>
						<p id="serv-parag-12" class="glyphicon glyphicon-ok item-servico"> CELESC - Centrais Eletricas de SC - Auditor Interno - 08/2006 - 2019.</p>
					</div>
					<p id="serv-parag-13" style="font-size: 16px; color: red; font-weight: 700; text-align: center;"> TRABALHO COMO PROGRAMADOR DESDE 1986 </P>
				</div>
		</div>
    </section>
	<section id="onde-estamos">
		<div class="container" id="texto-onde-estamos">
		    <div id="div-oe">Onde Moro</div>
			<div id="endereco">
			    <p id="ende-cidade">São José - SC</p>
				<p id="ende-rua" class="detalhe-ende">Rua Dos Ipês, 431</p>
				<p id="ende-comple" class="detalhe-ende">Bosque das Manões - São José</p>
				<p id="ende-cep" class="detalhe-ende">CEP 88108-440</p>
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
	<section id="contato">
		<div id="dados-contato">
		    <div id="div-titulo-contato">Contato</div>
			<div class="col-sm-6" id="div-dados-contato">
				<br>
				<p class="fones-contato cidade">São José /SC - CEP: 88108-440</p><br>
				<p class="fones-contato outros"><b>Fone:</b> (48) 99185-2147</p><br>
				<p class="fones-contato outros"><b>Email:</b> nunesp25@hotmail.com</p><br>
			</div>
			<div id="resposta">
				 
			</div>
			<div class="col-sm-5" id="div-form-contato">
				<form action="envia_email.php"  role="form" method="post">	
						<label for="nome">Nome</label>
						<div class="input-group col-lg-3">
							<input class="form-control" type="text" name="nome" id="nome" placeholder="Nome">
						</div>
						<label for="email">Email</label>
						<div class="input-group col-lg-3">
						<span class="input-group col-lg-3"></span>  
							<input type="text" id="email" name="email" class="form-control" placeholder="Email">
						</div>
						<label for="telefone">Telefone</label>
						<div class="input-group col-lg-3">  
							<input type="text" id="telefone" name="telefone" class="form-control" placeholder="Telefone com codigo de area">
						</div>
						<label for="mensagem">Mnesagem</label>
						<div class="input-group col-lg-3">  
							<textarea id="mensagem" name="mensagem" class="form-control" cols="60" rows="4" placeholder="Mensagem"></textarea>
						</div>
						<button class="btn btn-primary" type="submit" id="btn-enviar">Enviar</button>
				</form>
			</div>
		</div>
    </section>
	<footer>
		<address>
			<strong>Paulo Roberto Nunes</strong><br>
			Rua Dos Ipês, 431, São José<br>
			São José - SC - CEP: 88108-440<br>
			Tel. (48)99185 - 2147
		</address>
		<address>
			Email:nunesp25@hotmail.com<br>
		</address>
	</footer>
    <script src="js/jquery.js"></script>
	<!--<script src="bootstrap/js/bootstrap.min.js"></script>-->
	<script src="js/navbar-animation-fix.js"></script> 
	<script>
	        /* Trata o processo de login*/
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
			
			/* Trata o processo de baixa app*/
			var cliqueb = document.getElementById("bt_baixa_app");
			var divb = document.getElementById("dados_baixa");
			divb.style.display = "none";
			cliqueb.onclick = function (e) {
				 e.preventDefault();
				 if(divb.style.display == "none") {
					divb.style.display = "block";
				} else {
					divb.style.display = "none";
				}
			};
    </script>  
</body>
</html>