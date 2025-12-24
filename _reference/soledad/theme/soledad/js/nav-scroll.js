jQuery(document).ready(function($) {
    var $nav = $('.penci-post-sticky-nav');
    var $post = $('article.post');
    var $stickyWrapper = $('.sticky-wrapper #navigation');
    var $customStickyArea = $('.penci_builder_sticky_header_desktop');
  
    var lastHeights = { wrapper: 0, custom: 0, adminBar: false };
  
    function getCombinedOffset() {
      var wrapperHeight = $stickyWrapper.length ? $stickyWrapper.outerHeight(true) : 0;
      var customHeight = $customStickyArea.length ? $customStickyArea.outerHeight(true) : 0;
      var adminBarOffset = $('body').hasClass('admin-bar') ? 32 : 0;
  
      return {
        total: wrapperHeight + customHeight + adminBarOffset,
        wrapper: wrapperHeight,
        custom: customHeight,
        adminBar: adminBarOffset > 0
      };
    }
  
    function updateStickyTop() {
      var heights = getCombinedOffset();
  
      // Only update if height changed
      if (
        heights.wrapper !== lastHeights.wrapper ||
        heights.custom !== lastHeights.custom ||
        heights.adminBar !== lastHeights.adminBar
      ) {
        $nav.css('top', heights.total + 'px');
        lastHeights = {
          wrapper: heights.wrapper,
          custom: heights.custom,
          adminBar: heights.adminBar
        };
      }
    }
  
    function checkStickyNav() {
      if (!$nav.length || !$post.length) return;
  
      var postTop = $post.offset().top;
      var scrollTop = $(window).scrollTop();
      var navTop = parseInt($nav.css('top'), 10) || 0;
  
      if (scrollTop + navTop >= postTop) {
        $nav.addClass('active');
      } else {
        $nav.removeClass('active');
      }
    }
  
    function onScrollOrResize() {
      updateStickyTop();
      checkStickyNav();
    }
  
    // Periodic height check (every 300ms)
    setInterval(updateStickyTop, 300);
  
    // Bind events
    $(window).on('load scroll resize', onScrollOrResize);
  });
  