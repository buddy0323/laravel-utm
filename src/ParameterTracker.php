<?php

namespace Adzbuck\LaravelUTM;

use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;

class ParameterTracker
{
    public function __construct(
        protected Request $request,
        protected Session $session,
        protected array $trackedParameters,
        protected string|false $firstTouchSessionKey,
        protected string|false $lastTouchSessionKey,
    ) {
    }

    public function handle(): void
    {
        if (! $this->firstTouchSessionKey && ! $this->lastTouchSessionKey) {
            return;
        }

        $currentParameters = $this->getFirstTouch();

        $parameters = $this->buildTrackerParams();

        if (! $parameters) {
            return;
        }

        if ($this->firstTouchSessionKey && ! $currentParameters) {
            $this->session->put($this->firstTouchSessionKey, $parameters);
        }

        if ($this->lastTouchSessionKey) {
            $this->session->put($this->lastTouchSessionKey, $parameters);
        }
    }

    public function getFirstTouch(): array
    {
        return $this->session->get($this->firstTouchSessionKey, []);
    }

    public function getLastTouch(): array
    {
        return $this->session->get($this->lastTouchSessionKey, []);
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
