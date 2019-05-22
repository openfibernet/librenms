<?php

// Polls kea statistics from script via SNMP
use LibreNMS\RRD\RrdDefinition;

$name = 'kea';
$app_id = $app['app_id'];
if (!empty($agent_data['app'][$name])) {
    $kea = $agent_data['app'][$name];
} else {
    $options = '-Oqv';
    $oid     = '.1.3.6.1.4.1.8072.1.3.2.3.1.2.3.107.101.97';
    $kea     = snmp_get($device, $oid, $options);
}

echo ' kea';

list($pkt4_receive_drop, $v4_reclaimed_declined_addresses, $v4_reclaimed_leases, $pkt4_release_received, $pkt4_nak_sent,
    $pkt4_offer_sent, $pkt4_inform_received, $pkt4_request_received, $v4_declined_addresses, $pkt4_discover_received,
    $pkt4_ack_sent, $pkt6_rebind_received, $pkt6_reply_sent, $pkt6_solicit_received, $pkt6_renew_received,
    $v6_reclaimed_leases, $pkt6_confirm_received, $v6_reclaimed_declined_addresses, $pkt6_infrequest_received,
    $v6_declined_addresses, $pkt6_advertise_sent, $pkt6_receive_drop, $pkt6_request_received, $pkt6_release_received) = explode("\n", $kea);

$rrd_name = array('app', $name, $app_id);
$rrd_def = RrdDefinition::make()
    ->addDataset('pkt4_receive_drop', 'GAUGE', 0, 125000000000)
    ->addDataset('v4_reclaimed_declined_addresses', 'GAUGE', 0, 125000000000)
    ->addDataset('v4_reclaimed_leases', 'GAUGE', 0, 125000000000)
    ->addDataset('pkt4_release_received', 'GAUGE', 0, 125000000000)
    ->addDataset('pkt4_nak_sent', 'GAUGE', 0, 125000000000)
    ->addDataset('pkt4_offer_sent', 'GAUGE', 0, 125000000000)
    ->addDataset('pkt4_inform_received', 'GAUGE', 0, 125000000000)
    ->addDataset('pkt4_request_received', 'GAUGE', 0, 125000000000)
    ->addDataset('v4_declined_addresses', 'GAUGE', 0, 125000000000)
    ->addDataset('pkt4_discover_received', 'GAUGE', 0, 125000000000)
    ->addDataset('pkt4_ack_sent', 'GAUGE', 0, 125000000000)
    ->addDataset('pkt6_rebind_received', 'GAUGE', 0, 125000000000)
    ->addDataset('pkt6_reply_sent', 'GAUGE', 0, 125000000000)
    ->addDataset('pkt6_solicit_received', 'GAUGE', 0, 125000000000)
    ->addDataset('pkt6_renew_received', 'GAUGE', 0, 125000000000)
    ->addDataset('v6_reclaimed_leases', 'GAUGE', 0, 125000000000)
    ->addDataset('pkt6_confirm_received', 'GAUGE', 0, 125000000000)
    ->addDataset('v6_reclaimed_declined_addresses', 'GAUGE', 0, 125000000000)
    ->addDataset('pkt6_infrequest_received', 'GAUGE', 0, 125000000000)
    ->addDataset('v6_declined_addresses', 'GAUGE', 0, 125000000000)
    ->addDataset('pkt6_advertise_sent', 'GAUGE', 0, 125000000000)
    ->addDataset('pkt6_receive_drop', 'GAUGE', 0, 125000000000)
    ->addDataset('pkt6_request_received', 'GAUGE', 0, 125000000000)
    ->addDataset('pkt6_release_received', 'GAUGE', 0, 125000000000);

$fields = array(
    'pkt4_receive_drop'                => intval(trim($pkt4_receive_drop, '"')),
    'v4_reclaimed_declined_addresses'  => $v4_reclaimed_declined_addresses,
    'v4_reclaimed_leases'              => $v4_reclaimed_leases,
    'pkt4_release_received'            => $pkt4_release_received,
    'pkt4_nak_sent'                    => $pkt4_nak_sent,
    'pkt4_offer_sent'                  => $pkt4_offer_sent,
    'pkt4_inform_received'             => $pkt4_inform_received,
    'pkt4_request_received'            => $pkt4_request_received,
    'v4_declined_addresses'            => $v4_declined_addresses,
    'v4_declined_addresses'            => $v4_declined_addresses,
    'pkt4_discover_received'           => $pkt4_discover_received,
    'pkt4_ack_sent'                    => $pkt4_ack_sent,
    'pkt6_rebind_received'             => $pkt6_rebind_received,
    'pkt6_reply_sent'                  => $pkt6_reply_sent,
    'pkt6_solicit_received'            => $pkt6_solicit_received,
    'pkt6_renew_received'              => $pkt6_renew_received,
    'v6_reclaimed_leases'              => $v6_reclaimed_leases,
    'pkt6_confirm_received'            => $pkt6_confirm_received,
    'v6_reclaimed_declined_addresses'  => $v6_reclaimed_declined_addresses,
    'pkt6_infrequest_received'         => $pkt6_infrequest_received,
    'v6_declined_addresses'            => $v6_declined_addresses,
    'pkt6_advertise_sent'              => $pkt6_advertise_sent,
    'pkt6_receive_drop'                => $pkt6_receive_drop,
    'pkt6_request_received'            => $pkt6_request_received,
    'pkt6_release_received'            => intval(trim($pkt6_release_received, '"')),
);

$tags = compact('name', 'app_id', 'rrd_name', 'rrd_def');
data_update($device, 'app', $tags, $fields);
update_application($app, $kea, $fields);
