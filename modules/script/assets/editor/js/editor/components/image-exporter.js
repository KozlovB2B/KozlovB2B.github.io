/**
 * Экспортилка скрипта в SVG картинку
 *
 * @constructor
 */
var ImageExporter = function () {
    YiijBaseComponent.apply(this, []);
};

/**
 * Extends
 * @type {YiijBaseComponent}
 */
ImageExporter.prototype = Object.create(YiijBaseComponent.prototype);
ImageExporter.prototype.constructor = ImageExporter;


/**
 * Изменяет зум и центр так, чтобы весь скрипт уместился в экран
 */
ImageExporter.prototype.export = function () {
    var culmann = Yiij.app.getModule('editor').culmann;
    var padding = 40;
    var area = culmann.getObjectsArea(padding);

    var elem = $('#editor___image_export');

    if (!elem.length) {

        elem = $('<div id="editor___image_export" class="editor___image_export" style="display: none"></div>');

        $('body').append(elem);
    }

    elem.empty();

    // create svg drawing
    var draw = SVG('editor___image_export').size(area.width + 'px', area.height + 'px').attr({
        encode: 'utf-8'
    });

    culmann.coordinator.find('svg').each(function () {
        if ($(this).is(':visible')) {
            var width = $(this).outerWidth();
            var height = $(this).outerHeight();
            var abs_top = parseInt($(this).css('top')) - area.top;
            var abs_left = parseInt($(this).css('left')) - area.left;

            var arrow = draw.nested().move(abs_left, abs_top).size(width + 'px', height + 'px');

            $(this).find('path').each(function () {
                var path = arrow.path($(this).attr('d'));
                path.attr({
                    fill: $(this).attr('fill'),
                    stroke: $(this).attr('stroke'),
                    transform: $(this).attr('transform'),
                    'stroke-width': $(this).attr('stroke-width')
                });
            });
        }
    });

    culmann.coordinator.find('.node, .group').each(function () {
        if ($(this).is(':visible')) {
            var create_button = $(this).find('.group-variant-create-button, .variant-create-button');
            var width = $(this).outerWidth();
            var height = $(this).outerHeight() - create_button.outerHeight();
            var abs_top = parseInt($(this).css('top')) - area.top;
            var abs_left = parseInt($(this).css('left')) - area.left;
            var offset_y = 0;

            var node = draw.nested().move(abs_left, abs_top).size(width + 'px', height + 'px').attr({
                'style': 'overflow:hidden'
            });

            var html_head = $(this).find('.head');
            var bg = html_head.css('background-color');
            var padding = parseInt($(this).css('padding-left'));

            node.rect(width, height).attr({fill: bg});

            var functions_elem = $(this).find('.node-functions, .group-functions');
            var title_elem = $(this).find('.node-title, .group-title');
            var title = node.text(title_elem.text()).attr({
                x: padding,
                y: offset_y
            });
            title.font({
                family: title_elem.css('font-family'),
                size: title_elem.css('font-size'),
                anchor: 'left'
            }).fill({color: title_elem.css('color')});

            offset_y += padding + functions_elem.outerHeight();


            // Разбиение содержимого узла на tspan
            // Для определения переноса строки, который форсится CSS используется следущий способ:
            // Исходных элемент опустошается и потом наполняется по 1 слову, которые он содержал
            // в момент изменения высоты элемента - формируем новый tspan с переносом строки
            var content_elem = $(this).find('.node-content, .group-name');
            content_elem.addClass('svg-wordwrap-test');

            var content_text_array = content_elem.text().split(' ');

            content_elem.empty();

            var content_height = content_elem.outerHeight();
            var line = [];


            var content = node
                .text(function (add) {
                    for (var w = 0; w < content_text_array.length; w++) {

                        content_elem.append(content_text_array[w] + ' ');

                        if (content_elem.outerHeight() > content_height) {

                            content_height = content_elem.outerHeight();

                            if (line.length) {
                                var content_to_tspan = line.join(' ').trim();
                                if(content_to_tspan.length){
                                    add.tspan(content_to_tspan).attr({x: padding}).dy(parseInt(content_elem.css('line-height')));
                                }
                                line = [];
                            }
                        }

                        line.push(content_text_array[w]);
                    }

                    if (line.length) {
                        add.tspan(line.join(' ')).attr({x: padding}).dy(parseInt(content_elem.css('line-height')));
                        line = [];
                    }
                })
                .attr({
                    x: padding,
                    y: offset_y
                });

            offset_y += content_elem.outerHeight();


            content.font({
                family: content_elem.css('font-family'),
                size: content_elem.css('font-size'),
                weight: content_elem.css('font-weight'),
                anchor: 'left'
            }).fill({color: content_elem.css('color')});

            content_elem.removeClass('svg-wordwrap-test');

            var call_stage_elem = $(this).find('.node-call-stage');

            if (call_stage_elem.length) {

                var call_stage = node.text(call_stage_elem.text()).attr({
                    x: padding,
                    y: offset_y
                });

                call_stage.font({
                    family: call_stage_elem.css('font-family'),
                    size: call_stage_elem.css('font-size'),
                    anchor: 'left'
                }).fill({color: call_stage_elem.css('color')});

                offset_y += call_stage_elem.outerHeight();

            }

            var groups_elem = $(this).find('.node-groups');

            if (groups_elem.length) {


                var groups_text_array = groups_elem.text().split(' ');

                groups_elem.empty();

                var groups_height = groups_elem.outerHeight();

                var groups = node
                    .text(function (add) {
                        for (var w = 0; w < groups_text_array.length; w++) {

                            groups_elem.append(groups_text_array[w] + ' ');

                            if (groups_elem.outerHeight() > groups_height) {
                                groups_height = groups_elem.outerHeight();
                                if (line.length) {
                                    add.tspan(line.join(' ')).attr({x: padding}).dy(parseInt(groups_elem.css('line-height')));
                                    line = [];
                                }
                            }

                            line.push(groups_text_array[w]);
                        }

                        if (line.length) {
                            add.tspan(line.join(' ')).attr({x: padding}).dy(parseInt(groups_elem.css('line-height')));
                            line = [];
                        }
                    })
                    .attr({
                        x: padding,
                        y: offset_y
                    })
                    .font({
                        family: groups_elem.css('font-family'),
                        size: groups_elem.css('font-size'),
                        anchor: 'left'
                    })
                    .fill({color: groups_elem.css('color')});

                offset_y += groups_elem.outerHeight() + padding;
            }

            $(this).find('.variant, .group-variant').each(function () {
                var variant_elem = $(this);

                node.rect(variant_elem.outerWidth(), variant_elem.outerHeight() - 0.3).attr({
                    x: 0,
                    y: offset_y
                }).attr({fill: variant_elem.css('background-color')});

                node.text(variant_elem.find('.variant-content, .group-variant-content').text())
                    .attr({
                        x: padding * 3,
                        y: offset_y
                    })
                    .font({
                        family: variant_elem.css('font-family'),
                        size: variant_elem.css('font-size'),
                        anchor: 'left'
                    })
                    .fill({color: variant_elem.css('color')});

                offset_y += variant_elem.outerHeight();
            });
        }
    });

    return '<?xml version="1.0" encoding="UTF-8"?>' + elem.html();
};