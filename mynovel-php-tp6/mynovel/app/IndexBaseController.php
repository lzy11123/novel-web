<?php

declare(strict_types=1);

namespace app;


class IndexBaseController extends BaseController
{
    // 是否自动实例化当前模型
    protected $autoModel = false;
    // 是否开启参数自动验证
    protected $autoValidate = false;
}
