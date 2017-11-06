<h1 class="title title-main"><?=$title;?></h1>
<hr/>
<?if(isset($_GET['action']) && $_GET['action'] !== '' && $_GET['action'] !== 'delete'):?>

<div class="b-form">
	<form class="form" action="api/ip_adress.php?action=<?=$_GET['action'];?>" method="POST">
		<div class="row">
			<div class="col col-10">
				<div class="form__group row">
					<div class="col col-6">
						<label>IP</label>
						<input class="form-control" name="ip" type="text" value="<?=$item['ip']?>" />
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
					<div class="col col-6">
						<label>VLAN</label>
						<select class="form-control" name="vlan_ID">
							<option value="0">нет</option>
							<?foreach($VLAN as $vl):?>
							<option value="<?=$vl['UID']?>" <?if($vl['UID'] === $item['vlan_ID']):?>selected<?endif;?>><?='UID'.$vl['UID'].': '.$vl['value']?></option>
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
						<input id="status" type="checkbox" value="on" name="status" <?if($item['status'] === 'on'): echo 'checked'; endif;?> />
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
	<?
	$listIP = $DB->GetListItems('IP_ADRESS','date_last_update','ASC','UID',$item['UID']);
//	foreach($listIP as $ip) {
//		echo gettype($ip['status']).'<br/>';
//	}
	if(count($listIP) > 1):
	?>
	<div class="b-history">
		<h2>История измений</h2>
		<table class="table table-striped table-hover">
		<?
		$j = 1;
		for($i = 0; $i < count($listIP); $i++) {
			if ($j < count($listIP)) {
				$diffArr = array_diff($listIP[$i], $listIP[$j]); // получаю различия между записями
				$keysOfDiffArr = array_keys($diffArr); // получаю ключи массива с различиями
				foreach ($keysOfDiffArr as $key => $keyValue):
					if (gettype($keyValue) !== 'integer' && $keyValue != 'id' && $keyValue != 'date_last_update'): // проверяем, что ключи не id и не дата изменения
						if (array_key_exists($keyValue, $diffArr)): // проверяю наличие ключа в массиве
							if($keyValue === 'client_ID'):
								echo '<tr><td><span class="badge badge-info">' . $listIP[$j]['date_last_update'] . '</span> изменился ' . $keyValue . ' c <b>' . $listIP[$i][$keyValue] . '</b> на <b>' . $listIP[$j][$keyValue] . '</b></td>';
							endif;
						endif;
					endif;
				endforeach;
				$j++;
			}
		}
		?>
		</table>
	</div>
	<?endif?>
</div>

<?else:?>

<div class="b-table">
	<a href="index.php?view=ip_adress&action=add" class="btn btn-primary">Создать</a>
	<table class="table table-striped table-hover">
		<thead>
			<tr>
				<th>UID</th>
				<th>IP</th>
				<th>Скорость</th>
				<th>Статус</th>
				<th>ID клиента</th>
				<th>VLAN</th>
				<th>Дата создания</th>
				<th>Дата изменения</th>
			</tr>
		</thead>
		<tbody>
		<?foreach($items as $item):?>
			<tr>
				<td>
					<a href="index.php?view=ip_adress&action=edit&id=<?=$item['id'];?>" class="btn btn-small btn-primary">Изменить</a>
					<!-- <a href="api/ip_adress.php?action=delete&id=<?=$item['id'];?>" class="btn btn-small btn-danger">Удалить</a> -->
				</td>
				<td><a href="index.php?view=ip_adress&action=edit&id=<?=$item['id'];?>"><?=$item['ip']?></a></td>
				<td><?=$item['speed']?></td>
				<td><span class="badge badge-<?=$item['status'] === 'off' ? 'important' : 'success'?>"><?=$item['status'] === 'off' ? 'Отключен' : 'Подключен'?></span></td>
				<td><?=$item['client_ID']?></td>
				<td><?=$item['vlan_ID']?></td>
				<td><?=$item['date_created']?></td>
				<td><?=$item['date_last_update']?></td>
			</tr>
		<?endforeach;?>
		</tbody>
	</table>
</div>

<?endif;?>