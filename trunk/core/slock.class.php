<?php

/**
* 自旋锁 
* 用于php进程同步时使用
* add by weego 20120917
*/

class slock
{
    /**
    * $lock_timeout 设置等待回旋次数
    * $lock_wait_func 设置等待机制,本例使用usleep+mt_rand随机等待,随机等待有利错开多个竞争
    * $add_func 这里设置添加锁标志机制,本例使用PHP的APC组件apc_add函数,apc_add设定锁占有上限为5s,避免永久占有
    * $del_func 这里设置删除锁标志机制,本例使用PHP的APC组件apc_delete函数
    */
    private $locks;
    private $lock_timeout = 200;
    private $lock_wait_func;
    private $add_func;
    private $del_func;
    public function __construct()
    {
        $this->add_func = function($mutex)
        {
			return apc_add('sl:'.$mutex,1,5);;
        };

        $this->del_func = function($mutex)
        {
            return apc_delete('sl:'.$mutex);
        };
        $this->lock_wait_func = function()
        {
			usleep(mt_rand(1000,5000));
        };
    }
    public function __destruct()
    {
        $this->clean();
    }
    /**
    * 清除当前所有设置的锁,在当前的php进程中可以设置多个锁
    */
    public function clean()
    {
        if($this->locks)
        {
            foreach($this->locks as $lock => $tmp)
            call_user_func($this->del_func ,$lock);
            $this->locks = null;
        }
    }
    /**
    * 新建立一个锁
    * 首先会判断锁定标志是否已经定义，如果已锁定则判定为死锁
    * 其次使用apc共享内存方式add一个锁标志,如果失败则进入等待时间,直到超时
    */
    public function lock($mutex)
    {
        if($this->locks[$mutex]!=null)
        {
            throw new Exception('System Queue...');
            return false;
        }

        while(call_user_func($this->add_func ,$mutex) == false)
        {
			//并发在前一个没处理完，后面的都阻止运行
			echo "System busy!";
			die;
            return false;
			
			//下面是高并发的排队机制
			/*
            ++$i;
            if($i > $this->lock_timeout)
            {
                throw new Exception('lock timeout.');
                return false;
            }
            call_user_func($this->lock_wait_func);
			*/
        }
        $this->locks[$mutex] = 1;
        return $mutex;
    }

    /**
    * 手动释放锁,一般不用,
    * 在当前对象析构时会自动释放所有锁
    */
    public function release($mutex)
    {
        if($mutex == false) return false;
        unset($this->locks[$mutex]);
        call_user_func($this->del_func ,$mutex);
        return true;
    }
}

?>