<?php

namespace App\Protocols;

use App\Utils\Helper;

class Happ
{
    public $flag = 'happ';
    private $servers;
    private $user;

    public function __construct($user, $servers)
    {
        $this->user = $user;
        $this->servers = $servers;
    }

    public function handle()
    {
        $appName = config('v2board.app_name', 'V2Board');
        
        $routingRule = [
            "Name" => "{$appName}",
            "GlobalProxy" => "false",
            "RemoteDns" => "",
            "DomesticDns" => "",
            "Geoipurl" => "https://v6.gh-proxy.org/https://github.com/Loyalsoldier/v2ray-rules-dat/releases/latest/download/geoip.dat",
            "Geositeurl" => "https://v6.gh-proxy.org/https://github.com/Loyalsoldier/v2ray-rules-dat/releases/latest/download/geosite.dat",
            "DnsHosts" => new \stdClass(),
            "DirectSites" => ["geosite:cn"],
            "DirectIp" => ["geoip:cn", "geoip:private"],
            "ProxySites" => [],
            "ProxyIp" => [],
            "BlockSites" => [],
            "BlockIp" => [],
            "RouteOrder" => "block-direct-proxy",
            "DomainStrategy" => "AsIs"
        ];
        
        // 生成 happ:// 路由规则并追加回车换行
        $routingString = 'happ://routing/onadd/' . base64_encode(json_encode($routingRule, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)) . "\r\n";
        
        $uri = '';
        foreach ($this->servers as $server) {
            $uri .= Helper::buildUri($this->user['uuid'], $server);
        }
        
        // 拼接整个内容并整体 Base64 编码，符合标准 Xray Base64 订阅格式
        return base64_encode($routingString . $uri);
    }
}
