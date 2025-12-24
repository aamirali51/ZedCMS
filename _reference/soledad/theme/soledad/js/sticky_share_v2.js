function initStickyShareBox() {
  const posts = document.querySelectorAll('.post');
  const isRTL = document.documentElement.dir === 'rtl'; // detect RTL

  posts.forEach(post => {
    const shareBox = post.querySelector('.tags-share-box');
    if (!shareBox) return;

    const shareBoxHeight = shareBox.offsetHeight;

    function getStickyOffset() {
      const header = document.querySelector('.penci_builder_sticky_header_desktop');
      const headern = document.querySelector('.sticky-wrapper');
      const poststicky = document.querySelector('.penci-post-sticky-nav');
      const headerHeight = header ? header.offsetHeight : 0;
      const poststickyHeight = poststicky ? poststicky.offsetHeight : 0;
      const headernHeight = headern ? headern.offsetHeight : 0;
      return headerHeight + poststickyHeight + headernHeight + 60;
    }

    function updatePosition() {
      const postRect = post.getBoundingClientRect();
      const postTop = postRect.top + window.scrollY;
      const postBottom = postTop + post.offsetHeight;
      const scrollY = window.scrollY;

      const offset = getStickyOffset();

      // Calculate fixed alignment
      const shareRect = shareBox.getBoundingClientRect();
      const sidePos = isRTL
        ? window.innerWidth - (shareRect.left + shareRect.width)
        : shareRect.left;

      if (scrollY + offset > postTop && scrollY + shareBoxHeight + offset < postBottom) {
        shareBox.style.position = 'fixed';
        shareBox.style.top = offset + 'px';

        if (isRTL) {
          shareBox.style.right = sidePos + 'px';
          shareBox.style.left = '';
        } else {
          shareBox.style.left = sidePos + 'px';
          shareBox.style.right = '';
        }
      } else if (scrollY + shareBoxHeight + offset >= postBottom) {
        shareBox.style.position = 'absolute';
        shareBox.style.top = (post.offsetHeight - shareBoxHeight) + 'px';
        shareBox.style.left = '';
        shareBox.style.right = '';
      } else {
        shareBox.style.position = 'absolute';
        shareBox.style.top = '0px';
        shareBox.style.left = '';
        shareBox.style.right = '';
      }
    }

    window.addEventListener('scroll', updatePosition);
    window.addEventListener('resize', updatePosition);
    updatePosition();
  });
}

document.addEventListener('DOMContentLoaded', initStickyShareBox);