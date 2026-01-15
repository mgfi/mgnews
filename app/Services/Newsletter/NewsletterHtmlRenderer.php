<?php

namespace App\Services\Newsletter;

use App\Models\NewsletterClick;
use Illuminate\Support\Str;
use DOMDocument;
use DOMXPath;

class NewsletterHtmlRenderer
{
    protected function generateClickUrl(
        int $issueId,
        ?int $subscriberId,
        string $targetUrl,
        ?string $targetType = 'url',
        ?int $targetId = null
    ): string {
        $hash = Str::random(40);

        NewsletterClick::create([
            'newsletter_issue_id' => $issueId,
            'subscriber_id'       => $subscriberId,
            'target_type'         => $targetType,
            'target_id'           => $targetId,
            'target_url'          => $targetUrl,
            'hash'                => $hash,
        ]);

        return url('/newsletter/click/' . $hash);
    }
    protected function replaceLinksWithTracking(
        string $html,
        int $issueId,
        ?int $subscriberId
    ): string {
        libxml_use_internal_errors(true);

        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->loadHTML(
            mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'),
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );

        $xpath = new DOMXPath($dom);
        $links = $xpath->query('//a[@href]');

        foreach ($links as $link) {
            /** @var \DOMElement $link */

            $href = $link->getAttribute('href');

            // pomijamy maile, tel, kotwice, unsubscribe, open pixel
            if (
                str_starts_with($href, 'mailto:') ||
                str_starts_with($href, 'tel:') ||
                str_starts_with($href, '#') ||
                str_contains($href, '/newsletter/unsubscribe') ||
                str_contains($href, '/newsletter/open')
            ) {
                continue;
            }

            $trackingUrl = $this->generateClickUrl(
                $issueId,
                $subscriberId,
                $href
            );

            $link->setAttribute('href', $trackingUrl);
        }

        return $dom->saveHTML();
    }

    /**
     * Renderuje cały newsletter (wszystkie wiersze)
     */
    public function render(array $rows): string
    {
        $html = '';

        foreach ($rows as $row) {
            $html .= $this->renderRow($row);
        }

        $html = $this->replaceLinksWithTracking(
            $html,
            1,      // tymczasowo issue_id
            null    // tymczasowo subscriber_id
        );

        return $this->wrap($html);
    }

    /**
     * Renderuje pojedynczy wiersz (IMG / P / P P itd.)
     */
    protected function renderRow(array $row): string
    {
        $columns = count($row);
        $width = floor(100 / $columns);

        $cells = '';

        foreach ($row as $block) {
            $cells .= '<td width="' . $width . '%" style="padding:10px; vertical-align:top;">';
            $cells .= $this->renderBlock($block);
            $cells .= '</td>';
        }

        return '<tr>' . $cells . '</tr>';
    }

    /**
     * Renderuje pojedynczy blok
     */
    protected function renderBlock(array $block): string
    {
        return match ($block['type'] ?? null) {
            'p'   => $this->renderParagraph($block),
            'img' => $this->renderImage($block),
            default => '',
        };
    }

    /**
     * Renderuje blok tekstowy (P)
     */
    protected function renderParagraph(array $block): string
    {
        return '<div style="font-family:Arial,sans-serif;font-size:14px;line-height:1.6;color:#000;">'
            . ($block['html'] ?? '')
            . '</div>';
    }

    /**
     * Renderuje blok obrazka (IMG)
     */
    protected function renderImage(array $block): string
    {
        if (empty($block['image_path'])) {
            return '';
        }

        $src = asset('storage/' . $block['image_path']);
        $alt = e($block['alt'] ?? '');

        return '<img src="' . $src . '" alt="' . $alt . '" width="100%" style="display:block;max-width:100%;height:auto;border:0;outline:none;text-decoration:none;">';
    }


    /**
     * Opakowanie HTML maila (600px, kompatybilne z email clientami)
     */
    /**
     * Wrap full newsletter HTML (email-safe, 600px container)
     */
    protected function wrap(string $content): string
    {
        return '<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body style="margin:0;padding:0;background:#f4f4f4;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4;">
<tr>
<td align="center">
<table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;">
' . $content . '
</table>
</td>
</tr>
</table>
</body>
</html>';
    }
}
