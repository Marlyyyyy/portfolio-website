
/*
*  Author: Márton Széles
*  Copyright 2014
*/

// Preloads an array of images. Returns an object with a "done" method that can be used to execute a callback function
function preloadImages(array, el){

    var newImages = [], loadedImages = 0, arrLength = array.length, loadingContainer, progressP;
    var container = (typeof el === "undefined" ? document.body : el);

    var postAction = function(){};

    var arr = (typeof array != "object") ? [array] : array;

    // Executes call-back function after preloading all images
    function imageLoadPost(){

        loadedImages++;
        progressP.innerHTML = Math.round(100*(loadedImages/arrLength)) + "%";
        if (loadedImages == arrLength){
            onFinish();
            postAction(newImages);
        }
    }

    // Creates loading screen
    function onCreate(){

        loadingContainer = document.createElement("div");
        loadingContainer.id  = "image-loading-container";

        progressP = document.createElement("p");
        progressP.id = "image-progress-p";
        loadingContainer.appendChild(progressP);

        container.appendChild(loadingContainer);

        $(loadingContainer).fadeIn(150);
    }

    // Removes loading screen
    function onFinish(){

        $(loadingContainer).fadeOut(150, function(){
            container.removeChild(loadingContainer);
        });
    }

    onCreate();

    for (var i=0; i<arrLength; i++){
        newImages[i] = new Image();
        newImages[i].src = arr[i];
        newImages[i].onload = function(){
            imageLoadPost();
        };
        newImages[i].onerror = function(){
            imageLoadPost();
        }
    }

    // Return blank object with done() method
    return {
        done:function(f){
            postAction= f || postAction;
        }
    }
}

// Module responsible for smoothly displaying any number of background images
var ImageRotatorModule = (function(){

    var settings, imageArr, imageArrLength, background1, background2, backgroundContainer, mainContainer, intervalID, cPanel;
    var position = 0;
    var clock = true;

    function init(images, options){

        imageArr        = images;
        imageArrLength  = images.length;
        mainContainer   = $(".main_container");

        settings = $.extend({
            "fade_speed"        : 500,
            "slide_duration"    : 5000,
            "controlPanel"      : false,
            "hasExit"           : false
        }, options || {});

        registerEventListeners();

        return this;
    }

    function createBackground(){

        backgroundContainer    = document.createElement('div');
        backgroundContainer.className  = "background_container";

        background1            = document.createElement('div');
        background1.className  = "background_pic";
        background1.id         = "background_pic_01";

        background2            = document.createElement('div');
        background2.className  = "background_pic";
        background2.id         = "background_pic_02";

        backgroundContainer.appendChild(background1);
        backgroundContainer.appendChild(background2);

        document.body.appendChild(backgroundContainer);

        if (settings.controlPanel){
            createCPanel();
        }

        return this;
    }

    function removeBackground(){

        document.body.removeChild(backgroundContainer);
        removeCPanel();

        return this;
    }

    // Control Panel
    function createCPanel(){

        cPanel = document.createElement('div');
        cPanel.className = "c_container";

        var  c_left       = document.createElement('div');
        c_left.className  = "c_move c_left";

        var  c_right      = document.createElement('div');
        c_right.className = "c_move c_right";

        cPanel.appendChild(c_left);
        cPanel.appendChild(c_right);

        var  previous      = document.createElement('div');
        previous.className = "c_arrow image_rotator";
        previous.setAttribute("name","previous");

        var  next      = document.createElement('div');
        next.className = "c_arrow image_rotator";
        next.setAttribute("name","next");

        c_left.appendChild(previous);
        c_right.appendChild(next);

        if (settings.hasExit){
            var c_exit = document.createElement('div');
            c_exit.className = "c_exit image_rotator";
            c_exit.setAttribute("name","exit");
            cPanel.appendChild(c_exit);
        }

        document.body.appendChild(cPanel);

        return this;
    }

    function removeCPanel(){

        if ( typeof cPanel !== "undefined"){
            document.body.removeChild(cPanel);
        }

        while (cPanel.firstChild){
            cPanel.removeChild(cPanel.firstChild);
        }

        return this;
    }

    function registerEventListeners(){

        $(document).on("click", ".image_rotator", function(){

            var task = $(this).attr("name");

            switch(task){
                case "next":
                    stepForward();
                    break;
                case "previous":
                    stepBackward();
                    break;
                case "pause":
                    pause();
                    break;
                case "resume":
                    start();
                    break;
                case "jump":
                    focus();
                    jumpTo($(this).attr("id"));
                    break;
                case "exit":
                    blur();
                    break;
            }
        });
    }

    // Change between backgrounds
    function move(){

        if (clock){
            // Show top layer
            changeBackground(background1, "url("+imageArr[position]+")", function(){$(background1).finish().fadeIn(settings.fade_speed)});
        }else{
            // Show bottom layer
            changeBackground(background2, "url("+imageArr[position]+")", function(){$(background1).finish().fadeOut(settings.fade_speed)});
        }

        function changeBackground(obj, background, callback){
            $(obj).css({"background-image":background});
            callback();
        }

        clock = !clock;
    }

    function start(){

        move();
        intervalID =   window.setInterval(function(){
            position = (position + 1) % imageArrLength;
            move();
        }, settings.slide_duration);

        return this;
    }

    function pause(){

        clearInterval(intervalID);

        return this;
    }

    function stepForward(){

        pause();
        position++;
        position = position % imageArrLength;
        move();

        return this;
    }

    function stepBackward(){

        pause();
        position--;
        position = position % imageArrLength;
        if (position < 0) position += imageArrLength;
        move();

        return this;
    }

    function jumpTo(pos){

        pause();
        position = pos;
        move();

        return this;
    }

    function focus(){

        createBackground();
        $(mainContainer).fadeOut(100);

        return this;
    }

    function blur(){

        removeBackground();
        $(mainContainer).fadeIn(100);

        return this;
    }

    function lowerOpacity(){

        $(backgroundContainer).addClass("blur");

        return this;
    }

    return {
        init:init,
        create:createBackground,
        shade:lowerOpacity,
        focus:focus,
        blur:blur,
        start:start,
        pause:pause,
        stepForward:stepForward,
        stepBackward:stepBackward,
        jumpTo:jumpTo
    }
})();

// Module responsible for displaying interactive projects browser
var ProjectBrowserModule = (function(){

    var container, settings;

    function init( options){

        settings = $.extend({
            "open_speed"  : 100
        }, options || {});

        container = document.getElementById("project_container");

        registerEventListeners();
    }

    function registerEventListeners(){

        $(".project_header").click(interact);
    }

    function interact(){
        var header = this;
        var state = header.dataset.state;
        var entry = $(header).closest(".project_entry");
        if (state === "closed"){
            $(entry).animateAuto("height", settings.open_speed, function(){
                header.dataset.state = "open";
                $(entry).removeClass("project_entry_closed");
                $(header).addClass("project_header_open");
            });

        }else{
            $(entry).animate({"height":"50px"}, settings.open_speed, function(){
                header.dataset.state = "closed";
                $(entry).addClass("project_entry_closed");
                $(header).removeClass("project_header_open");
            });
        }

    }

    return{
        init:init
    }
})();

// Animates height:auto
$.fn.animateAuto = function(prop, speed, callback){

    var elem, height;

    // Iterate through each element the selector returned
    return this.each(function(i, el){
        el = $(el);
        elem = el.clone().css({"height":"auto"}).appendTo("body");
        height = elem.css("height");
        elem.remove();

        if(prop === "height") el.animate({"height":height}, speed, callback);
    });
};

