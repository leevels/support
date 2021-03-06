<?php

declare(strict_types=1);

namespace Leevel\Support\Arr;

use ArrayObject;
use JsonSerializable;
use Leevel\Support\IArray;
use Leevel\Support\IJson;

/**
 * 是否可以转换为 JSON.
 */
function should_json(mixed $content): bool
{
    return $content instanceof IJson ||
        $content instanceof IArray ||
        $content instanceof ArrayObject ||
        $content instanceof JsonSerializable ||
        is_array($content);
}

class should_json
{
}
