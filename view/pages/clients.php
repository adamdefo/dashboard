<h1 class="title title-main"><?=$title;?></h1>
<hr/>
<?if(isset($_GET['action']) && $_GET['action'] !== '' && $_GET['action'] !== 'delete'):?>

<div class="b-form">
	<form id="formClient" class="form" action="api/client.php?action=<?=$_GET['action'];?>" method="POST">
		<div class="row">
			<div class="col col-10">
				<div class="form__group row">
					<div class="col col-4">
						<label for="fullName">Полное наименование</label>
						<input id="fullName" class="form-control" name="full_name" type="text" value="<?=$item['full_name']?>" />
					</div>
					<div class="col col-4">
						<label for="shortName">Краткое наименование</label>
						<input id="shortName" class="form-control" name="short_name" type="text" value="<?=$item['short_name']?>" />
					</div>
					<div class="col col-4">
						<label for="director">Директор</label>
						<input id="director" class="form-control" name="director" type="text" value="<?=$item['director']?>" />
					</div>
				</div>
					
				<div class="form__group row">
					<div class="col col-4">
						<label>ИНН</label>
						<input class="form-control" name="inn" type="text" value="<?=$item['inn'] ? $item['inn'] : 'ИНН';?>" />
					</div>
					<div class="col col-4">
						<label>Контактное лицо</label>
						<input class="form-control" name="contact_person" type="text" value="<?=$item['contact_person']?>" />
					</div>
					<div class="col col-4">
						<label>Телефон контактного лица</label>
						<input class="form-control" name="contact_person_phone" type="text" value="<?=$item['contact_person_phone']?>" />
					</div>
				</div>

				<div class="form__group row">
					<div class="col col-6">
						<label>Юр. адрес</label>
						<input class="form-control" name="ul_adress" type="text" value="<?=$item['ul_adress']?>" />
					</div>
					<div class="col col-6">
						<label>Факт. адрес</label>
						<input class="form-control" name="fact_adress" type="text" value="<?=$item['fact_adress']?>" />
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
				<?if($_GET['action'] !== 'add'):?>
				<div class="form__group row">
					<div class="col col-12">
						<label>UID</label>
						<input class="form-control" type="text" value="<?=$item['UID']?>" disabled />
						<input id="UID" class="form-control" name="UID" type="hidden" value="<?=$item['UID']?>" />
					</div>
				</div>
				<?endif?>

				<div class="form__group row">
					<div class="col col-12">
						<label>Дата создания</label>
						<input class="form-control" type="text" value="<?=$_GET['action'] === 'add' ? date('Y-m-d H:i:s') : $item['date_created'];?>" disabled />
						<input name="date_created" type="hidden" value="<?=$_GET['action'] === 'add' ? date('Y-m-d H:i:s') : $item['date_created'];?>" />
					</div>
				</div>

				<?if($_GET['action'] !== 'add'):?>
				<div class="form__group row">
					<div class="col col-12">
						<label>Дата изменения</label>
						<input class="form-control" type="text" value="<?=$item['date_last_update']?>" disabled />
					</div>
				</div>
				<?endif?>

				<div class="form__group row">
					<div class="col col-12">
						<label>Дата подключения</label>
						<input class="form-control" name="connection_date" type="text" value="<?=$_GET['action'] === 'add' ? date('Y-m-d H:i:s') : $item['connection_date'];?>" />
					</div>
				</div>

				<div class="form__group row">
					<div class="col col-12">
						<label>Тип соединения</label>
						<select class="form-control" name="connection_type_ID">
							<option value="0">нет</option>
							<?foreach($CONNECTION_TYPE as $type):?>
							<option value="<?=$type['id']?>" <?if($type['id'] === $item['connection_type_ID']):?>selected<?endif;?>><?=$type['name']?></option>
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

	<?if($_GET['action'] === 'edit'):?>
	<hr/>
	<div class="b-ip">
		<h3>Подключенные IP <button class="btn btn-small btn-primary js-add-ip">Добавить IP</button></h3>
		<table id="tbl-ip" class="tbl table table-striped table-hover">
		<?if(count($listIP)):?>
			<thead class="tbl__head">
				<tr>
					<th>IP</th>
					<th></th>
				</tr>
			</thead>
			<tbody class="tbl__body">
				<?foreach($listIP as $ip):?>
				<tr>
					<td><?=$ip['ip']?></td>
					<td class="text-right">
						<button type="button" class="btn btn-small btn-danger js-off-ip" data-uid="<?=$ip['UID']?>">Отключить</button>
					</td>
				</tr>
				<?endforeach;?>
			</tbody>
		<?else:?>
			<tbody class="tbl__body">
				<tr class="tr-null">
					<td colspan="2"><p>Ни одного IP не подключено</p></td>
				</tr>
			</tbody>
		<?endif;?>
		</table>
	</div>
	<hr/>
	<div class="b-vlan">
		<h3>Подключенные VLAN <button class="btn btn-small btn-primary js-add-vlan">Добавить VLAN</button></h3>
		<table id="tbl-vlan" class="tbl table table-striped table-hover">
		<?if(count($listVLAN)):?>
			<thead class="tbl__head">
				<tr>
					<th>VLAN</th>
					<th></th>
				</tr>
			</thead>
			<tbody class="tbl__body">
				<?foreach($listVLAN as $vlan):?>
				<tr>
					<td><?=$vlan['value']?></td>
					<td class="text-right">
						<button type="button" class="btn btn-small btn-danger js-off-vlan" data-uid="<?=$vlan['UID']?>">Отключить</button>
					</td>
				</tr>
				<?endforeach;?>
			</tbody>
		<?else:?>
			<tbody class="tbl__body">
				<tr class="tr-null">
					<td colspan="2"><p>Ни одного VLAN не подключено</p></td>
				</tr>
			</tbody>
		<?endif;?>
		</table>
	</div>
	<?endif;?>
</div>

<?if($_GET['action'] === 'edit'):?>
<script src="app/clients.js"></script>
<?endif;?>

<?else:?>

<div class="b-table">
	<a href="index.php?view=clients&action=add" class="btn btn-primary">Создать</a>
	<table class="table table-striped table-hover">
		<thead>
			<tr>
				<!-- <th>UID</th> -->
				<th>Наименование</th>
				<!-- <th>Краткое наименование</th> -->
				<th>Директор</th>
				<th>ИНН</th>
				<th>Контакты</th>
				<th>Юр. адрес</th>
				<!-- <th>Факт. адрес</th> -->
				<th>Дата подключения</th>
				<th>Тип подключения</th>
				<th>Примечание</th>
				<!-- <th>Откр. примечание</th>
				<th>Комментарий</th>
				<th>Дата создания</th>
				<th>Дата изменения</th> -->
			</tr>
		</thead>
		<tbody>
		<?foreach($items as $item):?>
			<tr>
				<!-- <td>
					<a href="index.php?view=clients&action=edit&id=<?=$item['id'];?>"><?=$item['UID'];?></a>
					<a href="api/client.php?action=delete&id=<?=$item['id'];?>" class="btn btn-small btn-danger">Удалить</a>
				</td> -->
				<td><a href="index.php?view=clients&action=edit&id=<?=$item['id'];?>"><?=$item['full_name']?></a></td>
				<!-- <td><?=$item['short_name']?></td> -->
				<td><?=$item['director']?></td>
				<td><?=$item['inn']?></td>
				<td><?=$item['contact_person']?><br/><?=$item['contact_person_phone']?></td>
				<td><?=$item['ul_adress']?></td>
				<!-- <td><?=$item['fact_adress']?></td> -->
				<td><?=$item['connection_date']?></td>
				<td><?=$item['connection_type_ID']?></td>
				<td><?=$item['note']?></td>
				<!-- <td><?=$item['note_open']?></td>
				<td><?=$item['comment']?></td>
				<td><?=$item['date_created']?></td>
				<td><?=$item['date_last_update']?></td> -->
			</tr>
		<?endforeach;?>
		</tbody>
	</table>
</div>

<?endif;?>