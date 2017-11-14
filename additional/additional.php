<?php

function CheckDefaultIp($ip) {
  $str = '';
  for($i=0; $i < 3; $i++) {
    $str .= $ip[$i];
  }
  return $str === '10.' ? true : false;
}

function GenerateDefaultIP($vlan) {
  $b = $vlan >> 8;
  $c = $vlan & 0xFF;
  return '10.'.$b.'.'.$c.'.0/24';
}

function GenerateVLAN($commutator, $port, $segment) {
  return ($commutator - 200) * 24 + $port + 999 + $segment * 4000;
}