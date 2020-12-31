<?php declare(strict_types=1);
namespace openvk\Web\Models\Repositories;
use Chandler\Database\DatabaseConnection;
use openvk\Web\Models\Entities\IP;

class IPs
{
    private $context;
    private $ips;
    
    function __construct()
    {
        $this->context = DatabaseConnection::i()->getContext();
        $this->ips     = $this->context->table("ip");
    }
    
    function get(string $ip): ?IP
    {
        $bip = inet_pton($ip);
        if(!$bip)
            throw new \UnexpectedValueException("Malformed IP address");
        
        $res = $this->ips->get($bip);
        if(!$res) {
            $res = new IP;
            $res->setIp($ip);
            $res->save();
            
            return $res;
        }
        
        return new IP($res);
    }
}
