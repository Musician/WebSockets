<?php
require_once('./websockets.php');
require_once('./database.php');

class echoServer extends WebSocketServer {
	//protected $maxBufferSize = 1048576; //1MB... overkill for an echo server, but potentially plausible for other applications.
    protected $dataStorage = array();
    protected function process($user, $message) {
			echo $this->getColoredString("MESSAGE:  " . $user->id . " -> " .$message, "red")." \n";
			$db = new database("dbconfig.php");
			$to = $db->get("SELECT * FROM `bondage` WHERE `player` = '".$user->id . "' OR `agent` = '".$user->id."'");
			if ($to[0]["player"] == $user->id)
			    $messageTo = $to[0]["agent"];
			else
			    $messageTo = $to[0]["player"];
			
		    if (!empty($messageTo))
			    $this->send($this->userPool[$messageTo], $message);
	}
	protected function loop() {


	}
	protected function connected($user) {
	    echo $this->getColoredString("SYSTEM: user #".$user->id." connected", "green")."\n";
		$this->userPool[$user->id] = $user;
		$this->send($user, $user->id);
		
	}
	protected function closed($user) {
		echo $this->getColoredString("SYSTEM: user #".$user->id." disconnected", "red")."\n";
		unset($this->userPool[$user->id]);
	}
}


$echo = new echoServer("0.0.0.0","9090");
try {
	$echo->run();
} catch (Exception $e) {
	$echo->stdout($e->getMessage());
}
