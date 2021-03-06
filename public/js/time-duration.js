

(function ($) {

    // Constructor for durationpicker 'class'
    var durationPicker = function (element, options) {
        options.hours['name'] = $(element).data("name-hours");
        options.minutes['name'] = $(element).data("name-minutes");
        options.hours['value'] = $(element).data("value-hours");
        options.minutes['value'] = $(element).data("value-minutes");
        options.hours['step'] = $(element).data("step-hours");
        options.minutes['step'] = $(element).data("step-minutes");
        this.settings = options;
        this.stages = get_stages(this.settings);
        this.template = generate_template(this.settings, this.stages);
        this.jqitem = $(this.template);
        this.jqchildren = this.jqitem.children();
        this.element = $(element);
        this.setup();
        this.resize();
        this.jqchildren.find(".durationpicker-duration").trigger('change');
        this.jqchildren.find(".durationpicker-duration").trigger('oninput');
        var _self = this;
    };

    durationPicker.prototype = {
        constructor: durationPicker,
        setup: function () {
            this.element.before(this.jqitem);
            this.element.hide();
            this.jqchildren.find(".durationpicker-duration").on('change', {ths: this}, function (ev) {
                var element = ev.data.ths.element;
                var value = "";
                $(this).parent().parent().find('input').each(function () {
                    var input = $(this);
                    var val = 0;
                    if (input.val() != null && input.val() != ""){
                        val = input.val();
                    }
                    value += val + input.next().text() + ",";
                });
                value = value.slice(0, -1);
                element.val(value);
            });
            $(".durationpicker-duration").trigger('change');
            window.addEventListener('resize', this.resize.bind(this));

        },
        resize: function() {
            if (!this.settings.responsive) {
                return
            }
            var padding = parseInt(this.jqitem.css('padding-left').split('px')[0]) + parseInt(this.jqitem.css('padding-right').split('px')[0]);
            var minwidth = padding;
            var minheight = padding;
            this.jqchildren.each(function () {
                var ths = $(this);
                minwidth = minwidth + ths.outerWidth();
                minheight = minheight + ths.outerHeight();
            });
            if (this.jqitem.parent().width() < minwidth) {
                this.jqchildren.each(function () {
                    var ths = $(this);
                    ths.css('display', 'block');
                });
                this.jqitem.css('height', minheight)
            }
            else {
                this.jqchildren.each(function () {
                    var ths = $(this);
                    ths.css('display', 'inline-block');
                });
            }
        },
        getitem: function () {
            return this.jqitem;
        },
        setvalues: function (values) {
            set_values(values, this)
            $(".durationpicker-duration").trigger();
        },
        disable: function () {
            this.jqchildren.children("input").each(function (index, item) {
                item.readOnly = true;
            });
        },
        enable: function () {
            this.jqchildren.children("input").each(function (index, item) {
                item.readOnly = false;
            });
        }
    };

    $.fn.durationPicker = function(options){
        if (options == undefined) {
            var settings = $.extend(true, {}, $.fn.durationPicker.defaults, options);
        }
        else {
            var settings = $.extend(true, {}, {classname: 'form-control', responsive: true, type:'number'}, options);
        }

        // return this.each(function () {
        return new durationPicker(this, settings);
        // })
    };

    function set_values(values, self){
        for (var value in Object.keys(values)){
            if (self.stages.indexOf(Object.keys(values)[value]) != -1){
                self.jqitem.find("#duration-" + (Object.keys(values)[value])).val(values[(Object.keys(values)[value])]);
            }
        }
    }

    function get_stages(settings){
        var stages = [];
        for (var key in Object.keys(settings)){
            if (['classname', 'responsive', 'type'].indexOf(Object.keys(settings)[key]) == -1) {
                stages.push(Object.keys(settings)[key]);
            }
        }
        return stages
    }

    function generate_template (settings, stages) {
        var html = '<div class="durationpicker-container ' + settings.classname + '">';
        var type = settings.type;
        for (var item in stages){
            var value = settings[stages[item]]['value'];
            html += '<div class="durationpicker-innercontainer"><input name="' + settings[stages[item]]['name'] +  '" value="' + value + '" min="' + settings[stages[item]]['min'] + '" max="' + settings[stages[item]]['max'] + '" step="' + settings[stages[item]]['step'] + '" placeholder="0" type="' + type + '" id="duration-' + stages[item] + '" class="durationpicker-duration" ><span class="durationpicker-label">' + settings[stages[item]]['label'] + '</span></div>';
        }
        html += '</div>';

        return html
    }

    $.fn.durationPicker.defaults = {
        hours: {
        	label: "ساعات",
        	min: 0,
            max: 23,
            name: "",
            value: 0,
            step: 1,
        },
        minutes: {
        	label: "دقائق",
        	min: 0,
            max: 59,
            value: 0,
            step: 1,
        },
        classname: 'form-control',
        type: 'number',
        responsive: true
    };

    $.fn.durationPicker.Constructor = durationPicker;

})(jQuery);

$(document).ready(function(){
    $('input[type="number"]').on('keyup',function(){
        v = parseInt($(this).val());
        min = parseInt($(this).attr('min'));
        max = parseInt($(this).attr('max'));
        step = parseInt($(this).attr('step'));

        /*if (v < min){
            $(this).val(min);
        } else */if (v > max){
            $(this).val(max);
        }
    })
})