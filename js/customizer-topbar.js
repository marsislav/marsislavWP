/* global wp, jQuery */
/**
 * Topbar Live Preview — marsislav theme
 * Всички промени са динамични (postMessage)
 */
(function ($) {

    // ── Enable / Disable ─────────────────────────────────────────────────────
    wp.customize('topbar_enable', function (value) {
        value.bind(function (enabled) {
            if (enabled) {
                $('#site-topbar').slideDown(200);
            } else {
                $('#site-topbar').slideUp(200);
            }
        });
    });

    // ── Layout: 1 or 2 columns ───────────────────────────────────────────────
    wp.customize('topbar_layout', function (value) {
        value.bind(function (layout) {
            var $bar   = $('#site-topbar');
            var $inner = $bar.find('.topbar-inner');

            $bar.removeClass('layout-one layout-two').addClass('layout-' + layout);

            if (layout === 'two') {
                if ($bar.find('.topbar-col-1').length === 0) {
                    var $existing = $inner.children().detach();
                    var col2val   = wp.customize('topbar_col2_text') ? wp.customize('topbar_col2_text').get() : '';
                    $inner.html(
                        '<div class="topbar-col topbar-col-1"></div>' +
                        '<div class="topbar-col topbar-col-2"><div class="topbar-text topbar-col2-text">' + col2val + '</div></div>'
                    );
                    $inner.find('.topbar-col-1').append($existing);
                } else {
                    $bar.find('.topbar-col-2').show();
                }
            } else {
                if ($bar.find('.topbar-col-1').length > 0) {
                    var $col1Content = $bar.find('.topbar-col-1').children().detach();
                    $inner.html('').append($col1Content);
                }
            }
        });
    });

    // ── Marquee toggle ───────────────────────────────────────────────────────
    wp.customize('topbar_marquee', function (value) {
        value.bind(function (marquee) {
            var mainText = wp.customize('topbar_text') ? wp.customize('topbar_text').get() : '';
            var col1Text = wp.customize('topbar_col1_text') ? wp.customize('topbar_col1_text').get() : '';
            var layout   = wp.customize('topbar_layout') ? wp.customize('topbar_layout').get() : 'one';
            var displayText = (layout === 'two' && col1Text) ? col1Text : mainText;

            var $target = $('#site-topbar .topbar-col-1').length
                ? $('#site-topbar .topbar-col-1')
                : $('#site-topbar .topbar-inner');

            if (marquee) {
                $target.html('<div class="topbar-marquee"><span>' + displayText + '</span></div>');
            } else {
                $target.html('<div class="topbar-text">' + displayText + '</div>');
            }
        });
    });

    // ── Main text ────────────────────────────────────────────────────────────
    wp.customize('topbar_text', function (value) {
        value.bind(function (text) {
            var $marquee = $('#site-topbar .topbar-inner > .topbar-marquee span, #site-topbar .topbar-col-1 .topbar-marquee span');
            var $textEl  = $('#site-topbar .topbar-inner > .topbar-text, #site-topbar .topbar-col-1 .topbar-text');
            if ($marquee.length) { $marquee.text(text); }
            else if ($textEl.length) { $textEl.text(text); }
        });
    });

    // ── Column 1 text ────────────────────────────────────────────────────────
    wp.customize('topbar_col1_text', function (value) {
        value.bind(function (text) {
            var $col1text    = $('#site-topbar .topbar-col-1 .topbar-text');
            var $col1marquee = $('#site-topbar .topbar-col-1 .topbar-marquee span');
            if ($col1marquee.length) { $col1marquee.html(text); }
            else if ($col1text.length) { $col1text.html(text); }
            updateFullWidthCols();
        });
    });

    // ── Column 2 text ────────────────────────────────────────────────────────
    wp.customize('topbar_col2_text', function (value) {
        value.bind(function (text) {
            var $col2 = $('#site-topbar .topbar-col2-text');
            if ($col2.length) { $col2.html(text); }
            updateFullWidthCols();
        });
    });

    // ── Background color ─────────────────────────────────────────────────────
    wp.customize('topbar_bg_color', function (value) {
        value.bind(function (color) {
            updateTopbarStyle('bg', color);
        });
    });

    // ── Text color ───────────────────────────────────────────────────────────
    wp.customize('topbar_text_color', function (value) {
        value.bind(function (color) {
            updateTopbarStyle('text', color);
        });
    });

    // ── Helper: full-width col when the other is empty ───────────────────────
    function updateFullWidthCols() {
        var $col1 = $('#site-topbar .topbar-col-1');
        var $col2 = $('#site-topbar .topbar-col-2');
        if (!$col1.length || !$col2.length) { return; }
        var col1Empty = $col1.text().trim() === '';
        var col2Empty = $col2.text().trim() === '';
        $col1.toggleClass('topbar-col-full', col2Empty && !col1Empty);
        $col2.toggleClass('topbar-col-full', col1Empty && !col2Empty);
    }

    // ── Helper: keep inline <style> in sync ──────────────────────────────────
    function updateTopbarStyle(type, color) {
        var bg   = type === 'bg'   ? color : (wp.customize('topbar_bg_color')   ? wp.customize('topbar_bg_color').get()   : '#000000');
        var text = type === 'text' ? color : (wp.customize('topbar_text_color') ? wp.customize('topbar_text_color').get() : '#ffffff');

        var css =
            '#site-topbar { background-color: ' + bg + '; color: ' + text + '; }' +
            '#site-topbar a { color: ' + text + '; }' +
            '#site-topbar .topbar-marquee span { color: ' + text + '; }';

        var $style = $('#marsislav-topbar-colors');
        if (!$style.length) {
            $style = $('<style id="marsislav-topbar-colors"></style>').appendTo('head');
        }
        $style.text(css);
    }

})(jQuery);
