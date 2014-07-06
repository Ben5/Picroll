(function( $ ) {
    var thumbImg, settings, thumbWidth, thumbHeight, offset, zoomedWidth, zoomedHeight;

    $.fn.zoomify = function(options) {
        thumbImg = this;

        settings = $.extend({
            // defaults
            zoomWindowHeight: 300,
            zoomWindowWidth:  300,
            zoomStyle: 'box'
        }, options);

        var zoomStyleFunctions = {
            box: {
                mouseOver: mouseOver1,
                mouseMove: mouseMove1,
                mouseOut:  mouseOut1
            },
            magnifier: {
                mouseOver: mouseOver2,
                mouseMove: mouseMove2,
                mouseOut:  mouseOut1 // No seperate Mouse Out 2 yet
            }
        };

        thumbWidth  = this.width();
        thumbHeight = this.height();

        thumbImg.load(function() {
            thumbWidth  = $(this).width();
            thumbHeight = $(this).height();
        });

        offset = $(this).offset();

        // Set up mouse event handlers
        this.mouseover(zoomStyleFunctions[settings.zoomStyle].mouseOver);
        this.mousemove(zoomStyleFunctions[settings.zoomStyle].mouseMove);
        this.mouseout( zoomStyleFunctions[settings.zoomStyle].mouseOut);

        return this;
    };

    function mouseOver1(event) {
        var mouseX = event.pageX;
        var mouseY = event.pageY;

        var newDiv = $('<div>', {
            id: 'zoomWindow'
        });

        newDiv
            .css('width',    settings.zoomWindowWidth)
            .css('height',   settings.zoomWindowHeight)
            .css('position', 'absolute')
            .css('left',     mouseX - 150 + 'px')
            .css('top',      mouseY - 150 + 'px')
            .css('border-radius', '150px')
            .css('pointer-events', 'none')
            .css('z-index', 1000)
            .css('overflow', 'hidden');

        var newImg = $('<img>', {
            id: 'zoomImage'
        });

        newImg
            .attr('src', ($(this).attr('src')))
            .css('position', 'absolute');


        newImg.load(function() {
            zoomedWidth  = $(this).width();
            zoomedHeight = $(this).height();
        });

        newDiv.append(newImg);
        
        $('body').append(newDiv);
    }

    function mouseMove1(event) {
        var mouseX = event.pageX;
        var mouseY = event.pageY;

        var x = Math.ceil(mouseX - offset.left);
        var y = Math.ceil(mouseY - offset.top);

        // Update the position of the magnifier
        $('#zoomWindow')
            .css('left',     mouseX - 150 + 'px')
            .css('top',      mouseY - 150 + 'px');
        
        // get the percentage that x and y are through thumbwidth and thumbheight
        var xPercent = x / thumbWidth;
        var yPercent = y / thumbHeight;

        // apply the same perdentage through zoomedwidth and zoomed height
        var xZoomPos = xPercent * zoomedWidth;
        var yZoomPos = yPercent * zoomedHeight;

        // now we have the coordinates for the centre of the zoom window.
        // If the coords are near the edge of the image, clamp the image to being in the window
        var top, left;
        if (xZoomPos <= settings.zoomWindowWidth / 2) {
            left = 0;
        } else if (xZoomPos >= zoomedWidth - (settings.zoomWindowWidth / 2)) {
            left = zoomedWidth - settings.zoomWindowWidth;
        } else {
            left = xZoomPos - settings.zoomWindowWidth / 2;
        }

        if (yZoomPos <= settings.zoomWindowHeight / 2) {
            top = 0;
        } else if (yZoomPos >= zoomedHeight - (settings.zoomWindowHeight / 2)) {
            top = zoomedHeight - settings.zoomWindowHeight;
        } else {
            top = yZoomPos - settings.zoomWindowHeight / 2;
        }

        // set left and top on newImg.
        $('#zoomImage').css({
            left: -left,
            top:  -top
        });
    }

    function mouseOut1() {
        $('#zoomWindow').remove();
    }

    function mouseOver2(event) {
    }

    function mouseMove2(event) {
    }
}(jQuery));
