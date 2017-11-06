<h1 class="title title-main"><?=$title;?></h1>

<?if(isset($_GET['action']) && $_GET['action'] !== '' && $_GET['action'] !== 'delete'):?>

<div class="b-form">
	<form class="form" action="api/task.php?action=<?=$_GET['action'];?>" method="POST">
		<div class="row">
			<div class="col col-10">
				<div class="form__group row">
					<div class="col col-6">
						<label>Наименование</label>
						<input class="form-control" name="name" type="text" value="<?=$item['name']?>" />
					</div>
					<div class="col col-6">
						<label>Менеджер</label>
						<input class="form-control" name="manager" type="text" value="<?=$item['manager']?>" />
					</div>
				</div>

				<div class="form__group row">
					<div class="col col-12">
						<label>Описание</label>
						<textarea class="form-control textarea" rows="4" name="description"><?=$item['description']?></textarea>
					</div>
					<div class="col col-12">
						<label>Отчёт</label>
						<textarea class="form-control textarea" rows="4" name="report"><?=$item['report']?></textarea>
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
						<label>Объект</label>
						<select class="form-control" name="object_ID">
							<option value="0">нет</option>
							<?foreach($listObjects as $obj):?>
							<option value="<?=$obj['UID']?>" <?if($obj['UID'] === $item['object_ID']):?>selected<?endif;?>><?=$obj['id']?></option>
							<?endforeach;?>
						</select>
					</div>
				</div>

				<div class="form__group row">
					<div class="col col-12">
						<label>Исполнитель</label>
						<select class="form-control" name="user_ID">
							<option value="0">не назначен</option>
							<?foreach($listUsers as $user):?>
							<option value="<?=$user['id']?>" <?if($user['id'] === $item['user_ID']):?>selected<?endif;?>><?=$user['fio']?></option>
							<?endforeach;?>
						</select>
					</div>
				</div>

				<div class="form__group row">
					<div class="col col-12">
						<label>Статус</label>
						<select class="form-control" name="status_ID">
							<?foreach($COMMUTATOR_STATUS as $status):?>
							<option value="<?=$status['id']?>" <?if($status['id'] === $item['status_ID']):?>selected<?endif;?>><?=$status['name']?></option>
							<?endforeach;?>
						</select>
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
	<a href="index.php?view=tasks&action=add" class="btn btn-primary">Создать</a>
	<table class="table table-striped table-hover">
		<thead>
			<tr>
				<th>Удалить</th>
				<th>Наименование</th>
				<th>Менеджер</th>
				<th>Исполнитель</th>
				<th>ID объекта</th>
				<th>Статус</th>
				<th>Описание</th>
				<th>Отчёт</th>
				<th>Cоздан</th>
				<th>Обновлен</th>
			</tr>
		</thead>
		<tbody>
		<?foreach($items as $item):?>
			<tr>
				<td>
					<a href="api/task.php?action=delete&id=<?=$item['id'];?>" class="btn btn-small btn-danger">Удалить</a>
				</td>
				<td><a href="index.php?view=tasks&action=edit&id=<?=$item['id'];?>">#<?=$item['UID'];?>:<?=$item['name']?></a></td>
				<td><?=$item['manager']?></td>
				<td><?=$item['user_ID']?></td>
				<td><?=$item['object_ID']?></td>
				<td><?=$item['status_ID']?></td>
				<td><?=$item['description']?></td>
				<td><?=$item['report']?></td>
				<td><?=$item['date_created']?></td>
				<td><?=$item['date_last_update']?></td>
			</tr>
		<?endforeach;?>
		</tbody>
	</table>
</div>

<?endif;?>