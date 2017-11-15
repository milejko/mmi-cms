(function() {
    'use strict';

    var video, $output;
    var scale = 0.25;
    var currentFrameId = 1;
    var autoFramesGenerated  = false;
    var initialize = function() {
        $output = $('#output');
        video = $('#video').clone();
        video = $(video).get(0);
        video.addEventListener('loadedmetadata', captureFrames, false);
        video.addEventListener('seeked', timeSeeked, false);
        $('#capture').click(userCapture);
    };

    var userCapture = function(){
        captureFrame(false);
    };

    var captureFrame = function(isAutoProcess){
        var canvas = document.createElement('canvas');
        canvas.width = video.videoWidth * scale;
        canvas.height = video.videoHeight * scale;
        canvas.getContext('2d')
            .drawImage(video, 0, 0, canvas.width, canvas.height);

        var img = document.createElement('img');
        img.src = canvas.toDataURL();
        img.style.height = '100px';
        $output.prepend(img);
        if(isAutoProcess){
            currentFrameId++;
            if(currentFrameId > 10){
                if(autoFramesGenerated) {
                    video.currentTime = 0;
                    autoFramesGenerated = true;
                }
            }
            if(!autoFramesGenerated){
                captureFrames();
            }
        }
    };

    var timeSeeked = function () {
        if(currentFrameId <= 10) {
            captureFrame(true);
        }
    };

    var captureFrames = function(){
        video.currentTime += (video.duration / 10);
    };

    $(initialize);
}());