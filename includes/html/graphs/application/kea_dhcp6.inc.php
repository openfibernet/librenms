<?php
require 'includes/html/graphs/common.inc.php';
$i               = 0;
$scale_min       = 0;
$nototal         = 1;
$unit_text       = 'Per Sec.';
$rrd_filename    = rrd_name($device['hostname'], array('app', 'kea', 'dhcp6', $app['app_id']));
$fr_access_array = array(
    'rebind6_rcvd'     => 'Rebind',
    'reply6_sent'      => 'Reply',
    'solicit6_rcvd'    => 'Solicit',
    'renew6_rcvd'      => 'Renew',
    'confirm6_rcvd'    => 'Confirm',
    'infrequest6_rcvd' => 'Infrequest',
    'advertise6_sent'  => 'Advertise',
    'receive6_drop'    => 'Receive',
    'request6_rcvd'    => 'Request',
    'release6_rcvd'    => 'Release'
);
$colours      = 'mixed';
$rrd_list     = array();
if (rrdtool_check_rrd_exists($rrd_filename)) {
    foreach ($fr_access_array as $ds => $descr) {
        $rrd_list[$i]['filename'] = $rrd_filename;
        $rrd_list[$i]['descr']    = $descr;
        $rrd_list[$i]['ds']       = $ds;
        $i++;
    }
} else {
    echo "file missing: $rrd_filename";
}
require 'includes/html/graphs/generic_multi_line.inc.php';
