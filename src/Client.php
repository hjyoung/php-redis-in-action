<?php


class Client
{
    private $redis = null;
    private $config = [];

    public function __construct($config = [])
    {
        $this->config = $config;
        $redis = new Redis();
        if($redis->connect($config['host'],$config['port'],$config['timeout']))
        {
            $this->redis = $redis;
        }
        else
        {
            throw new RedisException("Couldn't connect to host");
        }
    }

    /**
     * @return Redis
     */
    public function pipeline()
    {
        return $this->redis->multi();
    }

    public function __call($name,$args)
    {
        switch (count($args))
        {
            case 0:
                return $this->redis->$name();
                break;
            case 1:
                return $this->redis->$name($args[0]);
                break;
            case 2:
                return $this->redis->$name($args[0],$args[1]);
                break;
            case 3:
                return $this->redis->$name($args[0],$args[1],$args[2]);
                break;
            case 4:
                return $this->redis->$name($args[0],$args[1],$args[2],$args[3]);
                break;
            case 5:
                return $this->redis->$name($args[0],$args[1],$args[2],$args[3],$args[4]);
                break;
            case 6:
                return $this->redis->$name($args[0],$args[1],$args[2],$args[3],$args[4],$args[5]);
                break;
            case 7:
                return $this->redis->$name($args[0],$args[1],$args[2],$args[3],$args[4],$args[5],$args[6]);
                break;
            case 8:
                return $this->redis->$name($args[0],$args[1],$args[2],$args[3],$args[4],$args[5],$args[6],$args[7]);
                break;
            case 9:
                return $this->redis->$name($args[0],$args[1],$args[2],$args[3],$args[4],$args[5],$args[6],$args[7],$args[8]);
                break;
        }
    }
}
