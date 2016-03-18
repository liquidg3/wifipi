<?php

$cmd = "/usr/sbin/arp " . $_SERVER['REMOTE_ADDR'];
$mac = shell_exec($cmd);
$error = false;
$done = false;
$name = false;
$existing = false;

if (!$mac) {
    echo json_encode(['error' => 'Could not detect your device. Please let someone know.']);
    return;
}

$matches = [];
preg_match('/..:..:..:..:..:../', $mac , $matches);
$mac = $matches[0];

exec("sudo iptables -I internet 1 -t mangle -m mac --mac-source $mac -j RETURN");
exec("sudo rmtrack " . $_SERVER['REMOTE_ADDR']);

echo json_encode(['error' => false]);