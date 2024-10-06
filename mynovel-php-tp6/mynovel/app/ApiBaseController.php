<?php

declare(strict_types=1);

namespace app;


class ApiBaseController extends BaseController
{
    // 是否自动实例化当前模型
    protected $autoModel = true;
    // 是否开启参数自动验证
    protected $autoValidate = true;
}
