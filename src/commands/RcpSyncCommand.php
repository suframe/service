<?php
namespace suframe\service\commands;

use suframe\core\components\Config;
use suframe\core\components\register\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RcpSyncCommand  extends Command{

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $out = new \suframe\core\components\console\SymfonyStyle($input, $output);
        go(function () use ($out){
            try{
                $rs = Client::getInstance()->commandUpdateServers();
                $rs = $rs && Client::getInstance()->syncRpc();
                if($rs){
                    $out->success('success');
                } else {
                    $out->error('fail');
                }
            } catch (\Exception $e){
                $out->error('fail:' . $e->getMessage());
            }
        });
    }

    protected function configure()
    {
        $this->setName('rpc:sync')
            ->setDescription('sync rpc interface')
        ;
    }
}