/* =========================================================
   WEBSITE POPUP (template/website-popup.php)
   Opens shortly after load. Once closed (or its link clicked) it stays
   hidden for 24 hours. Uploading a new image changes data-popup-key,
   so the new popup shows straight away.
========================================================= */

document.addEventListener("DOMContentLoaded", function () {

    var popup = document.getElementById("tt-popup");
    if (!popup) return;

    var storageKey = "tt-popup-closed";
    var hideFor = 24 * 60 * 60 * 1000;
    var popupKey = popup.getAttribute("data-popup-key");
    var lastFocus = null;

    // Stored as { key, time } - storage can throw (private mode, blocked site data): just show the popup then
    try {
        var closed = JSON.parse(localStorage.getItem(storageKey));
        if (closed && closed.key === popupKey && Date.now() - closed.time < hideFor) return;
    } catch (e) {}

    function remember() {
        try {
            localStorage.setItem(storageKey, JSON.stringify({ key: popupKey, time: Date.now() }));
        } catch (e) {}
    }

    function open() {
        lastFocus = document.activeElement;
        popup.classList.add("is-open");
        popup.setAttribute("aria-hidden", "false");
        document.body.classList.add("tt-popup-open");
        popup.querySelector(".tt-popup-close").focus();
    }

    function close() {
        remember();
        popup.classList.remove("is-open");
        popup.setAttribute("aria-hidden", "true");
        document.body.classList.remove("tt-popup-open");
        if (lastFocus) lastFocus.focus();
    }

    popup.querySelectorAll("[data-popup-close]").forEach(function (el) {
        el.addEventListener("click", close);
    });

    // The link opens in a new tab, so the popup is still open here: count it as closed
    var link = popup.querySelector(".tt-popup-link");
    if (link) link.addEventListener("click", remember);

    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape" && popup.classList.contains("is-open")) close();
    });

    // Load the image before showing, so the popup doesn't open empty
    var image = popup.querySelector(".tt-popup-image");
    image.loading = "eager";

    setTimeout(function () {
        if (image.complete) {
            open();
        } else {
            image.addEventListener("load", open, { once: true });
        }
    }, 1500);

});
