<?php
require ("require.php");
class insert
{
    private $dsn = 'mysql:host=localhost;port=3306;dbname=ippool';
    private $username = 'root';
    private $password = 'qq123456';
    private $nowdate;
    private $nowmin;
    private $nowtime;
    public function __construct()
{
    $this->nowdate=date("YmdH",time());
    //$this->nowmin=date("His",time());
    $this->nowmin=time();
    // $this->nowtime=date("His",time());
    $this->nowtime=time();
}

    private function getDb($method=null) {
        return new \fangl\db\Connection($this->dsn, $this->username, $this->password);
    }

    public function Qiptb() {
        $db = $this->getDb();
        //echo $this->nowmin;
        $ret = $db->createCommand('show tables like \'%ip_'.$this->nowdate.'%\'')->queryOne();
        if(!$ret)
        {
            $sql ="CREATE TABLE `ip_{$this->nowdate}` (  `id` int(11) NOT NULL AUTO_INCREMENT,  `ip` varchar(255) NOT NULL,  `atime` varchar(255) NOT NULL,  `btime` varchar(255) NOT NULL, PRIMARY KEY (`ip`,`id`),  UNIQUE KEY `ip` (`ip`) USING BTREE,  UNIQUE KEY `id` (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
            $db->createCommand($sql)->execute();
            $sql="CREATE TABLE `u_{$this->nowdate}` (  `uid` int(11) NOT NULL,  `ipid` int(11) NOT NULL,  `btime` varchar(255) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
            $db->createCommand($sql)->execute();
            $this->insertu();
        }
    }


    public function insertu() {
        $db = $this->getDb();
        $ret=$db->createCommand('select  id uid from user')->queryAll();
        foreach(  $ret as $key =>$var)
        {

            $ret[$key]['ipid']=0;
            $ret[$key]['btime']=$this->nowtime;
        }
        //print_r($param);
        $this->getDb()->createCommand()->batchInsert("u_{$this->nowdate}", $ret);

    }

    public function inip($param=[])
    {
        $this->Qiptb();
        foreach($param as $key =>$var)
        {
            $param[$key]['atime']=$this->nowmin;
            $param[$key]['btime']=$this->nowtime;
        }
        //print_r($param);
        $this->getDb()->createCommand()->batchInsert("ip_{$this->nowdate}", $param);
    }
}
//$in=new insert();
//$in->insertu();
$post=file_get_contents('php://input');
if(isset($post)&&$post!="")
{
    $arr=[];
    $split=explode(',',$post);
    for($i=0;$i<count($split);$i++)
    {
        $arr[$i]['ip']=$split[$i];
    }
    $in=new insert();
    $in->inip($arr);
}


?>

