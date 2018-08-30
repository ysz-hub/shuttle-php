<?php

namespace Shuttle\Server;

use Shuttle\Packag\Packag;

class Tcp
{
    private $fd;
    private $serv;

    public function __construct($host, $port)
    {
        $this->serv = new \swoole_server($host, $port);
        $this->serv->set([
            'worker_num' => 8,
            'daemonize' => false,
            'heartbeat_check_interval' => 60,
            'heartbeat_idle_time' => 600,
        ]);

        $this->serv->on('Start', array($this, 'onStart'));
        $this->serv->on('Connect', array($this, 'onConnect'));
        $this->serv->on('Receive', array($this, 'onReceive'));
        $this->serv->on('Close', array($this, 'onClose'));

        $this->serv->start();
    }

    public function onStart($server)
    {
        echo "Start\n";
    }

    public function onConnect($server, int $fd, int $from_id)
    {
        $this->fd[$fd] = $fd;
        echo "connection open: {$fd}\n";
    }

    public function onReceive(\swoole_server $server, int $fd, int $reactor_id, $data)
    {
        $data = Packag::decode($data);
        if (0) {
            //$arr = explode('@', Commons::ROUTES[$data['url']]);
            //(new $arr[0])->{$arr[1]}($server, $fd, $reactor_id, $data);

        } else {
            $sendData = Packag::encode([
                'err_code' => 0,
                'from_fd' => $fd,
                'msg' => $data['msg']
            ]);

            if (empty($data['fd'])) {
                foreach ($this->fd as $fd_i) {
                    echo "send to fd >> " . $fd_i . '. msg >> ' . $sendData . "\n";
                    $server->send($fd_i, $sendData);
                }
            } else {
                echo "send to fd >> " . $data['fd'] . '. msg >> ' . $sendData . "\n";
                $server->send($data['fd'], $sendData);
            }

        }

        //$server->close($fd);
    }

    public function onClose(\swoole_server $server, int $fd, int $from_id)
    {
        echo "connection close: {$fd}\n";
    }

}