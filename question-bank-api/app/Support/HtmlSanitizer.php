<?php

namespace App\Support;

use DOMDocument;
use DOMElement;

/**
 *  Cette classe fournit une méthode pour nettoyer le HTML des enquêtes en utilisant une liste d'autorisation
 *  stricte. Elle utilise DOMDocument pour analyser et manipuler le HTML, en supprimant les balises et les attributs non autorisés, 
 *  et en s'assurant que les liens sont sûrs.
 */
class HtmlSanitizer
{
    private const ALLOWED_TAGS = [
        'p', 'br', 'strong', 'em', 'ul', 'ol', 'li', 'a', 'h1', 'h2', 'h3','i', 'b', 'u', 'span','small'
    ];

    /**
     * Sanitize rich text with a strict allowlist.
     */
    public static function sanitizeEnqueteHtml(string $html): string
    {
        $allowedTags = '<' . implode('><', self::ALLOWED_TAGS) . '>';
        $sanitized = strip_tags($html, $allowedTags);

        if ($sanitized === '') {
            return $sanitized;
        }

        $previous = libxml_use_internal_errors(true);
        $document = new DOMDocument('1.0', 'UTF-8');
        $document->loadHTML('<?xml encoding="utf-8" ?><div>' . $sanitized . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        foreach ($document->getElementsByTagName('*') as $element) {
            if (!$element instanceof DOMElement) {
                continue;
            }

            $tag = strtolower($element->tagName);
            self::sanitizeAttributes($element, $tag);
        }

        $output = $document->saveHTML($document->documentElement);
        if ($output === false) {
            return '';
        }

        return preg_replace('/^<div>|<\/div>$/', '', $output) ?? '';
    }

    private static function sanitizeAttributes(DOMElement $element, string $tag): void
    {
        $allowedAttributes = $tag === 'a' ? ['href', 'target', 'rel'] : [];

        if ($element->hasAttributes()) {
            $toRemove = [];
            foreach ($element->attributes as $attribute) {
                $name = strtolower($attribute->name);
                if (!in_array($name, $allowedAttributes, true)) {
                    $toRemove[] = $attribute->name;
                }
            }

            foreach ($toRemove as $attributeName) {
                $element->removeAttribute($attributeName);
            }
        }

        if ($tag !== 'a') {
            return;
        }

        $href = trim((string) $element->getAttribute('href'));
        if ($href !== '' && !preg_match('/^(https?:|mailto:|tel:|\/|#)/i', $href)) {
            $element->removeAttribute('href');
        }

        $target = strtolower((string) $element->getAttribute('target'));
        if ($target !== '' && !in_array($target, ['_blank', '_self', '_parent', '_top'], true)) {
            $element->removeAttribute('target');
            $target = '';
        }

        if ($target === '_blank') {
            $relValue = strtolower(trim((string) $element->getAttribute('rel')));
            $relParts = $relValue === '' ? [] : preg_split('/\s+/', $relValue);
            $relParts = is_array($relParts) ? $relParts : [];

            if (!in_array('noopener', $relParts, true)) {
                $relParts[] = 'noopener';
            }

            if (!in_array('noreferrer', $relParts, true)) {
                $relParts[] = 'noreferrer';
            }

            $element->setAttribute('rel', trim(implode(' ', array_unique($relParts))));
        }
    }
}