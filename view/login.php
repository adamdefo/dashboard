<!doctype html>
<html lang="ru">
	<head>
		<title>Авторизация</title>
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

		<!--[if IE]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
	</head>

	<body>
		<div class="container-fluid">
			<main class="main">
				<div class="b-form">
					<form class="form form-authorization" action="auth/login.php" method="POST">
						<h2>Авторизация</h2>
						<hr/>
						<div class="form__inner">
							<div class="form__group">
								<label for="login">Логин</label>
								<input id="login" class="form-control" name="login" type="text" value="<?=$_POST['login']?>" />
							</div>
							<div class="form__group">
								<label for="password">Пароль</label>
								<input id="password" class="form-control" name="password" type="password" value="<?=$_POST['password']?>" />
							</div>
							<div class="form__group form__group-btn">
								<button class="btn btn-small btn-primary" type="submit">Войти</button>
							</div>
						</div>
					</form>
				</div>
			</main>
		</div>

		<script src="assets/js/classie.js"></script>
		<script>
		$(function() {
			console.log('Авторизация');
		});
		</script>
	</body>
</html>