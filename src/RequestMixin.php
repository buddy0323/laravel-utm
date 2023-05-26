<?php

namespace Adzbuck\LaravelUTM;

class RequestMixin
{
    public function __construct(
        protected ParameterTracker $parameterTracker,
    ) {

    }

    public function getFirstTouch(): array
    {
        return $this->parameterTracker->getFirstTouch();
    }

    public function getLastTouch(): array
    {
        return $this->parameterTracker->getLastTouch();
    }

    public function getCurrent(): array
    {
        return $this->parameterTracker->getCurrent();
    }
}
