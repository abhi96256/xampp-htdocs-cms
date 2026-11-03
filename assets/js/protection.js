// Basic Right-Click and Image Protection (No Blackout)
(function() {
    // 1. Disable Right Click on entire website
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
    });

    // 2. Override Clipboard (Copies URL instead of content)
    document.addEventListener('copy', function(e) {
        e.clipboardData.setData('text/plain', window.location.href);
        e.preventDefault();
        alert("Content protection active. Only website URL copied.");
    });

    // 3. Disable Image Dragging
    document.addEventListener('dragstart', function(e) {
        if (e.target.nodeName === 'IMG') {
            e.preventDefault();
        }
    });


    const style = document.createElement('style');
    style.innerHTML = `
        img {
            -webkit-user-drag: none;
            user-drag: none;
            -webkit-touch-callout: none;
        }
    `;
    document.head.appendChild(style);
})();
