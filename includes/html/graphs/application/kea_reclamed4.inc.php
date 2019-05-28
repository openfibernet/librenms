<?php
require 'includes/html/graphs/common.inc.php';
$i               = 0;
$scale_min       = 0;
$nototal         = 1;
$unit_text       = 'Per Sec.';
$rrd_filename    = rrd_name($device['hostname'], array('app', 'kea', 'reclamed4', $app['app_id']));
$fr_access_array = array(
    'reclaimed4_declined_addresses'     => 'Reclaimed declined addrs',
    'reclaimed4_leases'                 => 'Reclaimed leases',
    'declined4_addresses'               => 'Declined addrs',
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
