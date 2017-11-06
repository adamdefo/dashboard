<h1 class="title title-main"><?=$title;?></h1>
<hr/>
<?if(isset($_GET['action']) && $_GET['action'] !== '' && $_GET['action'] !== 'delete'):?>

<div class="b-form">
	<form class="form" action="api/commutator.php?action=<?=$_GET['action'];?>" method="POST">
		<div class="row">
			<div class="col col-10">
				<div class="form__group row">
					<div class="col col-4">
						<label>Модель</label>
						<input class="form-control" name="model" type="text" value="<?=$item['model']?>" />
					</div>
					<div class="col col-4">
						<label>IP</label>
						<input class="form-control" name="ip" type="text" value="<?=$item['ip'] ? $item['ip'] : '192.168.0.1';?>" />
					</div>
					<div class="col col-4">
						<label>Прошивка</label>
						<input class="form-control" name="firmware" type="text" value="<?=$item['firmware'] ? $item['firmware'] : 'прошивка';?>" />
					</div>
				</div>
					
				<div class="form__group row">
					<div class="col col-8">
						<label>Адрес</label>
						<input class="form-control" name="adress" type="text" value="<?=$item['adress'] ? $item['adress'] : 'адрес';?>" />
					</div>
					<div class="col col-1">
						<label>Сегмент</label>
						<input class="form-control" name="segment" type="text" value="<?=$item['segment']?>" />
					</div>
					<div class="col col-3">
						<label>Количество портов</label>
						<input class="form-control" name="count_client_ports" type="text" value="<?=$item['count_client_ports']?>" />
					</div>
				</div>
					
				<div class="form__group row">
					<div class="col col-6">
						<label>Родительский коммутатор</label>
						<select class="form-control" name="parent_ID">
							<option value="0">нет</option>
							<?if(count($listCommutators)):?>
								<?foreach($listCommutators as $commutator):?>
								<option value="<?=$commutator['UID']?>" <?if($commutator['UID'] === $item['parent_ID']):?>selected<?endif;?>><?=$commutator['UID']?></option>
								<?endforeach;?>
							<?endif;?>
						</select>
					</div>
					<div class="col col-6">
						<label>Родительский порт</label>
						<input class="form-control" name="parent_PORT" type="text" value="<?=$item['parent_PORT'] ? $item['parent_PORT'] : '0';?>" />
					</div>
				</div>

				<div class="form__group row">
					<div class="col col-12">
						<label>Примечание</label>
						<textarea class="form-control textarea" rows="4" name="note"><?=$item['note']?></textarea>
					</div>
					<div class="col col-12">
						<label>Открытое примечание</label>
						<textarea class="form-control textarea" rows="4" name="note_open"><?=$item['note_open']?></textarea>
					</div>
					<div class="col col-12">
						<label>Комментарий</label>
						<textarea class="form-control textarea" rows="4" name="comment"><?=$item['comment']?></textarea>
					</div>
				</div>
			</div>
			<div class="col col-2">
				<div class="form__group row">

					<div class="col col-12">
						<label>UID</label>
						<input class="form-control" <?if($_GET['action'] === 'add'):?>name="UID"<?endif;?> type="text" value="<?=$item['UID']?>" <?if($_GET['action'] === 'edit'):?>disabled<?endif;?> />
	<?if($_GET['action'] === 'edit'):?><input class="form-control" name="UID" type="hidden" value="<?=$item['UID']?>" /><?endif;?>
					</div>
					<?if($_GET['action'] !== 'add'):?>
					<?endif?>
				</div>
				<div class="form__group row">
					<div class="col col-12">
						<label>Дата создания</label>
						<input class="form-control" type="text" value="<?=$_GET['action'] === 'add' ? date('Y-m-d H:i:s') : $item['date_created'];?>" disabled />
						<input name="date_created" type="hidden" value="<?=$_GET['action'] === 'add' ? date('Y-m-d H:i:s') : $item['date_created'];?>" />
					</div>
				</div>
				<div class="form__group row">
					<div class="col col-12">
						<label>Тип подключения</label>
						<select class="form-control" name="connection_type_ID">
							<option value="0">нет</option>
							<?foreach($CONNECTION_TYPE as $type):?>
							<option value="<?=$type['id']?>" <?if($type['id'] === $item['connection_type_ID']):?>selected<?endif;?>><?=$type['name']?></option>
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
	<a href="index.php?view=commutators&action=add" class="btn btn-primary">Создать</a>
	<table class="table table-striped table-hover">
		<thead>
			<tr>
				<th>UID</th>
				<th>Модель</th>
				<th>IP</th>
				<th>Прошивка</th>
				<th>Адрес</th>
				<th>Род. коммутатор</th>
				<th>Сегмент</th>
				<th>Тип подключения</th>
				<th>Кол-во портов</th>
				<th>Статус</th>
				<th>Примечание</th>
				<th>Откр. примечание</th>
				<th>Комментарий</th>
				<th>Создан</th>
				<th>Обновлен</th>
			</tr>
		</thead>
		<tbody>
		<?foreach($items as $item):?>
			<tr>
				<td>
					<a href="index.php?view=commutators&action=edit&id=<?=$item['id'];?>"><?=$item['UID'];?></a>
					<a href="index.php?view=commutators&action=edit&id=<?=$item['id'];?>" class="btn btn-small btn-primary">Изменить</a>
					<!-- <a href="api/commutator.php?action=delete&id=<?=$item['id'];?>" class="btn btn-small btn-danger">Удалить</a> -->
				</td>
				<td><?=$item['model']?></td>
				<td><?=$item['ip']?></td>
				<td><?=$item['firmware']?></td>
				<td><?=$item['adress']?></td>
				<td><?=is_null($item['parent_ID']) ? 'нет' : $item['parent_ID'];?></td>
				<td><?=$item['segment']?></td>
				<td><?=$item['connection_type_ID']?></td>
				<td><?=$item['count_client_ports']?></td>
				<td><?=$item['status_ID']?></td>
				<td><?=$item['note']?></td>
				<td><?=$item['note_open']?></td>
				<td><?=$item['comment']?></td>
				<td><?=$item['date_created']?></td>
				<td><?=$item['date_last_update']?></td>
			</tr>
		<?endforeach;?>
		</tbody>
	</table>
</div>

<?endif;?>