<?php
/**
 * 1. socket_create:  新建 socket
 * 2. socket_connect: 连接服务端
 * 3. socket_write:   给服务端发数据
 * 4. socket_read:    读取服务端返回的数据
 * 5. socket_close:   关闭 socket
 */

$ip = '192.168.1.10';
$port = 23333;
$sk = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
!$sk && echoSocketError('socket_create error');

!socket_connect($sk, $ip, $port) && echoSocketError('connect fail');
$msg = 'hello, I am A';

if (socket_write($sk, $msg, strlen($msg)) === false) {
    echoSocketError('socket_write fail');
}

while ($res = socket_read($sk, 1024)) {
    echo 'server return message is:'. PHP_EOL. $res;
}

socket_close($sk);//工作完毕，关闭套接流

function echoSocketError($errMsg)
{
    echo "$errMsg, msg: " . socket_strerror(socket_last_error()) . PHP_EOL;die;
}
