<?php

namespace Webid\Druid\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Webid\Druid\Models\Post;
use Webid\Druid\Services\ComponentDisplayContentExtractor;

class PostResource extends JsonResource
{
    /** @var Post */
    public $resource;

    /**
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return $this->toArrayWithoutRequestContext();
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayWithoutRequestContext(): array
    {
        /** @var ComponentDisplayContentExtractor $componentDisplayContentExtractor */
        $componentDisplayContentExtractor = app()->make(ComponentDisplayContentExtractor::class);

        return [
            'id' => $this->resource->getKey(),
            'title' => $this->resource->title,
            'slug' => $this->resource->slug,
            'lang' => $this->resource->lang,
            'url' => $this->resource->url(),
            'content' => $componentDisplayContentExtractor->getContentFromBlocks($this->resource->content),
            'thumbnail' => MediaResource::make($this->whenLoaded('thumbnail')),
            'searchable_content' => $this->resource->searchable_content,
            'status' => $this->resource->status->value,
            'indexation' => $this->getIndexationValue($this->resource->disable_indexation),
            'meta_title' => $this->resource->meta_title ?? $this->resource->title,
            'meta_description' => $this->resource->meta_description,
            'meta_keywords' => $this->resource->meta_keywords,
            'opengraph_title' => $this->resource->opengraph_title,
            'opengraph_description' => $this->resource->opengraph_description,
            'opengraph_picture' => MediaResource::make(optional($this->whenLoaded('openGraphPicture'))),
            'opengraph_picture_alt' => $this->resource->opengraph_picture_alt,
            'canonical' => $this->resource->url(),
            'deleted_at' => $this->resource->deleted_at,
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'translations' => $this->whenLoaded('translations') ? PostResource::collection($this->resource->translations) : [],
        ];
    }

    public function toObject(): object
    {
        return (object) $this->toArrayWithoutRequestContext();
    }

    private function getIndexationValue(bool $disableIndexation): string
    {
        if (config('cms.disable_robots_follow') === true || $disableIndexation) {
            return 'nofollow,noindex';
        }

        return 'index,follow';
    }
}
