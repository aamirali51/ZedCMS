"use strict";

const ContentProtector = (function () {

  function blockKey(keyCode) {
    window.addEventListener("keydown", function (event) {
      if ((event.ctrlKey || event.metaKey) && event.which === keyCode) {
        event.preventDefault();
      }
    });
    document.onkeypress = function (event) {
      if ((event.ctrlKey || event.metaKey) && event.which === keyCode) {
        return false;
      }
    };
  }

  function enableProtections() {
    // Block common shortcuts
    [65, 67, 88, 86, 83, 85, 80].forEach(blockKey); // A, C, X, V, S, U, P

    // Block Developer Tools
    function isFramed() {
      try {
        return window.self !== window.top;
      } catch (e) {
        return true;
      }
    }

    function destroyPage() {
      if (!isFramed()) {
        document.body?.remove();
        document.head?.remove();
      }
    }

    const devToolKeys = [
      "F12", "Ctrl+Shift+I", "Ctrl+Shift+J", "Ctrl+Shift+C",
      "Ctrl+Shift+K", "Ctrl+Shift+E", "Shift+F7", "Shift+F9",
      "Shift+F5", "Shift+F12", "Cmd+Opt+I", "Cmd+Opt+J",
      "Cmd+Shift+C", "Cmd+Opt+K", "Cmd+Opt+E", "Cmd+Opt+Z"
    ];

    window.addEventListener("keydown", function (e) {
      if (e.key && devToolKeys.includes(e.key)) {
        e.preventDefault();
      }
    });

    if (typeof devtoolsDetector !== "undefined") {
      devtoolsDetector.addListener(function (open) {
        if (open) destroyPage();
      });
      devtoolsDetector.launch();
    }

    // Block Safari Reader Mode
    if (/safari/i.test(navigator.userAgent) && !/chrome/i.test(navigator.userAgent)) {
      window.addEventListener("keydown", function (e) {
        if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.keyCode === 82) {
          e.preventDefault();
        }
      });
    }

    // Block Right Click
    document.oncontextmenu = function (e) {
      if ((e.target || e.srcElement).nodeName !== "A") return false;
    };
    document.body.oncontextmenu = () => false;
    document.onmousedown = function (e) {
      if (e.button === 2) return false;
    };

    setInterval(() => {
      if (document.oncontextmenu === null) {
        document.body?.remove();
        document.head?.remove();
      }
    }, 500);

    // Block Text Selection
    if (typeof document.body.onselectstart !== "undefined") {
      document.body.onselectstart = () => false;
    } else if (typeof document.body.style.MozUserSelect !== "undefined") {
      document.body.style.MozUserSelect = "none";
    } else if (typeof document.body.style.webkitUserSelect !== "undefined") {
      document.body.style.webkitUserSelect = "none";
    } else {
      document.body.onmousedown = () => false;
    }

    if (!(/safari/i.test(navigator.userAgent) && !/chrome/i.test(navigator.userAgent))) {
      document.documentElement.style.webkitTouchCallout = "none";
      document.documentElement.style.webkitUserSelect = "none";

      const css = document.createElement("style");
      css.type = "text/css";
      css.innerText = `
        *:not(input):not(textarea):not([contenteditable=""]):not([contenteditable="true"]) {
          -webkit-user-select: none !important;
          -moz-user-select: none !important;
          -ms-user-select: none !important;
          user-select: none !important;
        }
      `;
      document.head.appendChild(css);
    }

    // Block Image Drag
    document.ondragstart = () => false;
  }

  return {
    enable: enableProtections
  };
})();

document.addEventListener("DOMContentLoaded", function () {
  ContentProtector.enable();
});