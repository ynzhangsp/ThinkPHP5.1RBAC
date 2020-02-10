<?php


namespace app\admin\controller;


class IPQuery
{
    private $fh;        // IP数据库文件句柄
    private $first;     // 第一条索引
    private $last;      // 最后一条索引
    private $total;     // 索引总数
    private $dbFile = __DIR__ . DIRECTORY_SEPARATOR . 'qqwry.dat';      // 纯真 IP 数据库文件存放路径
//    private $dbExpires = 86400 * 10;        // 数据库文件有效期（10天）如无需自动更新 IP 数据库，请将此值改为 0
    private $dbExpires = 0;        // 数据库文件有效期（10天）如无需自动更新 IP 数据库，请将此值改为 0

    // 构造函数
    function __construct() {
        // IP 数据库文件不存在或已过期，则自动获取
//        if(!file_exists($this->dbFile) || ($this->dbExpires && ((time() - filemtime($this->dbFile)) > $this->dbExpires))) {
//            $this->update();
//        }
    }

    // 忽略超时
    private function ignore_timeout() {
        @ignore_user_abort(true);
        @ini_set('max_execution_time', 48 * 60 * 60);
        @set_time_limit(48 * 60 * 60);    // set_time_limit(0)  2day
        @ini_set('memory_limit', '4000M');// 4G;
    }

    // 读取little-endian编码的4个字节转化为长整型数
    private function getLong4() {
        $result = unpack('Vlong', fread($this->fh, 4));
        return $result['long'];
    }

    // 读取little-endian编码的3个字节转化为长整型数
    private function getLong3() {
        $result = unpack('Vlong', fread($this->fh, 3).chr(0));
        return $result['long'];
    }

    // 查询位置信息
    private function getPos($data = '') {
        $char = fread($this->fh, 1);
        while (ord($char) != 0) {   // 地区信息以 0 结束
            $data .= $char;
            $char = fread($this->fh, 1);
        }
        return $data;
    }

    // 查询运营商
    private function getISP() {
        $byte = fread($this->fh, 1);    // 标志字节
        switch (ord($byte)) {
            case 0: $area = ''; break;  // 没有相关信息
            case 1: // 被重定向
                fseek($this->fh, $this->getLong3());
                $area = $this->getPos(); break;
            case 2: // 被重定向
                fseek($this->fh, $this->getLong3());
                $area = $this->getPos(); break;
            default: $area = $this->getPos($byte); break;     // 没有被重定向
        }
        return $area;
    }

    // 检查 IP 格式是否正确
    public function checkIp($ip) {
        $arr = explode('.', $ip);
        if(count($arr) != 4) return false;
        for ($i = 0; $i < 4; $i++) {
            if ($arr[$i] < '0' || $arr[$i] > '255') {
                return false;
            }
        }
        return true;
    }

    // 查询 IP 地址
    public function query($ip) {
        if(!$this->checkIp($ip)) {
            return false;
        }

        $this->fh    = fopen($this->dbFile, 'rb');
        $this->first = $this->getLong4();
        $this->last  = $this->getLong4();
        $this->total = ($this->last - $this->first) / 7;    // 每条索引7字节

        $ip = pack('N', intval(ip2long($ip)));

        // 二分查找 IP 位置
        $l = 0;
        $r = $this->total;
        while($l <= $r) {
            $m = floor(($l + $r) / 2);     // 计算中间索引
            fseek($this->fh, $this->first + $m * 7);
            $beginip = strrev(fread($this->fh, 4)); // 中间索引的开始IP地址
            fseek($this->fh, $this->getLong3());
            $endip = strrev(fread($this->fh, 4));   // 中间索引的结束IP地址

            if ($ip < $beginip) {   // 用户的IP小于中间索引的开始IP地址时
                $r = $m - 1;
            } else {
                if ($ip > $endip) { // 用户的IP大于中间索引的结束IP地址时
                    $l = $m + 1;
                } else {            // 用户IP在中间索引的IP范围内时
                    $findip = $this->first + $m * 7;
                    break;
                }
            }
        }

        // 查找 IP 地址段
        fseek($this->fh, $findip);
        $location['beginip'] = long2ip($this->getLong4()); // 用户IP所在范围的开始地址
        $offset = $this->getlong3();
        fseek($this->fh, $offset);
        $location['endip'] = long2ip($this->getLong4()); // 用户IP所在范围的结束地址

        // 查找 IP 信息
        $byte = fread($this->fh, 1); // 标志字节
        switch (ord($byte)) {
            case 1:  // 都被重定向
                $countryOffset = $this->getLong3(); // 重定向地址
                fseek($this->fh, $countryOffset);
                $byte = fread($this->fh, 1); // 标志字节
                switch (ord($byte)) {
                    case 2: // 信息被二次重定向
                        fseek($this->fh, $this->getLong3());
                        $location['pos'] = $this->getPos();
                        fseek($this->fh, $countryOffset + 4);
                        $location['isp'] = $this->getISP();
                        break;
                    default: // 信息没有被二次重定向
                        $location['pos'] = $this->getPos($byte);
                        $location['isp'] = $this->getISP();
                        break;
                }
                break;

            case 2: // 信息被重定向
                fseek($this->fh, $this->getLong3());
                $location['pos'] = $this->getPos();
                fseek($this->fh, $offset + 8);
                $location['isp'] = $this->getISP();
                break;

            default: // 信息没有被重定向
                $location['pos'] = $this->getPos($byte);
                $location['isp'] = $this->getISP();
                break;
        }

        // 信息转码处理
        foreach ($location as $k => $v) {
            $location[$k] = iconv('gb2312', 'utf-8', $v);
            $location[$k] = preg_replace(array('/^.*CZ88\.NET.*$/isU', '/^.*纯真.*$/isU', '/^.*日IP数据/'), '', $location[$k]);
            $location[$k] = htmlspecialchars($location[$k]);
        }

        return $location;
    }

    // 更新数据库 https://www.22vd.com/40035.html
//    public function update() {
//        $this->ignore_timeout();
//        $copywrite = file_get_contents('http://update.cz88.net/ip/copywrite.rar');
//        $qqwry     = file_get_contents('http://update.cz88.net/ip/qqwry.rar');
//        $key       = unpack('V6', $copywrite)[6];
//        for($i = 0; $i < 0x200; $i++) {
//            $key *= 0x805;
//            $key ++;
//            $key = $key & 0xFF;
//            $qqwry[$i] = chr(ord($qqwry[$i]) ^ $key);
//        }
//        $qqwry = gzuncompress($qqwry);
//        file_put_contents($this->dbFile, $qqwry);
//    }

    // 析构函数
    function __destruct() {
        if($this->fh) {
            fclose($this->fh);
        }
        $this->fp = null;
    }
}