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

	<a href="index.php?view=commutators&action=add" class="btn btn-primary">Создать</a>

	<?
		echo '<ul class="commutators-tree">';
		$commutatorsTree->outTree(0, 0);
		echo '</ul>';
	?>

<?endif;?>

<script src="app/commutators.js"></script>
