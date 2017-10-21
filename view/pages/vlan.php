<h1 class="title title-main"><?=$title;?></h1>

<?if(isset($_GET['action']) && $_GET['action'] !== '' && $_GET['action'] !== 'delete'):?>

<div class="b-form">
	<form class="form" action="api/vlan.php?action=<?=$_GET['action'];?>" method="POST">
		<div class="row">
			<div class="col col-10">
				<div class="form__group row">
					<div class="col col-6">
						<label>VLAN</label>
						<input class="form-control" name="value" type="text" value="<?=$item['value']?>" />
					</div>
					<div class="col col-6">
						<label>Скорость</label>
						<input class="form-control" name="speed" type="text" value="<?=$item['speed']?>" />
					</div>
				</div>

				<div class="form__group row">
					<div class="col col-6">
						<label>Клиент</label>
						<select class="form-control" name="client_ID">
							<option value="0">нет</option>
							<?foreach($clients as $client):?>
							<option value="<?=$client['UID']?>" <?if($client['UID'] === $item['client_ID']):?>selected<?endif;?>><?=$client['full_name']?></option>
							<?endforeach;?>
						</select>
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
						<input id="status" type="checkbox" value="1" name="status" <?if($item['status'] > 0): echo 'checked'; endif;?> />
						<label class="label-normal" for="status">подключен</label>
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
	<a href="index.php?view=vlan&action=add" class="btn btn-primary">Создать</a>
	<table class="table table-striped table-hover">
		<thead>
			<tr>
				<th>UID</th>
				<th>VLAN</th>
				<th>Скорость</th>
				<th>Статус</th>
				<th>ID клиента</th>
				<th>Cоздан</th>
				<th>Обновлен</th>
			</tr>
		</thead>
		<tbody>
		<?foreach($items as $item):?>
			<tr>
				<td>
					<a href="index.php?view=vlan&action=edit&id=<?=$item['id'];?>" class="btn btn-small btn-primary">Изменить</a>
					<!-- <a href="api/ip_adress.php?action=delete&id=<?=$item['id'];?>" class="btn btn-small btn-danger">Удалить</a> -->
				</td>
				<td><a href="index.php?view=vlan&action=edit&id=<?=$item['id'];?>"><?=$item['value']?></a></td>
				<td><?=$item['speed']?></td>
				<td><span class="badge badge-<?=$item['status'] == 0 ? 'important' : 'success'?>"><?=$item['status'] == 0 ? 'Отключен' : 'Подключен'?></span></td>
				<td><?=$item['client_ID']?></td>
				<td><?=$item['date_created']?></td>
				<td><?=$item['date_last_update']?></td>
			</tr>
		<?endforeach;?>
		</tbody>
	</table>
</div>

<?endif;?>