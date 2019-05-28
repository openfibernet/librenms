<?php

// Polls kea statistics from script via SNMP
use LibreNMS\RRD\RrdDefinition;

$name = 'kea';
$app_id = $app['app_id'];

echo " $name";

$options = '-Oqv';
$oid     = '.1.3.6.1.4.1.8072.1.3.2.3.1.2.3.107.101.97';
$kea     = snmp_get($device, $oid, $options);
$metrics = array();

list($receive4_drop, $reclaimed4_declined_addresses, $reclaimed4_leases, $release4_rcvd, $nak4_sent,
    $offer4_sent, $inform4_rcvd, $request4_rcvd, $declined4_addresses, $discover4_rcvd,
    $ack4_sent, $rebind6_rcvd, $reply6_sent, $solicit6_rcvd, $renew6_rcvd,
    $reclaimed6_leases, $confirm6_rcvd, $reclaimed6_declined_addresses, $infrequest6_rcvd,
    $declined6_addresses, $advertise6_sent, $receive6_drop, $request6_rcvd, $release6_rcvd) = explode("\n", $kea);


/* Reclaiming DHCPv4 statistics */
$rrd_name = array('app', $name, 'reclamed4', $app_id);
$rrd_def = RrdDefinition::make()
    ->addDataset('reclaimed4_declined_addresses', 'DERIVE', 0)
    ->addDataset('reclaimed4_leases', 'DERIVE', 0)
    ->addDataset('declined4_addresses', 'DERIVE', 0);

$fields = array(
    'reclaimed4_declined_addresses'     => $reclaimed4_declined_addresses,
    'reclaimed4_leases'                 => $reclaimed4_leases,
    'declined4_addresses'               => $declined4_addresses,
);

$metrics['reclamed4'] = $fields;
$tags = compact('name', 'app_id', 'rrd_name', 'rrd_def');
data_update($device, 'app', $tags, $fields);


/* DHCPv4 statistics */
$rrd_name = array('app', $name, 'dhcp4', $app_id);
$rrd_def = RrdDefinition::make()
    ->addDataset('receive4_drop', 'DERIVE', 0)
    ->addDataset('release4_rcvd', 'DERIVE', 0)
    ->addDataset('nak4_sent', 'DERIVE', 0)
    ->addDataset('offer4_sent', 'DERIVE', 0)
    ->addDataset('inform4_rcvd', 'DERIVE', 0)
    ->addDataset('request4_rcvd', 'DERIVE', 0)
    ->addDataset('discover4_rcvd', 'DERIVE', 0)
    ->addDataset('ack4_sent', 'DERIVE', 0);

$fields = array(
    'receive4_drop'  => intval(trim($receive4_drop, '"')),
    'release4_rcvd'  => $release4_rcvd,
    'nak4_sent'      => $nak4_sent,
    'offer4_sent'    => $offer4_sent,
    'inform4_rcvd'   => $inform4_rcvd,
    'request4_rcvd'  => $request4_rcvd,
    'discover4_rcvd' => $discover4_rcvd,
    'ack4_sent'      => $ack4_sent,
);

$metrics['dhcp4'] = $fields;
$tags = compact('name', 'app_id', 'rrd_name', 'rrd_def');
data_update($device, 'app', $tags, $fields);


/* DHCPv6 statistics */
$rrd_name = array('app', $name, 'dhcp6', $app_id);
$rrd_def = RrdDefinition::make()
    ->addDataset('rebind6_rcvd', 'DERIVE', 0)
    ->addDataset('reply6_sent', 'DERIVE', 0)
    ->addDataset('solicit6_rcvd', 'DERIVE', 0)
    ->addDataset('renew6_rcvd', 'DERIVE', 0)
    ->addDataset('confirm6_rcvd', 'DERIVE', 0)
    ->addDataset('infrequest6_rcvd', 'DERIVE', 0)
    ->addDataset('advertise6_sent', 'DERIVE', 0)
    ->addDataset('receive6_drop', 'DERIVE', 0)
    ->addDataset('request6_rcvd', 'DERIVE', 0)
    ->addDataset('release6_rcvd', 'DERIVE', 0);

$fields = array(
    'rebind6_rcvd'                      => $rebind6_rcvd,
    'reply6_sent'                       => $reply6_sent,
    'solicit6_rcvd'                     => $solicit6_rcvd,
    'renew6_rcvd'                       => $renew6_rcvd,
    'confirm6_rcvd'                     => $confirm6_rcvd,
    'infrequest6_rcvd'                  => $infrequest6_rcvd,
    'advertise6_sent'                   => $advertise6_sent,
    'receive6_drop'                     => $receive6_drop,
    'request6_rcvd'                     => $request6_rcvd,
    'release6_rcvd'                     => intval(trim($release6_rcvd, '"')),
);

$metrics['dhcp6'] = $fields;
$tags = compact('name', 'app_id', 'rrd_name', 'rrd_def');
data_update($device, 'app', $tags, $fields);


/* Reclaiming DHCPv6 statistics */
$rrd_name = array('app', $name, 'reclamed6', $app_id);
$rrd_def = RrdDefinition::make()
    ->addDataset('declined6_addresses', 'DERIVE', 0)
    ->addDataset('reclaimed6_leases', 'DERIVE', 0)
    ->addDataset('reclaimed6_declined_addresses', 'DERIVE', 0);

$fields = array(
    'reclaimed6_leases'                 => $reclaimed6_leases,
    'reclaimed6_declined_addresses'     => $reclaimed6_declined_addresses,
    'declined6_addresses'               => $declined6_addresses,
);

$metrics['reclamed6'] = $fields;
$tags = compact('name', 'app_id', 'rrd_name', 'rrd_def');
data_update($device, 'app', $tags, $fields);

update_application($app, $kea, $metrics);
