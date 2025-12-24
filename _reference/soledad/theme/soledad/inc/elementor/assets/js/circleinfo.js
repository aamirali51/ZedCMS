function circleJs(id, circleMoving, movingTime, mouseEvent) {
    var circles = document.querySelectorAll('#' + id + ' .penci-cinfosub-circle');
    var circleContents = document.querySelectorAll('#' + id + '  .penci-cinfoitem');
    var parent = document.querySelector('#' + id + ' .penci-cinfoinner ');

    var i = 2;
    var prevNowPlaying = null;

    if (movingTime <= 0) {
        movingTime = '100000000000';
    }

    if (circleMoving === false) {
        movingTime = '100000000000';
    }

    function myTimer() {
        //console.log('setInterval');
        var dataTab = jQuery(' #' + id + ' .penci-cinfosub-circle.active').data('circle-index');
        var totalSubCircle = jQuery('#' + id + ' .penci-cinfosub-circle').length; // here

        if (dataTab > totalSubCircle || i > totalSubCircle) {
            dataTab = 1;
            i = 1;
        }

        jQuery('#' + id + '  .penci-cinfosub-circle').removeClass('active');
        jQuery('#' + id + ' .penci-cinfosub-circle.active').removeClass('active', this);
        jQuery('#' + id + '  ' + '[data-circle-index=\'' + i + '\']').addClass('active');
        jQuery('#' + id + '  .penci-cinfoitem').removeClass('active');
        jQuery('#' + id + '  .icci' + i).addClass('active');
        i++;
        var activeIcon = '#' + id + ' .penci-cinfosub-circle i,' + '#' + id + ' .penci-cinfosub-circle svg';
        jQuery(activeIcon).css({
            'transform': 'rotate(' + (360 - (i - 2) * 36) + 'deg)',
            'transition': '2s'
        });
        jQuery('#' + id + ' .penci-cinfoinner').css({
            'transform': 'rotate(' + ((i - 2) * 36) + 'deg) ',
            'transition': '1s'
        });

    }
    if (circleMoving === true) {
        var prevNowPlaying = setInterval(myTimer, movingTime);
    }
    if (circleMoving === false) {
        clearInterval(prevNowPlaying);
    }


    // active class toggle methods
    var removeClasses = function removeClasses(nodes, value) {
        var nodes = nodes;
        var value = value;
        if (nodes) return nodes.forEach(function (node) {
            return node.classList.contains(value) && node.classList.remove(value);
        });
        else return false;
    };
    var addClass = function addClass(nodes, index, value) {
        var nodes = nodes;
        var index = index;
        var value = value;
        return nodes ? nodes[index].classList.add(value) : 0;
    };
    var App = {
        initServicesCircle: function initServicesCircle() {
            // info circle
            if (parent) {
                var spreadCircles = function spreadCircles() {
                    // spread the sub-circles around the circle
                    var parent = document.querySelector('#' + id + ' .penci-cinfoinner ').getBoundingClientRect();
                    var centerX = 0;
                    var centerY = 0;
                    Array.from(circles).reverse().forEach(function (circle, index) {
                        var circle = circle;
                        var index = index;
                        var angle = index * (360 / circles.length);
                        var x = centerX + (parent.width / 2) * Math.cos((angle * Math.PI) / 180);
                        var y = centerY + (parent.height / 2) * Math.sin((angle * Math.PI) / 180);
                        circle.style.transform = 'translate3d(' + parseFloat(x).toFixed(5) + 'px,' + parseFloat(y).toFixed(5) + 'px,0)';
                    });
                };

                spreadCircles();

                var resizeTimer = void 0;
                window.addEventListener('resize', function () {
                    clearTimeout(resizeTimer);
                    resizeTimer = setTimeout(function () {
                        spreadCircles();
                    }, 50);
                });
                circles.forEach(function (circle, index) {
                    var circle = circle;
                    var index = index;
                    var circlesToggleFnc = function circlesToggleFnc() {
                        this.index = circle.dataset.circleIndex;
                        if (!circle.classList.contains('active')) {
                            removeClasses(circles, 'active');
                            removeClasses(circleContents, 'active');
                            addClass(circles, index, 'active');
                            addClass(circleContents, index, 'active');
                        }
                    };
                    if (mouseEvent === 'mouseover') {
                        circle.addEventListener('mouseover', circlesToggleFnc, true);
                    } else if (mouseEvent === 'click') {
                        circle.addEventListener('click', circlesToggleFnc, true);
                    } else {
                        circle.addEventListener('mouseover', circlesToggleFnc, true);
                    }
                });
            }
        }
    };
    App.initServicesCircle();
}

function epObserveTarget(target, callback) {
    var options =
      arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
    // Set the rootMargin to trigger when the target is 10% past the viewport
    options.rootMargin = options.rootMargin || "10% 0px 0px 0px";
    var observer = new IntersectionObserver(function (entries, observer) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          callback(entry);
  
          if (!options.loop) observer.unobserve(entry.target); // Unobserve after the first intersection
        }
      });
    }, options);
    observer.observe(target);
  }
  
(function ($, elementor) {
    'use strict';
    var widgetCircleInfo = function ($scope, $) {
        var $circleInfo = $scope.find('.penci-cinfo');

        if (!$circleInfo.length) {
            return;
        }

        epObserveTarget($circleInfo[0], function () {
            const $this = jQuery($circleInfo[0]);
            var $settings = $this.data('settings');

            circleJs($settings.id, $settings.circleMoving, $settings.movingTime, $settings.mouseEvent);

        }, {
            root: null,
            rootMargin: '0px',
            threshold: 0.8
        });

    };

    jQuery(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/penci-circle-info.default', widgetCircleInfo);
    });
}(jQuery, window.elementorFrontend));