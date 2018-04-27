<?php
error_reporting(E_ALL | E_STRICT);
ini_set("display_errors", "On");
require ("require.php");
class get
{
    private $dsn = 'mysql:host=localhost;port=3306;dbname=ippool';
    private $username = 'root';
    private $password = 'qq123456';
    private $nowdate;
    private $nowmin;
    private $nowtime;
    private $id;
    private $l;
    public function __construct($id,$i)
    {
        $this->nowdate=date("YmdH",time());
        $this->nowmin=date("His",time());
        $this->nowmin=time();
        $this->nowtime=date("His",time());
        $this->nowtime=time();
        $this->id=$id;
        $this->l=$i;
    }
    private function getDb($method=null) {
        return new \fangl\db\Connection($this->dsn, $this->username, $this->password);
    }

    public function getip($param=[])
    {
        $l=$this->l?$this->l:10;
        $param = [];
        $db = $this->getDb();
        //$ret=$db->createCommand("select ip from ip_{$this->nowdate} i order by i.btime desc limit 0,{$l}")->queryAll();
       $ret = $db->createCommand("SELECT id,ip FROM ip_{$this->nowdate} i LEFT JOIN u_{$this->nowdate} u ON i.id=u.ipid and u.uid={$this->id} WHERE u.uid IS NULL ORDER BY i.btime desc  limit 0,{$l} ", [], true)->queryAll();
        if (count($ret) != 0) {
            foreach ($ret as $key => $var) {
                echo $var['ip'] . "\r\n";
                $param[$key]['ipid'] = $var['id'];
                $param[$key]['uid'] = $this->id;
                $param[$key]['btime'] = $this->nowtime;
            }
            $this->getDb()->createCommand()->batchInsert("u_{$this->nowdate}", $param); // $this->getDb()->createCommand()->batchInsert("ip_{$this->nowdate}", $param);
        }
        else
        {
             echo "no ip";
        }
    }

}

$get=new get($_GET['id'],$_GET['l']);


?>