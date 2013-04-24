<?php

/**
 * Summary job: total BTC.
 */

// get the most recent blockchain balances
$q = db()->prepare("SELECT * FROM address_balances
	JOIN addresses ON address_balances.address_id=addresses.id
	WHERE address_balances.user_id=? AND is_recent=1 AND currency=?");
$q->execute(array($job['user_id'], 'btc'));
while ($balance = $q->fetch()) {
	$total += $balance['balance'];
}

// and the most recent offsets
$q = db()->prepare("SELECT * FROM offsets
	WHERE user_id=? AND is_recent=1 AND currency=?");
$q->execute(array($job['user_id'], 'btc'));
while ($offset = $q->fetch()) { // we should only have one anyway
	$total += $offset['balance'];
}

crypto_log("Total BTC balance for user " . $job['user_id'] . ": " . $total);