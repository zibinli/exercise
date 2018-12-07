<?php
/**
 * 1. socket_create:  新建 socket
 * 2. socket_connect: 连接服务端
 * 3. socket_write:   给服务端发数据
 * 4. socket_read:    读取服务端返回的数据
 * 5. socket_close:   关闭 socket
 */

$ip = '192.168.1.10';
$ip = '192.168.1.73';
$port = 23333;
$sk = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
!$sk && outInfo('socket_create error');

$msg = 'hello, I am A';

if (!socket_sendto($sk, $msg, strlen($msg), 0, $ip, $port)) {
    outInfo('socket_sendto fail');
}

$from = '';
$reqPort = 0;
if (!socket_recvfrom($sk, $buf, 1024, 0, $from, $reqPort)) {
    outInfo('server socket_recvfrom error');
}

// $stz = bin2hex($buf);
outInfo("Received $buf from server address $from:$port", 'INFO');

socket_close($sk);//工作完毕，关闭套接流

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
