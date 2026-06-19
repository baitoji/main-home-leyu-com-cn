<?php

/**
 * Site meta information container with description generation.
 *
 * This file provides a structured way to store site metadata and generate
 * a short descriptive text for use in templates or API responses.
 */

class SiteMeta
{
    /**
     * @var array<string, mixed> Site metadata store.
     */
    private array $meta = [];

    /**
     * Create a new SiteMeta instance with default values.
     *
     * @param array $defaults Optional initial metadata array.
     */
    public function __construct(array $defaults = [])
    {
        $this->meta = array_merge([
            'name'        => '',
            'url'         => '',
            'keywords'    => [],
            'description' => '',
            'language'    => 'zh-CN',
            'charset'     => 'UTF-8',
        ], $defaults);
    }

    /**
     * Set a single metadata field.
     *
     * @param string $key   Field name.
     * @param mixed  $value Field value.
     * @return void
     */
    public function set(string $key, $value): void
    {
        $this->meta[$key] = $value;
    }

    /**
     * Get a metadata field, or the whole array.
     *
     * @param string|null $key     Optional field name.
     * @param mixed       $default Default value if key not found.
     * @return mixed
     */
    public function get(?string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->meta;
        }
        return $this->meta[$key] ?? $default;
    }

    /**
     * Generate a short description text based on stored metadata.
     *
     * The description uses the site name, URL, and up to three keywords.
     * All output is HTML-escaped for safe use in web pages.
     *
     * @return string Generated description text.
     */
    public function generateDescription(): string
    {
        $name = htmlspecialchars($this->meta['name'] ?? '', ENT_QUOTES, $this->meta['charset']);
        $url  = htmlspecialchars($this->meta['url'] ?? '', ENT_QUOTES, $this->meta['charset']);

        $keywords = $this->meta['keywords'] ?? [];
        if (is_string($keywords)) {
            $keywords = [$keywords];
        }
        $keywordList = array_slice((array)$keywords, 0, 3);
        $keywordStr  = '';
        if (!empty($keywordList)) {
            $escaped = array_map(function ($kw) {
                return htmlspecialchars((string)$kw, ENT_QUOTES, $this->meta['charset']);
            }, $keywordList);
            $keywordStr = implode('、', $escaped);
        }

        $parts = [];
        if ($name !== '') {
            $parts[] = $name;
        }
        if ($url !== '') {
            $parts[] = $url;
        }
        if ($keywordStr !== '') {
            $parts[] = '关键词：' . $keywordStr;
        }

        return implode(' - ', $parts);
    }

    /**
     * Output the description as a plain text string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->generateDescription();
    }
}

// ---------------------------------------------------------------------------
// Example usage with sample data
// ---------------------------------------------------------------------------

$siteMeta = new SiteMeta([
    'name'        => '乐鱼体育',
    'url'         => 'https://main-home-leyu.com.cn',
    'keywords'    => ['乐鱼体育', '体育赛事', '在线娱乐'],
    'description' => '乐鱼体育官方首页，提供丰富体育赛事与娱乐资讯。',
]);

// Override description via setter to use auto-generation
$siteMeta->set('description', '');

echo '<meta name="description" content="' . $siteMeta->generateDescription() . '">' . "\n";

// Alternatively, just echo the object
// echo $siteMeta;