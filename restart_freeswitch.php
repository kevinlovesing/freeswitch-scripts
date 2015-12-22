<?php
/**
 *add a crond to run it .
 *10 3 * * * php /root/monitor/restart_freeswitch.php  >> /var/log/freeswitch/restart.log 2>&1
 */

date_default_timezone_set('Asia/Shanghai');

$start_time = '03:00:00';
$end_time = '05:00:00';


main_process();


function main_process(){
	global $start_time, $end_time;
	while(true){
		$cur_time = date('H:i:s');
		$cur_date = date('Y-m-d H:i:s');
		if($cur_time > $start_time && $cur_time < $end_time){
			$call_count = get_fs_calls();
			if($call_count == 0){
				exec("/etc/init.d/freeswitch restart",$result);
				echo "$cur_date : right time to restart freeswitch\n";
				var_dump($result);
				break;
			}else{
				echo "$cur_date : there are $call_count calls now ,sleep 5 min then restart freeswitch !!\n";
				sleep(300);
			}
		}
		else{
			echo "$cur_date : bad time to restart freeswitch !!!\n";
			break;
		}

	}

}

function get_fs_calls(){
	exec("fs_cli -x 'show calls count'",$call_count);
        $count = trim($call_count[1], " total.");
        return $count;
}



?>
