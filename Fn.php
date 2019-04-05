<?php

declare(strict_types=1);

/*
 * This file is part of the ************************ package.
 * _____________                           _______________
 *  ______/     \__  _____  ____  ______  / /_  _________
 *   ____/ __   / / / / _ \/ __`\/ / __ \/ __ \/ __ \___
 *    __/ / /  / /_/ /  __/ /  \  / /_/ / / / / /_/ /__
 *      \_\ \_/\____/\___/_/   / / .___/_/ /_/ .___/
 *         \_\                /_/_/         /_/
 *
 * The PHP Framework For Code Poem As Free As Wind. <Query Yet Simple>
 * (c) 2010-2019 http://queryphp.com All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Leevel\Support;

use Closure;
use Error;

/**
 * 函数自动导入.
 *
 * @author Xiangmin Liu <635750556@qq.com>
 *
 * @since 2019.04.05
 *
 * @version 1.0
 */
class Fn
{
    /**
     * 自动导入函数.
     *
     * @param \Closure $call
     * @param array    $args
     *
     * @return mixed
     */
    public function __invoke(Closure $fn, ...$args)
    {
        try {
            return $fn(...$args);
        } catch (Error $th) {
            $fnName = $this->normalizeFn($th);

            foreach (['Fn', 'Prefix', 'Index'] as $type) {
                if ($this->{'is'.$type}($fnName)) {
                    return $fn(...$args);
                }
            }

            throw $th;
        }
    }

    /**
     * 整理函数名字.
     *
     * @param \Error $th
     *
     * @return string
     */
    protected function normalizeFn(Error $th): string
    {
        $message = $th->getMessage();
        $fnMessage = 'Call to undefined function ';

        if (0 !== strpos($message, $fnMessage)) {
            throw $th;
        }

        $fn = substr($message, strlen($fnMessage), -2);

        return $fn;
    }

    /**
     * 一个函数一个文件.
     *
     * @param string $fn
     *
     * @return bool
     */
    protected function isFn(string $fn): bool
    {
        return class_exists($fn);
    }

    /**
     * 前缀分隔一组函数.
     *
     * @param string $fn
     *
     * @return bool
     */
    protected function isPrefix(string $fn): bool
    {
        if (false === strpos($fn, '_')) {
            return false;
        }

        $fnPrefix = substr($fn, 0, strpos($fn, '_'));

        return class_exists($fnPrefix);
    }

    /**
     * 基于 index 索引.
     *
     * @param string $fn
     *
     * @return bool
     */
    protected function isIndex(string $fn): bool
    {
        $fnIndex = substr($fn, 0, strripos($fn, '\\')).'\\index';

        return class_exists($fnIndex);
    }
}