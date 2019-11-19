<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Element\Child;

/**
 * The video uploader.
 */
final class VideoUploader
{
    /** The video uploader's name */
    private string $name;
    /**  URL of a webpage with additional information about this uploader. This URL must be in the same domain as the <loc> tag. */
    private ?string $info;

    public function __construct(string $name, ?string $info = null)
    {
        $this->name = $name;
        $this->info = $info;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getInfo(): ?string
    {
        return $this->info;
    }
}
