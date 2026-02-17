@php
    $template = (string) ($session->game->custom_template ?? '');
    $mobileCssTag = '<link rel="stylesheet" href="' . e(asset('css/mobile-responsive-fix.css')) . '">';
    $viewportTag = '<meta name="viewport" content="width=device-width, initial-scale=1.0">';

    $insertBeforeClosingHead = static function (string $html, string $snippet): string {
        $headClosePos = stripos($html, '</head>');
        if ($headClosePos === false) {
            return $snippet . "\n" . $html;
        }

        return substr($html, 0, $headClosePos) . $snippet . "\n" . substr($html, $headClosePos);
    };

    if (stripos($template, 'mobile-responsive-fix.css') === false) {
        $template = $insertBeforeClosingHead($template, $mobileCssTag);
    }

    if (stripos($template, 'name="viewport"') === false) {
        $template = $insertBeforeClosingHead($template, $viewportTag);
    }
@endphp

{!! $template !!}
