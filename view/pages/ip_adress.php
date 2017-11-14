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
					<?if($_GET['action'] === 'edit'):?>
					<div class="col col-6">
						<label>Скорость</label>
						<input class="form-control" name="speed" type="text" value="<?=$item['speed']?>" />
					</div>
					<?endif;?>
				</div>

				<div class="form__group row">
					<div class="col col-6">
						<label>Клиент</label>
						<select class="form-control" name="client_ID" <?if($_GET['action'] === 'edit'):?>disabled<?endif;?>>
							<option value="0">нет</option>
							<?foreach($clients as $client):?>
							<option value="<?=$client['UID']?>" <?if($client['UID'] === $item['client_ID']):?>selected<?endif;?>><?=$client['full_name']?></option>
							<?endforeach;?>
						</select>
						<?if($_GET['action'] === 'edit'):?>
						<input class="form-control" name="client_ID" type="hidden" value="<?=$item['client_ID']?>" />
						<?endif;?>
					</div>
					<div class="col col-6">
						<label>VLAN</label>
						<select class="form-control" name="vlan_ID" <?if($_GET['action'] === 'edit'):?>disabled<?endif;?>>
							<option value="0">нет</option>
							<?foreach($VLAN as $vl):?>
							<option value="<?=$vl['UID']?>" <?if($vl['UID'] === $item['vlan_ID']):?>selected<?endif;?>><?='UID'.$vl['UID'].': '.$vl['value']?></option>
							<?endforeach;?>
						</select>
						<?if($_GET['action'] === 'edit'):?>
							<input class="form-control" name="vlan_ID" type="hidden" value="<?=$item['vlan_ID']?>" />
						<?endif;?>
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
						<input class="form-control" name="status" type="hidden" value="<?=$item['status']?>" />
					</div>
					<?endif?>
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
	if($_GET['action'] === 'edit'):
		$queryGetListIp = "SELECT ip.ip, ip.date_last_update, ip.status, ip.client_ID, clients.full_name FROM
(SELECT * FROM IP_ADRESS WHERE UID=".$item['UID'].") as ip
INNER JOIN (SELECT * FROM
            (SELECT UID, full_name, date_last_update FROM CLIENTS) AS cl1,
             (SELECT UID as client_UID, MAX(date_last_update) AS max_date_update FROM CLIENTS
              GROUP BY client_UID) AS cl2
             WHERE cl1.UID = cl2.client_UID AND cl1.date_last_update = cl2.max_date_update
            GROUP BY UID) as clients ON ip.client_ID = clients.UID";
		$getListIp = $DB->FetchDataInArray($DB->ExecuteQuery($queryGetListIp));
	?>
	<div class="b-history">
		<h2>История измений</h2>
		<table class="table table-striped table-hover">
		<?
		echo '<tr><td><span class="badge badge-info">' . $getListIp[0]['date_last_update'] . '</span> подключен к клиенту <b>' . $getListIp[0]['full_name'] . '</b></td>';

		if(count($getListIp) > 1):
		$j = 1;
		for($i = 0; $i < count($getListIp); $i++) {
			if ($j < count($getListIp)) {
				$diffArr = array_diff($getListIp[$i], $getListIp[$j]); // получаю различия между записями
				$keysOfDiffArr = array_keys($diffArr); // получаю ключи массива с различиями
				foreach ($keysOfDiffArr as $key => $keyValue):
					if (gettype($keyValue) !== 'integer' && $keyValue != 'date_last_update' && $keyValue != 'speed'): // проверяем, что ключи не id и не дата изменения и скорость
						if (array_key_exists($keyValue, $diffArr) && $keyValue === 'status'): // проверяю наличие ключа в массиве
							if($getListIp[$j]['status'] === 'off'):
								echo '<tr><td><span class="badge badge-info">' . $getListIp[$j]['date_last_update'] . '</span> отключен у клиента <b>' . $getListIp[$j]['full_name'] . '</b></td>';
							else:
								echo '<tr><td><span class="badge badge-info">' . $getListIp[$j]['date_last_update'] . '</span> подключен к клиенту <b>' . $getListIp[$j]['full_name'] . '</b></td>';
							endif;
						endif;
					endif;
				endforeach;
				$j++;
			}
		}
		endif;
		?>
		</table>
	</div>
	<?endif;?>
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
			<?if(!CheckDefaultIp($item['ip'])):?>
			<tr>
				<td>
					<a href="index.php?view=ip_adress&action=edit&id=<?=$item['id'];?>" class="btn btn-small btn-primary">Изменить</a>
<!--					 <a href="api/ip_adress.php?action=delete&UID=--><?//=$item['UID'];?><!--" class="btn btn-small btn-danger">Удалить</a>-->
				</td>
				<td><a href="index.php?view=ip_adress&action=edit&id=<?=$item['id'];?>"><?=$item['ip']?></a></td>
				<td><?=$item['speed']?></td>
				<td><span class="badge badge-<?=$item['status'] === 'off' ? 'important' : 'success'?>"><?=$item['status'] === 'off' ? 'Отключен' : 'Подключен'?></span></td>
				<td><?=$item['client_ID']?></td>
				<td><?=$item['vlan_ID']?></td>
				<td><?=$item['date_created']?></td>
				<td><?=$item['date_last_update']?></td>
			</tr>
			<?endif;?>
		<?endforeach;?>
		</tbody>
	</table>
</div>

<?endif;?>