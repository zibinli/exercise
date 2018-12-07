<?php
/**
 * 1. socket_create: 新建 socket
 * 2. socket_bind:   绑定 IP 和 port
 * 3. socket_listen: 监听
 * 4. socket_accept: 接收客户端连接，返回连接 socket
 * 5. socket_read:   读取客户端发送数据
 * 6. socket_write:  返回数据
 * 7. socket_close:  关闭 socket
 */

$ip = '192.168.1.10';
// $ip = '192.168.1.73';
$port = 23333;
// $port = 80;
$sk = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
!$sk && outInfo('socket_create error');

// 绑定 IP
!socket_bind($sk, $ip, $port) && outInfo('socket_bind error');

// 监听
!socket_listen($sk) && outInfo('sever listen error');

outInfo("Success Listen: $ip:$port", 'INFO');
while (true) {
    $accept_res = socket_accept($sk);
    !$accept_res && outInfo('sever accept error');
    $reqStr = socket_read($accept_res, 1024);

    if (!$reqStr) outInfo('sever read error');
    outInfo("Server receive client msg: $reqStr", 'INFO');
    $response = 'Hello A, I am B. you msg is : ' . $reqStr . PHP_EOL;
    if (socket_write($accept_res, $response, strlen($response)) === false) {
        outInfo('response error');
    }

    socket_close($accept_res);
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
