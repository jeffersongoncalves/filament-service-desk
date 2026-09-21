<?php

use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\KbArticles\Schemas\KbArticleForm;

it('converts seo_keywords string from the core column into an array for TagsInput', function () {
    expect(KbArticleForm::seoKeywordsToArray('foo, bar, baz'))->toBe(['foo', 'bar', 'baz'])
        ->and(KbArticleForm::seoKeywordsToArray(null))->toBe([])
        ->and(KbArticleForm::seoKeywordsToArray(''))->toBe([]);
});

it('converts TagsInput array back into the string the core column expects', function () {
    expect(KbArticleForm::seoKeywordsToString(['foo', 'bar']))->toBe('foo, bar')
        ->and(KbArticleForm::seoKeywordsToString([]))->toBe('');
});
