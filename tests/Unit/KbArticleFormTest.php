<?php

use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\KbArticleResource;

it('converts seo_keywords string from the core column into an array for TagsInput', function () {
    expect(KbArticleResource::seoKeywordsToArray('foo, bar, baz'))->toBe(['foo', 'bar', 'baz'])
        ->and(KbArticleResource::seoKeywordsToArray(null))->toBe([])
        ->and(KbArticleResource::seoKeywordsToArray(''))->toBe([]);
});

it('converts TagsInput array back into the string the core column expects', function () {
    expect(KbArticleResource::seoKeywordsToString(['foo', 'bar']))->toBe('foo, bar')
        ->and(KbArticleResource::seoKeywordsToString([]))->toBe('');
});
