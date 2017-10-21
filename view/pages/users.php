<h1 class="title title-main"><?=$title;?></h1>

<?if(isset($_GET['action']) && $_GET['action'] !== '' && $_GET['action'] !== 'delete'):?>

<div class="b-form">
	<form class="form" action="api/user.php?action=<?=$_GET['action']?><?=$id ? '&id='.$id : ''?>" method="POST">
		<div class="row">
			<div class="col col-10">
				<div class="form__group row">
					<div class="col col-6">
						<label>ФИО</label>
						<input class="form-control" name="fio" type="text" value="<?=$item['fio']?>" />
					</div>
					<div class="col col-6">
						<label>Телефон</label>
						<input class="form-control" name="phone" type="text" value="<?=$item['phone']?>" />
					</div>
				</div>

				<div class="form__group row">
					<div class="col col-6">
						<label>Логин</label>
						<input class="form-control" name="login" type="text" value="<?=$item['login']?>" />
					</div>
					<div class="col col-6">
						<label>Пароль</label>
						<input class="form-control" name="password" type="text" value="<?=$item['password']?>" />
					</div>
				</div>
			</div>

			<div class="col col-2">
				<div class="form__group row">
					<?if($_GET['action'] !== 'add'):?>
					<div class="col col-12">
						<label>UID</label>
						<input class="form-control" type="text" value="<?=$item['UID']?>" disabled />
						<input class="form-control" name="UID" type="hidden" value="<?=$item['UID']?>" />
					</div>
					<?endif?>
				</div>

				<div class="form__group row">
					<div class="col col-12">
						<label>Роль</label>
						<select class="form-control" name="role_ID">
							<?foreach($ROLES as $role):?>
							<option value="<?=$role['id']?>" <?if($role['id'] === $item['role_ID']):?>selected<?endif;?>><?=$role['name']?></option>
							<?endforeach;?>
						</select>
					</div>
				</div>

				<div class="form__group row">
					<div class="col col-12">
						<input id="isActive" type="checkbox" value="1" name="is_active" <?if($item['is_active'] > 0): echo 'checked'; endif;?> />
						<label class="label-normal" for="isActive">активен</label>
					</div>
				</div>

				<div class="form__group row">
					<div class="col col-12">
						<label>Дата создания</label>
						<input class="form-control" type="text" value="<?=$_GET['action'] === 'add' ? date('Y-m-d H:i:s') : $item['date_created'];?>" disabled />
						<input name="date_created" type="hidden" value="<?=$_GET['action'] === 'add' ? date('Y-m-d H:i:s') : $item['date_created'];?>" />
					</div>
				</div>

				<div class="form__group form__group-btn row">
					<div class="col col-12">
						<button type="submit" class="btn btn-success js-save">Сохранить</button>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>

<?else:?>

<div class="b-table">
	<a href="index.php?view=users&action=add" class="btn btn-primary">Создать</a>
	<table class="table table-striped table-hover">
		<thead>
			<tr>
				<th>#</th>
				<th>Пользователь</th>
				<th>Телефон</th>
				<th>Логин</th>
				<th>Пароль</th>
				<th>Права</th>
				<th>Статус</th>
				<th>Cоздан</th>
			</tr>
		</thead>
		<tbody>
		<?foreach($items as $item):?>
			<tr>
				<td>
					<a href="api/user.php?action=delete&id=<?=$item['id'];?>" class="btn btn-small btn-danger">Удалить</a>
				</td>
				<td><a href="index.php?view=users&action=edit&id=<?=$item['id'];?>"><?=$item['fio']?></a></td>
				<td><?=$item['phone']?></td>
				<td><?=$item['login']?></td>
				<td><?=$item['password']?></td>
				<td><?=$item['role_ID']?></td>
				<td><span class="badge badge-<?=$item['is_active'] == 0 ? 'important' : 'success'?>"><?=$item['is_active'] == 0 ? 'Неактивен' : 'Активен'?></span></td>
				<td><?=$item['date_created']?></td>
			</tr>
		<?endforeach;?>
		</tbody>
	</table>
</div>

<?endif;?>