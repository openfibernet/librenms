<?php
require 'includes/html/graphs/common.inc.php';
$i               = 0;
$scale_min       = 0;
$nototal         = 1;
$unit_text       = 'Per Sec.';
$rrd_filename    = rrd_name($device['hostname'], array('app', 'kea', 'dhcp4', $app['app_id']));
$fr_access_array = array(
    'receive4_drop'  => 'Dropped',
    'release4_rcvd'  => 'Released',
    'nak4_sent'      => 'NACK',
    'offer4_sent'    => 'Offer',
    'inform4_rcvd'   => 'Inform',
    'request4_rcvd'  => 'Request',
    'discover4_rcvd' => 'Discover',
    'ack4_sent'      => 'ACK'
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
