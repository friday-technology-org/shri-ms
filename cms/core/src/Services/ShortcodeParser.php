<?php

namespace Cms\Core\Services;

class ShortcodeParser
{
    protected array $shortcodes = [];

    /**
     * Register a new shortcode tag and handler.
     */
    public function register(string $tag, callable $callback): void
    {
        $this->shortcodes[$tag] = $callback;
    }

    /**
     * Parse shortcodes inside the content.
     */
    public function parse(string $content): string
    {
        if (empty($this->shortcodes)) {
            return $content;
        }

        // Match [shortcode_name key="val" ...]
        $pattern = '/\[([a-zA-Z0-9_\-]+)([^\]]*)\]/';

        return preg_replace_callback($pattern, function ($matches) {
            $tag = $matches[1];
            $attrString = $matches[2];

            if (!isset($this->shortcodes[$tag])) {
                return $matches[0]; // Return unchanged if not registered
            }

            $attributes = $this->parseAttributes($attrString);
            return call_user_func($this->shortcodes[$tag], $attributes);
        }, $content);
    }

    /**
     * Parse attributes string into an associative array.
     */
    protected function parseAttributes(string $text): array
    {
        $attributes = [];
        $pattern = '/(\w+)\s*=\s*"([^"]*)"|(\w+)\s*=\s*\'([^\']*)\'|(\w+)\s*=\s*([^\s\'"]+)/';

        if (preg_match_all($pattern, $text, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                if (!empty($match[1])) {
                    $attributes[$match[1]] = $match[2];
                } elseif (!empty($match[3])) {
                    $attributes[$match[3]] = $match[4];
                } elseif (!empty($match[5])) {
                    $attributes[$match[5]] = $match[6];
                }
            }
        }

        return $attributes;
    }
}
