<?php

namespace Adzbuck\LaravelUTM;

use Adzbuck\LaravelUTM\Helpers\Store;
use Illuminate\Http\Request;

class ParameterTracker
{
    public function __construct(
        protected Request $request,
        protected array $trackedParameters,
        protected string|false $firstTouchKey,
        protected string|false $lastTouchKey,
    ) {
    }

    public function handle(): void
    {
        if (! $this->firstTouchKey && ! $this->lastTouchKey) {
            return;
        }

        $currentParameters = $this->getFirstTouch();

        $parameters = $this->buildTrackerParams();

        if (! $parameters) {
            return;
        }

        if ($this->firstTouchKey && ! $currentParameters) {
            Store::set($this->firstTouchKey, $parameters);
        }

        if ($this->lastTouchKey) {
            Store::set($this->lastTouchKey, $parameters);
        }
    }

    public function getFirstTouch(): array
    {
        return Store::get($this->firstTouchKey, []);
    }

    public function getLastTouch(): array
    {
        return Store::get($this->lastTouchKey, []);
    }

    public function getCurrent(): array
    {
        return $this->buildTrackerParams();
    }

    protected function buildTrackerParams(): array
    {
        return collect($this->trackedParameters)
            ->mapWithKeys(function ($trackedParameter) {
                $source = new $trackedParameter['source']($this->request);

                return [$trackedParameter['key'] => $source->get($trackedParameter['key'])];
            })
            ->filter()
            ->toArray();
    }
}
