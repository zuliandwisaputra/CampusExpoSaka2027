<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

/** Membuat slug unik otomatis dari kolom nama/judul. */
trait HasSlug
{
    public static function bootHasSlug(): void
    {
        static::saving(function ($model) {
            $source = $model->{$model->slugSource()};

            if (blank($model->slug) || ($model->isDirty($model->slugSource()) && ! $model->isDirty('slug'))) {
                $model->slug = $model->uniqueSlug((string) $source);
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected function slugSource(): string
    {
        return 'nama';
    }

    protected function uniqueSlug(string $text): string
    {
        $base = Str::slug($text) ?: 'item';
        $slug = $base;
        $i = 2;

        while (static::where('slug', $slug)->when($this->exists, fn ($q) => $q->whereKeyNot($this->getKey()))->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}
