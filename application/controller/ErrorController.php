<?php
namespace application\controller;

use umi\hmvc\component\request\IComponentRequest;
use umi\hmvc\controller\result\IControllerResult;
use umi\hmvc\controller\type\BaseController;
use umi\hmvc\exception\http\HttpException;

/**
 * Контроллер ошибок компонента.
 * @package App
 */
class ErrorController extends BaseController
{
    /**
     * @var \Exception $exception исключение
     */
    protected $exception;

    /**
     * Конструктор.
     * @param \Exception $e исключение
     */
    public function __construct(\Exception $e)
    {
        $this->exception = $e;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(IComponentRequest $request)
    {
        if ($this->exception instanceof HttpException) {
            return $this->httpException();
        }

        return $this->createControllerResult('error', ['e' => $this->exception])
            ->setCode(500);
    }

    /**
     * Обрабатывает HTTP исключения
     * @return IControllerResult
     */
    public function httpException()
    {
        if ($this->exception->getCode() == HttpException::HTTP_NOT_FOUND) {
            return $this->error404();
        }

        return $this->createControllerResult('error', ['e' => $this->exception])
            ->setCode($this->exception->getCode());
    }

    /**
     * Отображает 404 ошибку.
     * @return IControllerResult
     */
    public function error404()
    {
        return $this->createControllerResult('error404', ['e' => $this->exception])
            ->setCode(404);
    }
}