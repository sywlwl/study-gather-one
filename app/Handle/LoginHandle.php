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
namespace App\Handle;

use App\Annotation\Handle;
use App\Cmd\ResponseLoginCmd;
use App\Codec\Frame;
use App\Constants\Cmd;
use App\Contract\AuthedChannel;
use App\Contract\HandleInterface;
use App\Tool\BCD;
use App\Tool\BIN;
use Hyperf\Di\Annotation\Inject;

/**
 * 登录处理.
 */
#[Handle]
class LoginHandle implements HandleInterface
{
    #[Inject]
    public AuthedChannel $authedChannel;

    public function __construct()
    {
        HandleFactory::register(Cmd::X01, $this);
    }

    public function run(Frame $frame, int $fd)
    {
        $data = $frame->getData();

        $reader = BIN::newReader($data);

        $pile = BCD::bin2Bcd($reader->readSlice(7));

        // 类型
        $mode = $reader->readChar();

        // 枪的数量
        $num = $reader->readChar();

        // 通讯版本
        $version1 = $reader->readChar();

        // 程序版本
        $version2 = BCD::bin2Bcd($reader->readSlice(8));

        // 网络类型
        $networkType = $reader->readChar();

        // sim卡
        $sim = BCD::bin2Bcd($reader->readSlice(10));

        // 运营商
        $carrier = $reader->readChar();

        echo '这是 ', Cmd::getMessage($frame->getCmd()), "\n";
        echo $pile, "\n";
        echo $mode, "\n";
        echo $num, "\n";
        echo $version1, "\n";
        echo $version2, "\n";
        echo $networkType, "\n";
        echo $sim, "\n";
        echo $carrier, "\n";
        echo '====================', "\n";

        /**
         * 67569755522001
         * 1
         * 1
         * 16  1.6
         * 4D333332315F3348
         * 3
         * 00000000000000000000
         * 4.
         */

        // todo 查看桩号是否注册 如果注册 记录授权通道
        $loginResult = 0;

        if (! $this->authedChannel->getChannel($pile)) {
            $this->authedChannel->addChannel($pile, $fd);
        }
        // 响应登录
        $resFrame = ResponseLoginCmd::make($pile, $loginResult, $frame);

        // 需要发给客户端
        $this->authedChannel->send($pile, $resFrame);
    }
}
