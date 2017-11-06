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

		<script src="assets/js/jquery.js"></script>
		<script src="assets/js/bootstrap.min.js"></script>

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
						<a class="brand" href="/">Мониторинг</a>
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
						</div>
						<div class="navbar-user">
							<ul class="nav">
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?=$Auth->user['fio']?> <b class="caret"></b></a>
									<ul class="dropdown-menu">
										<li><a href="auth/logout.php">Выйти</a></li>
									</ul>
								</li>
							</ul>
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

		<div class="modal-overlay"></div>

		<?if($_GET['view'] === 'clients' && $_GET['action'] === 'edit'):?>
		<!-- Модалка для VLAN -->
		<div id="modal-vlan" class="modal modal-add-vlan">
			<button type="button" class="btn modal-close js-modal-close" data-modal="modal-vlan">Закрыть</button>
			<div class="modal-content">
				<div class="modal-title">
					<h2>Добавить VLAN</h2>
				</div>
				<div class="modal-container">
					<div class="modal-note"></div>
					<form id="form-vlan" class="form">
						<div class="form__group row">
							<div class="col col-12">
								<label>VLAN</label>
								<input class="form-control" name="vlan" type="text" value="" />
							</div>
						</div>
						<div class="form__group row">
							<div class="col col-12">
								<label>Скорость</label>
								<input class="form-control" name="speed" type="text" value="" />
							</div>
						</div>
						<div class="form__group form__group-btn row">
							<div class="col col-12 text-right">
								<button type="submit" class="btn btn-success js-save-vlan">Сохранить</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- Модалка для IP -->
		<div id="modal-ip" class="modal modal-add-ip">
			<button type="button" class="btn modal-close js-modal-close" data-modal="modal-ip">Закрыть</button>
			<div class="modal-content">
				<div class="modal-title">
					<h2>Добавить IP</h2>
				</div>
				<div class="modal-container">
					<div class="modal-note"></div>
					<form id="form-ip" class="form">
						<div class="form__group row">
							<div class="col col-12">
								<label>IP</label>
								<input class="form-control" name="ip" type="text" value="" />
							</div>
						</div>
						<div class="form__group row">
							<div class="col col-12">
								<?if(count($listVLAN)):?>
								<label>VLAN</label>
								<select class="form-control" name="vlanID">
									<?foreach($listVLAN as $vlan):?>
									<option value="<?=$vlan['UID']?>"><?=$vlan['value']?></option>
									<?endforeach;?>
								</select>
								<?else:?>
								<p>У клиента нет VLAN, сначала <a class="js-add-vlan">добавьте VLAN</a></p>
								<?endif;?>
							</div>
						</div>
						<div class="form__group row">
							<div class="col col-12">
								<label>Скорость</label>
								<input class="form-control" name="speed" type="text" value="" />
							</div>
						</div>
						<div class="form__group form__group-btn row">
							<div class="col col-12 text-right">
								<button type="submit" class="btn btn-success js-save-ip">Сохранить</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<?endif;?>

		<script src="assets/js/classie.js"></script>
		<script src="app.js"></script>
		<script>
		$(function() {
			var modalOverlay = $('.modal-overlay');
			$('.js-modal-close').on('click', function() {
				$('#'+$(this).data('modal')).removeClass('_show');
				modalOverlay.removeClass('_show');
			});
		});
		</script>
	</body>
</html>