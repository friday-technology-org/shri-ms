<?php

namespace Cms\Core\Services;

class HookManager
{
    protected array $actions = [];
    protected array $filters = [];

    /**
     * Register an action callback.
     */
    public function addAction(string $hook, callable $callback, int $priority = 10): void
    {
        $this->actions[$hook][$priority][] = $callback;
    }

    /**
     * Execute action callbacks registered for a hook.
     */
    public function doAction(string $hook, ...$args): void
    {
        if (empty($this->actions[$hook])) {
            return;
        }

        // Sort by priority key
        ksort($this->actions[$hook]);

        foreach ($this->actions[$hook] as $priority => $callbacks) {
            foreach ($callbacks as $callback) {
                call_user_func_array($callback, $args);
            }
        }
    }

    /**
     * Register a filter callback.
     */
    public function addFilter(string $hook, callable $callback, int $priority = 10): void
    {
        $this->filters[$hook][$priority][] = $callback;
    }

    /**
     * Apply filter callbacks to a value.
     */
    public function applyFilters(string $hook, mixed $value, ...$args): mixed
    {
        if (empty($this->filters[$hook])) {
            return $value;
        }

        // Sort by priority key
        ksort($this->filters[$hook]);

        foreach ($this->filters[$hook] as $priority => $callbacks) {
            foreach ($callbacks as $callback) {
                $value = call_user_func_array($callback, array_merge([$value], $args));
            }
        }

        return $value;
    }
}
