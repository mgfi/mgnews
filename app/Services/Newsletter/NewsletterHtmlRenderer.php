<?php

namespace App\Services\Newsletter;

class NewsletterHtmlRenderer
{
    /**
     * Renderuje cały newsletter (wszystkie wiersze)
     */
    public function render(array $rows): string
    {
        $html = '';

        foreach ($rows as $row) {
            $html .= $this->renderRow($row);
        }

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
