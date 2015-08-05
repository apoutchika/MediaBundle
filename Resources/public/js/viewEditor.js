(function($, ApoutchikaMedia, Backbone, Mustache, _, confirm){
    'use strict';

    setInterval(function(){
        $('.apoutchika-media-action-valid-crop').css('border-color', '#f7f7f7');
        setTimeout(function(){
            $('.apoutchika-media-action-valid-crop').css('border-color', '#707070');
        }, 800);
    }, 2000);


    ApoutchikaMedia.ImageEditor = Backbone.View.extend({
        model: ApoutchikaMedia.Media,
        editorEl: null,
        template: $('#apoutchika-media-view-image-editor').html(),
        jcropApi: null,
        jcropInfos: null,
        initialize: function(options){
            this.editorEl = options.editorEl;
        },
        render: function(){
            this.$el.html(
                Mustache.render(
                    this.template, {
                        media: this.model.toJSON()
                    }
                )
            );

            var that = this;

            if (this.model.get('is_image')) {

                if (this.jcropApi !== null) {
                    this.jcropApi.destroy();
                }

                _.map(['focus', 'crop'], function(i){
                    that.$el.find('.apoutchika-media-action-' + i).hover(function(){
                        that.$el.find('.info-' + i).fadeIn('fast');
                    }, function(){
                        that.$el.find('.info-' + i).fadeOut('fast');
                    });
                });

                $('.apoutchika-media-focus-pointer').draggable({
                    containment: '.apoutchika-media-focus',
                    stop: function(){

                        // setFocus
                        var w = $('.apoutchika-media-focus').outerWidth();
                        var h = $('.apoutchika-media-focus').outerHeight();
                        var pos = $('.apoutchika-media-focus-pointer').position();

                        var left = (pos.left * 100) / w;
                        var top = (pos.top * 100) / h;

                        that.model.set('focus_left', left);
                        that.model.set('focus_top', top);

                        var filter = that.model.get('filter');
                        that.model.save(
                            {focusLeft: left, focusTop: top, filter: filter},
                            {success: function(media){
                                that.updateImages(media, true);
                            }});
                    }
                });
            }
        },
        events: {
            'click .apoutchika-media-action-rotate-left': 'rotateLeft',
            'click .apoutchika-media-action-rotate-right': 'rotateRight',
            'click .apoutchika-media-action-mirror-x': 'mirrorX',
            'click .apoutchika-media-action-mirror-y': 'mirrorY',
            'click .apoutchika-media-action-crop': 'crop',
            'click .apoutchika-media-action-focus': 'focus',
            'dblclick .jcrop-tracker': 'cropAction',
            'click .apoutchika-media-action-valid-crop': 'cropAction'
        },
        updateImages: function(media, isFocus) {
            var date = new Date();
            var urls = [];
            _.map(media.get('urls'), function (val, context) {
                val = val.split('?');
                urls[context] = val[0] + '?' + date.getTime();
            });

            media.set('urls', urls);
            media.set('rotate', null);
            media.set('mirror', null);
            media.set('x', null);
            media.set('y', null);
            media.set('h', null);
            media.set('w', null);

            var focus = ApoutchikaMedia.imageEditor.$el.find('.apoutchika-media-focus-pointer');
            focus.css('left', media.get('focus_left') + '%');
            focus.css('top', media.get('focus_top') + '%');

            if (isFocus !== true) {
                ApoutchikaMedia.imageEditor.render();
            }
        },
        rotateLeft: function(e){
            e.preventDefault();
            this.model.save(
                {rotate: -90, filter: this.model.get('filter')},
                {success: this.updateImages});
        },
        rotateRight: function(e){
            e.preventDefault();
            this.model.save({rotate: 90, filter: this.model.get('filter')}, {
                success: this.updateImages
            });
        },
        mirrorX: function(e){
            e.preventDefault();
            this.model.save({mirror: 'x', filter: this.model.get('filter')}, {
                success: this.updateImages
            });
        },
        mirrorY: function(e){
            e.preventDefault();
            this.model.save({mirror: 'y', filter: this.model.get('filter')}, {
                success: this.updateImages
            });
        },
        crop: function(e) {
            e.preventDefault();

            var that = ApoutchikaMedia.imageEditor;

            if (that.$el.find('.apoutchika-media-action-crop').hasClass('active')) {
                return;
            }

            that.$el.find('.apoutchika-media-action-valid-crop').show();

            that.$el.find('.apoutchika-media-action-crop').addClass('active');
            that.$el.find('.apoutchika-media-action-focus').removeClass('active');

            that.$el.find('.apoutchika-media-focus-pointer').hide();

            that.jcropApi = null;
            that.jcropInfos = null;
            var arroundPercent = 10;

            var arroundLeft = (arroundPercent * that.model.get('width')) / 100;
            var arroundTop = (arroundPercent * that.model.get('height')) / 100;

            var arroundWidth = that.model.get('width') - arroundLeft;
            var arroundHeight = that.model.get('height') - arroundTop;

            that.$el.find('.apoutchika-media-image').Jcrop({ // eslint-disable-line
                onChange: that.setJcropCoords,
                onSelect: that.setJcropCoords,
                trueSize: [that.model.get('width'), that.model.get('height')],
                setSelect: [arroundLeft, arroundTop, arroundWidth, arroundHeight]
            }, function(){
                that.jcropApi = this;
            });
        },
        setJcropCoords: function (c) {
            ApoutchikaMedia.imageEditor.jcropInfos = c;
        },
        cropAction: function (e) {
            e.preventDefault();

            if (confirm($('#trans_js_confirmCrop').val())){ 
                this.model.save({
                    filter: this.model.get('filter'),
                    x: this.jcropInfos.x,
                    y: this.jcropInfos.y,
                    w: this.jcropInfos.w,
                    h: this.jcropInfos.h
                }, {
                    success: this.updateImages
                });
            }
        },
        focus: function(e) {
            e.preventDefault();

            if (this.$el.find('.apoutchika-media-action-focus').hasClass('active')) {
                return;
            }

            this.$el.find('.apoutchika-media-action-valid-crop').hide();

            this.$el.find('.apoutchika-media-action-focus').addClass('active');
            this.$el.find('.apoutchika-media-action-crop').removeClass('active');
            this.$el.find('.apoutchika-media-focus-pointer').show();

            if (this.jcropApi !== undefined) {
                this.jcropApi.destroy();
            }
        }
    });

    ApoutchikaMedia.Editor = Backbone.View.extend({
        model: ApoutchikaMedia.Media,
        template: $('#apoutchika-media-view-editor').html(),
        initialize: function(){
        },
        events: {
            'click .apoutchika-media-save': 'saveMedia',
            'click .apoutchika-media-cancel': 'closeAdd',
            'click .close': 'goBack',
            'click .modal-bg': 'closeAdd'
        },
        saveMedia: function(e){
            e.preventDefault();

            this.model.set('name', this.$el.find('#apoutchika-media-editor-name').val());
            if (this.model.get('is_image')) {
                this.model.set('alt', this.$el.find('#apoutchika-media-editor-alt').val());
            }
            this.model.set('description', this.$el.find('#apoutchika-media-editor-description').val());

            this.model.save({filter: this.model.get('filter')}, {
                success: function(){
                    window.history.back();
                }
            });
        },
        render: function(){
            this.$el.html(
                Mustache.render(
                    this.template, {
                        media: this.model.toJSON()
                    }
                )
            );

            ApoutchikaMedia.imageEditor = new ApoutchikaMedia.ImageEditor({
                model: this.model,
                el: this.$el.find('.right'),
                editorEl: this.$el
            });

            ApoutchikaMedia.imageEditor.render();

        },
        goBack: function(e){
            e.preventDefault();
            window.history.back();
        },
        closeAdd: function(e){
            e.preventDefault();
            window.history.back();
        }
    });
})(window.jQuery, window.ApoutchikaMedia, window.Backbone, window.Mustache, window._, window.confirm);
