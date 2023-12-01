<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Command;

use App\Tool\BCD;
use App\Tool\BIN;
use App\Tool\CP56Time2a;
use Hyperf\Command\Annotation\Command;
use Hyperf\Command\Command as HyperfCommand;
use Psr\Container\ContainerInterface;

#[Command]
/**
 * @internal
 * @coversNothing
 */
class BinTest extends HyperfCommand
{
    public function __construct(protected ContainerInterface $container)
    {
        parent::__construct('bin:test');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('Bin Test');
    }

    public function handle()
    {
        echo BIN::bin2ShortLE(BCD::bcd2Bin('0570'));
//        $b = BIN::double2BinLE(1.22223333222);
//        echo BIN::bin2DoubleLE($b), PHP_EOL;
//        BIN::dump(BIN::double2BinLE(1.2222222));
//        BIN::dump(BIN::float2Bin(1.2222222));
//        BIN::dump(BIN::float2BinLE(1.2222222));
//        $writer = BIN::newWriter();
//
//        $writer->writeFloat(1.1);
//        $bin = $writer->getBin();

//        $date = new \DateTime();
//        echo $date->format('Y-m-d H:i:s.v'), "\n";
//        $bin = CP56Time2a::datetime2Bin($date);
//        BIN::dump($bin);
//
//        $dateTime = CP56Time2a::bin2Datetime($bin);
//        echo $dateTime->format('Y-m-d H:i:s.v'), "\n";
//
//        echo '======================', "\n";
//        usleep(1000);
//        $date = new \DateTime();
//        echo $date->format('Y-m-d H:i:s.v'), "\n";
//        $bin = CP56Time2a::datetime2BinLE($date);
//        BIN::dump($bin);
//
//        $dateTime = CP56Time2a::bin2DatetimeLE($bin);
//        echo $dateTime->format('Y-m-d H:i:s.v'), "\n";

        // echo $date->format("Y-m-d H:i:s.v"), "\n";
        // 3F8CCCCD
        // BIN::dump(BCD::bcd2Bin('20201111232323')); // , "\n";

//        $writer->writeInt(10);
//        $writer->writeLongLE(20);
//
//        $bin = $writer->getBin();
//        echo $writer->getIndex(), "\n";
//
//        $reader = BIN::newReader($bin);
//
//        echo $reader->readFloat(), "\n";
//        echo $reader->readInt(), "\n";
//        echo $reader->readLongLE(), "\n";
        // 3F 8C CC CD
        // 00 00 00 0A
        // 14 00 00 00 00 00 00 00
    }
}
