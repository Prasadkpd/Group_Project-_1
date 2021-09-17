const workbox = require("workbox-build");
workbox.generateSW({
    cacheId: "sportizza",
    globDirectory:"./",
    globPatterns: [
        "**/*.{css|js}"
    
    ],
    swDest: "sw.js",
    runtimeCaching:[{
        urlPattern:/\.(?:html|html|xml)$/,
        handler:"staleWhileRevalidate",
        options:{
            cacheName: "markup",
            expiration:{
                maxAgeSeconds: 1800
            }
        }
    }]
});