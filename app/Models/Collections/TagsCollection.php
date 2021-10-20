<?php

namespace App\Models\Collections;

use App\Models\Tag;

class TagsCollection
{
    private array $tags;

    public function __construct()
    {
        $this->tags = [];
    }
    public function add(Tag $tag): void
    {
        $this->tags[] = $tag;
    }
    public function search(int $productId): TagsCollection
    {
        $tagsCollection = new TagsCollection();
        foreach ($this->tags as $tag)
        {
            if ($productId === $tag->getId()) $tagsCollection->add($tag);
        }
        return $tagsCollection;
    }
}