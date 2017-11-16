"use strict";
var VideoFrameExtractor = function () {
    var extractor = {};
    extractor.video = null;
    extractor.ghostVideo = null;
    extractor.output = null;
    extractor.scale = 0.25;
    extractor.currentFrameId = 1;
    extractor.autoFramesGenerated = false;
    extractor.selectors = {
        input: '',
        video: '',
        btn: '',
        uploadInput:'',
        uploadBtn:'',
        output: '',
        dialog: ''
    };
    extractor.modalFixer = function () {
        setTimeout(function () {
            if ($(extractor.selectors.dialog).length > 0) {
                $(extractor.selectors.dialog).css({top: '25px'});
            }
        }, 100);
    };

    extractor.prependSelected = function () {
        var selectedImg = $('#poster').val();
        if (selectedImg) {
            var img = document.createElement('img');
            img.src = selectedImg;
            img.classList.add('active');
            $(img).on('click', function () {
                $(extractor.selectors.output + ' > img').removeClass('active');
                $(this).addClass('active');
                $(extractor.selectors.input).val(this.src);
            });
            extractor.output.prepend(img);
        }
    };

    extractor.initialize = function (selectors) {
        if (selectors) {
            extractor.selectors = selectors;
            if($(extractor.selectors.video).length > 0) {
                extractor.video = $(extractor.selectors.video).clone();
                extractor.video = $(extractor.video).get(0);
                extractor.modalFixer();
                extractor.output = $(extractor.selectors.output);
                extractor.output.empty();
                extractor.video.addEventListener('loadedmetadata', extractor.captureFrames, false);
                extractor.video.addEventListener('seeked', extractor.timeSeeked, false);
                $(extractor.selectors.btn).off('click');
                $(extractor.selectors.btn).on('click', extractor.userCapture);
                $(extractor.selectors.uploadBtn).off('click');
                $(extractor.selectors.uploadBtn).on('click', extractor.uploadFile);
            }
        } else {
            console.log('selectors was not provided');
        }
    };
    extractor.userCapture = function () {
        extractor.captureFrame(false);
    };
    extractor.uploadFile = function () {
       $(extractor.selectors.uploadInput).click();
    };
    extractor.captureFrame = function (isAutoProcess) {
        if (!isAutoProcess) {
            extractor.video = $(extractor.selectors.video).get(0);
        }
        var canvas = document.createElement('canvas');
        canvas.width = extractor.video.videoWidth * extractor.scale;
        canvas.height = extractor.video.videoHeight * extractor.scale;
        canvas.getContext('2d')
            .drawImage(extractor.video, 0, 0, canvas.width, canvas.height);

        var img = document.createElement('img');
        img.src = canvas.toDataURL();
        $(img).on('click', function () {
            $(extractor.selectors.output + ' > img').removeClass('active');
            $(this).addClass('active');
            $(extractor.selectors.input).val(this.src);
        });
        extractor.output.prepend(img);
        if (isAutoProcess) {
            extractor.currentFrameId++;
            if (extractor.currentFrameId > 10) {
                if (extractor.autoFramesGenerated) {
                    extractor.video.currentTime = 0;
                    extractor.autoFramesGenerated = true;
                }
            }
            if (!extractor.autoFramesGenerated) {
                extractor.captureFrames();
            }
        }
    };

    extractor.timeSeeked = function () {
        if (extractor.currentFrameId <= 10) {
            extractor.captureFrame(true);
        } else {
            extractor.prependSelected();
        }
    };

    extractor.captureFrames = function () {
        extractor.video.currentTime += (extractor.video.duration / 10);
    };
    return extractor;
};