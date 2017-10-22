<!doctype html>
<html lang="ru">
	<head>
		<title><?=$title?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<!-- <meta http-equiv="X-UA-Compatible" content="IE=Edge"> -->
		<!-- <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=yes"> -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		<meta name="author" content="<?=$author?>">
		<meta name="copyright" content="<?=$copyright?>">

		<meta name="language" content="Russian">

		<link rel="icon" href="/favicon.ico" type="image/x-icon">
		<link rel="stylesheet" href="assets/css/bootstrap.css" />
		<link rel="stylesheet" href="assets/css/bootstrap-responsive.css" />
		<link rel="stylesheet" href="assets/css/styles.css" />

		<!--[if IE]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
	</head>

	<body>
		<header class="header">
			<div class="navbar navbar-inverse navbar-fixed-top">
				<div class="navbar-inner">
					<div class="container-fluid">
						<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</a>
						<a class="brand" href="#">Мониторинг</a>
						<div class="nav-collapse collapse">
							<ul class="nav">
								<?foreach($mainMenu as $link):?>
<li <?if($_GET['view'] === $link['url']):?>class="active"<?endif;?>><a href="index.php?view=<?=$link['url']?>"><?=$link['name']?></a></li>
								<?endforeach;?>
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown">Справочники <b class="caret"></b></a>
									<ul class="dropdown-menu">
										<li><a href="index.php?view=ip_adress">IP адреса</a></li>
										<li><a href="index.php?view=vlan">VLAN</a></li>
										<li class="divider"></li>
										<li class="nav-header">Состояния</li>
										<!-- <li><a href="index.php?view=connection_type">Типы подключений</a></li>
										<li><a href="index.php?view=commutator_status">Статусы коммутаторов</a></li>
										<li><a href="index.php?view=task_status">Статусы задач</a></li> -->
									</ul>
								</li>
							</ul>
							<!-- <form class="navbar-form pull-right">
								<input class="span2" type="text" name="login" placeholder="Логин">
								<input class="span2" type="password" name="password" placeholder="Пароль">
								<button type="submit" class="btn btn-primary">Войти</sup>
							</form> -->
						</div>
					</div>
				</div>
			</div>
		</header>

		<div class="page">
			<div class="container-fluid">
				<main class="main">
					<? include 'view/pages/'. $view .'.php'; ?>
				</main>
			</div>
		</div>

		<script src="assets/js/jquery.js"></script>
		<script src="assets/js/bootstrap.min.js"></script>
		<script src="app.js"></script>
	</body>
</html>