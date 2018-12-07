<?php
/**
 * 1. socket_create: 新建 socket
 * 2. socket_bind:   绑定 IP 和 port
 * 5. socket_read:   读取客户端发送数据
 * 6. socket_write:  返回数据
 * 7. socket_close:  关闭 socket
 */

$ip = '192.168.1.10';
$ip = '192.168.1.73';
$port = 23333;
// $port = 80;
$sk = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
!$sk && outInfo('socket_create error');

// 绑定 IP
!socket_bind($sk, $ip, $port) && outInfo('socket_bind error');

outInfo("Success Listen: $ip:$port", 'INFO');
while (true) {
    $from = '';
    $reqPort = 0;
    if (!socket_recvfrom($sk, $buf, 1024, 0, $from, $reqPort)) {
        outInfo('sever socket_recvfrom error');
    }

    $stz = bin2hex($buf);
    outInfo("Received $buf from remote address $from:$port", 'INFO');
    $response = "Hello $from:$port, I am Server. you msg is : " . $stz . PHP_EOL;
    if (!socket_sendto($sk, $response, strlen($response), 0, $from, $reqPort)) {
        outInfo('socket_sendto error');
    }

    socket_close($sk);
}

socket_close($sk);

function outInfo($errMsg, $level = 'ERROR')
{
    if ($level === 'INFO') {
        $outstr = $errMsg . PHP_EOL;
    } elseif ($level === 'ERROR') {
        $outstr = "$errMsg, msg: " . socket_strerror(socket_last_error()) . PHP_EOL;
    }
    echo $outstr;
    $level === 'ERROR' && die;
}
