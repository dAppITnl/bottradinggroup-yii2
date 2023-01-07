<?php
		$logstatus = ['ok'=>0, 'error'=>0];
		$logstatus[ true ? 'ok' : 'error' ]++;
    $logstatus[ false ? 'ok' : 'error' ]++;
    $logstatus[ true ? 'ok' : 'error' ]++;
    $logstatus[ true ? 'ok' : 'error' ]++;

echo "\n\n".print_r($logstatus, true)."\n\n";
echo json_encode($logstatus)."\n\n";
