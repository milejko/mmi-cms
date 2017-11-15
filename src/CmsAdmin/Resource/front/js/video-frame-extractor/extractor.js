(function() {
    "use strict";
    var VideoFrameExtractor = function (){
        var extractor = {};
        extractor.video = null;
        extractor.output = null;
        extractor.scale = 0.25;
        extractor.currentFrameId = 1;
        extractor.autoFramesGenerated  = false;

        extractor.initialize  = function() {
            extractor.output = $('#output');
            extractor.video = $('#video').clone();
            extractor.video = $(video).get(0);
            extractor.video.addEventListener('loadedmetadata', extractor.captureFrames, false);
            extractor.video.addEventListener('seeked', extractor.timeSeeked, false);
            $('#capture').click(extractor.userCapture);
        };

        extractor.userCapture = function(){
            extractor.captureFrame(false);
        };
        extractor.captureFrame = function(isAutoProcess){
            var canvas = document.createElement('canvas');
            canvas.width = extractor.video.videoWidth * extractor.scale;
            canvas.height = extractor.video.videoHeight * extractor.scale;
            canvas.getContext('2d')
                .drawImage(extractor.video, 0, 0, canvas.width, canvas.height);

            var img = document.createElement('img');
            img.src = canvas.toDataURL();
            img.style.height = '100px';
            extractor.output.prepend(img);
            if(isAutoProcess){
                extractor.currentFrameId++;
                if(extractor.currentFrameId > 10){
                    if(extractor.autoFramesGenerated) {
                        extractor.video.currentTime = 0;
                        extractor.autoFramesGenerated = true;
                    }
                }
                if(!extractor.autoFramesGenerated){
                    extractor.captureFrames();
                }
            }
        };

        extractor.timeSeeked = function () {
            if(extractor.currentFrameId <= 10) {
                extractor.captureFrame(true);
            }
        };

        extractor.captureFrames = function(){
            extractor.video.currentTime += (extractor.video.duration / 10);
        };
        return extractor;
    };
    $(document).ready(function(){
        new VideoFrameExtractor.initialize();
    })
}());