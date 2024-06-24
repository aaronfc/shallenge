<?php

$MINIMUM_FILE = 'minimum.txt';
$TICK = 10;

$phrase_prefix = "aaronfc/aaron+dot+com+dot+es/Hello/";

if ( !file_exists($MINIMUM_FILE) ) {
	file_put_contents($MINIMUM_FILE, "initial:ffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff");
}
[ $minimum_phrase, $minimum_hash ] = explode(":", file_get_contents($MINIMUM_FILE));

$last_tick = microtime(true);
$hashes = 0;
while ( true ) {
	$phrase = $phrase_prefix . md5(uniqid(rand(), true));
	for($i = 0; $i < 1000; $i++) {
		$tryphrase = $phrase . $i;
		$hash = hash('sha256', $tryphrase);
		if ( strcmp( $hash, $minimum_hash ) < 0 ) {
			file_put_contents($MINIMUM_FILE, $tryphrase . ":" . $hash, LOCK_EX);
			echo "New minimum found: $tryphrase\n$hash\n";
			$minimum_hash = $hash;
		}
		// Speed calculation and output.
		$hashes++;
		$now = microtime(true);
		if ( $now - $last_tick >= $TICK ) {
			echo round( $hashes / ($now - $last_tick) ) . "hash/s – last: ..." . substr($tryphrase, -5) . "\r";
			$last_tick = microtime(true);
			$hashes = 0;
		}
	}
}
