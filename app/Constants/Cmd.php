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
namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

#[Constants]
class Cmd extends AbstractConstants
{
    /**
     * @Message("X01 充电桩登录认证")
     */
    public const X01 = 0x01;

    /**
     * @Message("X02 登录认证应答")
     */
    public const X02 = 0x02;

    /**
     * @Message("X03 充电桩心跳包")
     */
    public const X03 = 0x03;

    /**
     * @Message("X04 心跳包应答")
     */
    public const X04 = 0x04;

    /**
     * @Message("X05 计费模型验证请求")
     */
    public const X05 = 0x05;

    /**
     * @Message("X06 计费模型验证请求应答")
     */
    public const X06 = 0x06;

    /**
     * @Message("X09 充电桩计费模型请求")
     */
    public const X09 = 0x09;

    /**
     * @Message("X0A 计费模型请求应答")
     */
    public const X0A = 0x0A;

    /**
     * @Message("X12 读取实时监测数据")
     */
    public const X12 = 0x12;

    /**
     * @Message("X13 上传实时监测数据")
     */
    public const X13 = 0x13;

    /**
     * @Message("X33 远程启动充电命令回复")
     */
    public const X33 = 0x33;

    /**
     * @Message("X34 运营平台远程控制启机")
     */
    public const X34 = 0x34;

    /**
     * @Message("X35 远程停机命令回复")
     */
    public const X35 = 0x35;

    /**
     * @Message("X36 运营平台远程停机")
     */
    public const X36 = 0x36;

    /**
     * @Message("X3B 交易记录")
     */
    public const X3B = 0x3B;

    /**
     * @Message("X40 交易记录确认")
     */
    public const X40 = 0x40;

    /**
     * @Message("X55 对时设置应答")
     */
    public const X55 = 0x55;

    /**
     * @Message("X56 对时设置")
     */
    public const X56 = 0x56;
}
