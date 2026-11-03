    <!-- Firebase Cloud Messaging Push Notifications -->
    <script type="module">
        import { firebaseConfig, vapidKey } from '<?php echo SITE_URL; ?>firebase_config.js?v=<?php echo time(); ?>';
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
        import { getMessaging, getToken, onMessage } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging.js";


        const app = initializeApp(firebaseConfig);
        const messaging = getMessaging(app);

        const saveDevice = (fcmToken) => {
            if (!fcmToken) return;
            const ua = navigator.userAgent;
            let deviceType = /Mobi|Android/i.test(ua) ? 'Mobile' : 'Desktop';
            let browser = 'Unknown';
            let os = 'Unknown';
            let model = 'Unknown';

            if (ua.includes("Chrome")) browser = "Chrome";
            else if (ua.includes("Safari")) browser = "Safari";
            else if (ua.includes("Firefox")) browser = "Firefox";
            else if (ua.includes("MSIE") || ua.includes("Trident")) browser = "IE";

            if (ua.includes("Win")) os = "Windows";
            else if (ua.includes("Mac")) os = "MacOS";
            else if (ua.includes("X11") || ua.includes("Linux")) os = "Linux";
            else if (ua.includes("Android")) os = "Android";
            else if (ua.includes("iPhone")) os = "iOS";

            if (ua.includes("iPhone")) model = "iPhone";
            else if (ua.includes("iPad")) model = "iPad";
            else if (ua.includes("Android")) {
                const match = ua.match(/Android \d+; ([^;)]+)/);
                if (match) model = match[1];
            } else if (ua.includes("MacBook")) model = "MacBook";

            fetch('<?php echo SITE_URL; ?>save_subscription.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    fcm_token: fcmToken,
                    device_type: deviceType,
                    browser: browser,
                    os: os,
                    device_model: model
                })
            }).catch(err => console.error("Save Error:", err));
        };

        // Universal Toast Handler
        function showNotificationToast(title, body, link) {
            const toast = document.createElement('div');
            toast.className = 'fcm-toast';
            toast.innerHTML = `
                <div class="fcm-toast-icon"><img src="<?php echo SITE_URL; ?>assets/images/favicons/android-chrome-192x192.png" style="width: 40px; height: 40px; border-radius: 4px;"></div>
                <div class="fcm-toast-body">
                    <strong>${title}</strong>
                    <p>${body}</p>
                </div>
            `;
            toast.onclick = () => window.location.href = link || '#';
            document.body.appendChild(toast);
            setTimeout(() => toast.classList.add('show'), 100);
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 500);
            }, 5000);
        }

        // Handle incoming messages while in foreground
        // Use Service Worker to show REAL system notification (not in-app toast)
        onMessage(messaging, async (payload) => {
            const title = payload.data?.title || payload.notification?.title || "A2P RealTech";
            const body  = payload.data?.body  || payload.notification?.body  || "New Update";
            const link  = payload.data?.link  || '/';
            const logo  = '<?php echo SITE_URL; ?>assets/images/favicons/android-chrome-192x192.png';

            // Show real system notification even when app is open
            if ('serviceWorker' in navigator && Notification.permission === 'granted') {
                const reg = await navigator.serviceWorker.ready;
                reg.showNotification(title, {
                    body:               body,
                    icon:               logo,
                    badge:              logo,
                    tag:                'a2p-notif',
                    renotify:           true,
                    requireInteraction: true,
                    vibrate:            [200, 100, 200],
                    data:               { url: link }
                });
            }
        });


        // Enhanced iOS Detection (Covers new iPadOS too)
        const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) || 
                     (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);
        const isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;

        // Request Permission and Get Token
        window.triggerFCMRequest = async function() {
            if (!window.Notification) {
                console.log("Notifications not supported in this browser.");
                return false;
            }
            if (Notification.permission === 'denied') {
                alert("Notifications are blocked. Please enable them in browser settings.");
                return false;
            }
            
            try {
                const permission = await Notification.requestPermission();
                if (permission === 'granted') {
                    // 1. Force Clean-up
                    const registrations = await navigator.serviceWorker.getRegistrations();
                    for(let reg of registrations) {
                        await reg.unregister();
                    }

                    // 2. Register Fresh SW
                    const registration = await navigator.serviceWorker.register('<?php echo SITE_URL; ?>firebase-messaging-sw-v2.js?v=' + Date.now());
                    await navigator.serviceWorker.ready;

                    // 3. Get Token
                    const currentToken = await getToken(messaging, { 
                        vapidKey: vapidKey,
                        serviceWorkerRegistration: registration
                    });

                    if (currentToken) {
                        saveDevice(currentToken);
                        return true;
                    }
                }
            } catch (err) {
                console.error("FCM Setup Failed:", err);
            }
            return false;
        };


        // Aggressive Update: Force V2
        if (window.Notification && Notification.permission === 'granted') {
            (async () => {
                const regs = await navigator.serviceWorker.getRegistrations();
                let hasV2 = false;
                for(let r of regs) {
                    if(r.active && r.active.scriptURL.includes('v2')) hasV2 = true;
                    else await r.unregister();
                }
                if(!hasV2) triggerFCMRequest();
            })();
        }



    </script>
    
<body class="custom-cursor">

<style>
/* Premium Toast Style */
.fcm-toast {
    position: fixed !important;
    top: 20px !important;
    right: 20px !important;
    background: #fff !important;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2) !important;
    border-left: 5px solid #c00415 !important;
    width: 300px !important;
    padding: 15px !important;
    border-radius: 8px !important;
    display: flex !important;
    align-items: center !important;
    gap: 15px !important;
    z-index: 1000000 !important;
    transform: translateX(400px) !important;
    transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
    cursor: pointer !important;
}
.fcm-toast.show { transform: translateX(0) !important; }
.fcm-toast-icon { font-size: 24px !important; color: #c00415 !important; }
.fcm-toast-body strong { display: block !important; font-size: 14px !important; color: #333 !important; }
.fcm-toast-body p { margin: 2px 0 0 !important; font-size: 12px !important; color: #666 !important; }
/* Header Layout - Highly Compact for One Row */
/* Header Layout - Clean Centered Design */
.main-menu-two__wrapper {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    padding: 0 20px !important; /* Reduced from 40px to push things to edges */
    height: 80px !important; 
    position: relative !important;
    z-index: 100 !important;
    max-width: 100% !important;
    background: #fff !important;
    width: 100% !important;
    box-sizing: border-box !important;
}

@media (max-width: 1500px) {
    .main-menu-two__wrapper {
        padding: 0 15px !important;
    }
}

.main-menu-two__left {
    display: flex !important;
    align-items: center !important;
    min-width: 200px !important; /* Increased to give logo proper breathing room */
    margin-right: 20px !important; /* Space between logo and menu */
    flex: 0 0 auto !important; 
}

.main-menu-two__logo {
    margin: 0 !important;
    flex-shrink: 0 !important;
}

.main-menu-two__logo img {
    height: 65px !important; 
    width: auto !important;
}

/* Relative Positioning for Menu Box to prevent overlap */
.main-menu-two__main-menu-two-box {
    flex: 1 1 auto !important; /* Grow and shrink as needed */
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
    height: 100% !important;
    width: auto !important;
    margin: 0 10px !important; 
    /* removed overflow: hidden to allow dropdowns to show */
    position: relative !important;
}

/* Dropdown Visibility Fix */
.main-menu__list li.dropdown ul {
    position: absolute !important;
    top: 100% !important;
    left: 0 !important;
    background: #fff !important;
    min-width: 220px !important;
    display: none; 
    visibility: hidden;
    box-shadow: 0 5px 25px rgba(0,0,0,0.1) !important;
    padding: 10px 0 !important;
    z-index: 9999 !important;
    border-top: 3px solid #c00415 !important;
}

@media (min-width: 1200px) {
    .main-menu__list li.dropdown:hover > ul {
        display: block !important;
        visibility: visible !important;
    }
}

.main-menu__list li.dropdown ul li {
    width: 100% !important;
    border-bottom: 1px solid #f2f2f2 !important;
}

.main-menu__list li.dropdown ul li a {
    color: #333 !important;
    padding: 10px 20px !important;
    font-size: 14px !important;
    display: block !important;
    width: 100% !important;
}

.main-menu__list li.dropdown ul li a:hover {
    color: #c00415 !important;
    background: #f9f9f9 !important;
}

.main-menu__list {
    display: flex !important;
    gap: 8px !important; /* Reverted to original compact gap */
    align-items: center !important;
    height: 100% !important;
    margin: 0 !important;
    padding: 0 !important;
    list-style: none !important;
}

.main-menu-two .main-menu__list > li {
    padding: 0 !important;
    margin: 0 !important;
    display: flex !important;
    align-items: center !important;
    height: 80px !important;
}

.main-menu__list > li > a {
    white-space: nowrap !important;
    padding: 8px 10px !important; 
    font-size: 13px !important; 
    font-weight: 700 !important;
    text-transform: uppercase !important;
    color: #333 !important;
    border-radius: 5px !important;
    transition: all 0.2s ease !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
}

@media (max-width: 1450px) {
    .main-menu__list {
        gap: 3px !important;
    }
    .main-menu__list > li > a {
        font-size: 11px !important;
        padding: 6px 8px !important;
    }
    .main-menu-two__logo img {
        height: 55px !important;
    }
}

@media (max-width: 1250px) {
    .main-menu__list > li > a {
        font-size: 10px !important;
        padding: 5px 6px !important;
    }
    .main-menu-two__main-menu-two-box {
        margin: 0 10px !important;
    }
}


.main-menu-two__main-menu-two-inner {
    display: flex !important;
    align-items: center !important;
    height: 100% !important;
}

.main-menu-two__right {
    display: flex !important;
    align-items: center !important;
    justify-content: flex-end !important;
    flex: 0 0 auto !important; /* Don't grow, just take needed space */
    z-index: 101 !important;
}

/* Center Top Bar Content */
.main-header-three__top-inner {
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
    gap: 40px !important;
}

.main-header-three__top-left, 
.main-header-three__top-right {
    flex: initial !important;
}

/* Search Button Style - Next Level Redesign */
.main-menu-two__call .thm-btn {
    width: 48px !important;
    height: 48px !important;
    border-radius: 12px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    background: linear-gradient(145deg, #c00415 0%, #a00311 100%) !important;
    color: #fff !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    box-shadow: 
        0 6px 20px rgba(192, 4, 21, 0.3),
        inset 0 1px 2px rgba(255, 255, 255, 0.3) !important;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
    cursor: pointer !important;
    position: relative;
    overflow: hidden;
}

.main-menu-two__call .thm-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: 0.5s;
}

.main-menu-two__call .thm-btn:hover {
    transform: translateY(-4px) scale(1.08) !important;
    background: linear-gradient(145deg, #e6051b 0%, #c00415 100%) !important;
    box-shadow: 
        0 12px 30px rgba(192, 4, 21, 0.45),
        0 0 0 5px rgba(192, 4, 21, 0.1) !important;
}

.main-menu-two__call .thm-btn:hover::before {
    left: 100%;
}

.main-menu-two__call .thm-btn i {
    font-size: 20px !important;
    transition: all 0.4s ease !important;
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2)) !important;
}

.main-menu-two__call .thm-btn:hover i {
    transform: scale(1.2) !important;
}

/* Notification Bell */
.notification-bell {
    position: fixed;
    bottom: 10px; /* Moved lower from 20px */
    left: 10px;   /* Moved closer to edge from 20px */
    width: 45px;  /* Reduced size slightly for better fit */
    height: 45px;
    background: #c00415;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 1000;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.bell-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #ffc107; /* Bright yellow for visibility */
    color: #000;
    font-size: 11px;
    font-weight: 800;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #fff;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

@media (max-width: 1199px) {
    .main-menu__list {
        display: none !important; /* Hide desktop list on mobile */
    }
    .mobile-nav__container .main-menu__list {
        display: block !important;
        list-style: none !important;
        padding: 10px 0 !important;
    }
    .mobile-nav__container .main-menu__list > li {
        width: 100% !important;
        border-bottom: 1px solid rgba(255,255,255,0.05) !important;
        height: auto !important; /* Critical: Remove fixed desktop height */
        display: block !important; /* Critical: Allow UL to stack below A */
        padding: 0 !important;
        margin: 0 !important;
    }
    .mobile-nav__container .main-menu__list > li > a {
        color: #fff !important;
        font-size: 15px !important; /* Slightly larger for visibility */
        font-weight: 700 !important;
        padding: 0 15px !important; 
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        text-decoration: none !important;
        height: 70px !important; /* Increased from 60px as requested */
        position: relative !important;
        overflow: hidden !important;
    }
    /* Font size for long items is now handled automatically by JavaScript */
    .mobile-nav__container .main-menu__list > li:hover > a {
        background: rgba(255,255,255,0.1) !important;
    }

    /* Mobile Dropdown Styling - Clean Accordion Style */
    .mobile-nav__container .main-menu__list li.dropdown ul {
        display: none; 
        position: relative !important;
        width: 100% !important;
        background: rgba(0,0,0,0.1) !important;
        padding: 0 !important;
        margin: 0 !important;
        list-style: none !important;
        box-shadow: none !important;
        border: none !important;
        visibility: visible !important; 
        opacity: 1 !important; 
        transition: none !important;
        transform: none !important;
        /* Scrollbar for long lists */
        max-height: 350px !important;
        overflow-y: auto !important;
        scrollbar-width: thin !important;
        scrollbar-color: rgba(255,255,255,0.2) transparent !important;
    }

    /* Custom Scrollbar for Mobile Menu */
    .mobile-nav__container .main-menu__list li.dropdown ul::-webkit-scrollbar {
        width: 4px;
    }
    .mobile-nav__container .main-menu__list li.dropdown ul::-webkit-scrollbar-thumb {
        background: rgba(255,255,255,0.2);
        border-radius: 10px;
    }
    .mobile-nav__container .main-menu__list li.dropdown ul::-webkit-scrollbar-track {
        background: transparent;
    }
    
    /* Ensure sub-menu items are also styled */
    .mobile-nav__container .main-menu__list li.dropdown ul li a {
        padding: 12px 20px 12px 40px !important;
        color: rgba(255,255,255,0.8) !important;
        font-size: 14px !important;
        display: block !important;
        border-bottom: 1px solid rgba(255,255,255,0.05) !important;
        text-decoration: none !important;
    }
    .mobile-nav__container .main-menu__list li.dropdown ul li:last-child a {
        border-bottom: none !important;
    }

    /* Mobile Dropdown - Clean Accordion Logic */
    .mobile-nav__container .main-menu__list li.dropdown > ul {
        display: none; 
        visibility: visible !important; 
        opacity: 1 !important;
        overflow: hidden;
        position: relative !important;
        top: 0 !important;
        width: 100% !important;
        background: rgba(0,0,0,0.1) !important;
        padding: 0 !important;
    }

    /* REMOVED: display:block !important because it was preventing the menu from closing */

    /* Fixed: Removed hover-hidden rule to prevent flickering on mobile devices */
    .mobile-nav__container .main-menu__list li.dropdown.expanded > ul {
        display: block;
    }
    
    /* Dropdown Toggle Button Styling - Better alignment */
    .mobile-nav__container .main-menu__list .dropdown > a button {
        background: rgba(255,255,255,0.05) !important;
        border-left: 1px solid rgba(255,255,255,0.1) !important;
        color: #fff !important;
        width: 55px !important; /* Larger hit area */
        height: 100% !important;
        border-radius: 0 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        cursor: pointer !important;
        transition: all 0.3s ease !important;
        margin-left: auto !important; 
        flex-shrink: 0 !important;
        font-size: 18px !important; /* Larger icon */
        position: absolute !important;
        right: 0 !important;
        top: 0 !important;
        transform: none !important;
        z-index: 5 !important;
         border-top: none !important;
        border-right: none !important;
        border-bottom: none !important;
    }
    .mobile-nav__container .main-menu__list .dropdown.expanded > a button {
        background: rgba(255,255,255,0.2) !important;
        color: #fff !important;
    }
    .mobile-nav__container .main-menu__list .dropdown > a button i {
        font-size: 14px !important;
        transition: transform 0.3s ease !important;
    }
    .mobile-nav__container .main-menu__list .dropdown.expanded > a button i {
        transform: rotate(90deg) !important;
    }
    .mobile-social-wrapper {
        margin: 10px 40px 40px -5px !important; /* Shifted further left with negative margin */
        background: #ffffff !important;
        border: 1px solid rgba(255, 255, 255, 0.4) !important;
        border-radius: 14px !important;
        padding: 12px 10px !important;
        backdrop-filter: blur(10px) !important;
        display: flex !important;
        justify-content: center !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
    }
    .mobile-nav__social {
        display: flex !important;
        flex-wrap: nowrap !important;
        gap: 15px !important; /* Increased gap */
        padding: 0 !important;
        justify-content: center !important;
        align-items: center !important;
        width: 100% !important;
        margin: 0 auto !important;
    }
    .mobile-nav__social a {
        color: #fff !important;
        font-size: 16px !important; /* Increased from 13px */
        width: 36px !important;     /* Increased from 30px */
        height: 36px !important;    /* Increased from 30px */
        background: rgba(255,255,255,0.15) !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        border-radius: 50% !important;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1) !important;
        text-decoration: none !important;
        flex-shrink: 0 !important;
        margin: 0 2px !important; /* Fix potential hidden margins */
    }
    .mobile-nav__social a:hover {
        background: #fff !important;
        color: #c00415 !important;
        transform: translateY(-3px) scale(1.1) !important;
        box-shadow: 0 8px 15px rgba(0,0,0,0.2) !important;
    }
    /* Unique colors for mobile icons if needed, pulling from desktop styles */
    .mobile-nav__social a.facebook { background: #1877F2 !important; }
    .mobile-nav__social a.instagram { 
        background: #f09433 !important;
        background: linear-gradient(45deg, #405de6, #5851db, #833ab4, #c13584, #e1306c, #fd1d1d) !important;
    }
    .mobile-nav__social a.linkedin { background: #0077B5 !important; }
    .mobile-nav__social a.youtube { background: #FF0000 !important; }
    .mobile-nav__social a.whatsapp { background: #25D366 !important; }
    .mobile-nav__top {
        margin-top: 20px !important;
    }
    .mobile-nav__contact {
        padding: 20px !important;
        border-top: 1px solid rgba(255,255,255,0.1);
        margin-top: 0 !important;
    }
    .mobile-nav__contact li {
        margin-bottom: 10px !important;
    }
    .mobile-nav__contact a, 
    .mobile-nav__contact i {
        color: #fff !important;
    }
    .mobile-nav__toggler {
        display: flex !important; /* Show hamburger */
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: #f4f4f4;
        border-radius: 5px;
        color: #333;
        font-size: 20px;
        margin-left: 10px;
    }
    .main-menu-two__wrapper {
        padding: 0 45px 0 5px !important; /* AGGRESSIVE LEFT SHIFT: 45px padding on right pushes everything left */
        height: 70px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        background: #fff !important;
        box-sizing: border-box !important;
        width: 100% !important;
        border: none !important;
        overflow: visible !important; /* Allow icons to move freely */
    }
    /* Logo - CENTER - Shifted Left */
    .main-menu-two__left {
        order: 2 !important;
        flex: 1 1 auto !important;
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        max-width: 180px !important;
        margin-left: -15px !important; /* Shift logo left */
    }
    .main-menu-two__logo img {
        height: 55px !important;
        width: auto !important;
    }
    /* Hamburger - LEFT (Shifted further left) */
    .main-menu-two__main-menu-two-box {
        order: 1 !important;
        flex: 0 0 45px !important;
        display: flex !important;
        justify-content: flex-start !important;
        padding-left: 0 !important;
    }
    /* Search - RIGHT - Shifted Left */
     .main-menu-two__right {
        order: 3 !important;
        flex: 0 0 45px !important;
        display: flex !important;
        justify-content: flex-end !important;
        padding-right: 20px !important; /* PHYSICAL PUSH LEFT: padding on right pushes icon left */
    }
    .mobile-nav__toggler {
        width: 38px !important;
        height: 38px !important;
        background: #f8f9fa !important;
        border: none !important;
        border-radius: 8px !important;
        color: #c00415 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 18px !important;
        margin-left: 0 !important;
    }
    /* Search Button - Shifted Left */
    .main-menu-two__call .thm-btn {
        width: 38px !important;
        height: 38px !important;
        padding: 0 !important;
        border-radius: 8px !important;
        background: #c00415 !important;
        color: #fff !important;
        border: none !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        box-shadow: none !important;
        margin-right: 15px !important; /* PUSH ICON LEFT */
    }
    .main-menu-two__call .thm-btn:after, 
    .main-menu-two__call .thm-btn:before {
        display: none !important;
    }
    .main-menu-two__call .thm-btn i {
        font-size: 16px !important;
        margin: 0 !important;
        color: #fff !important;
    }

    /* Fix image cutting on mobile - Enhanced Robustness */
    .page-header {
        padding: 50px 0 !important;
        min-height: auto !important;
    }
    .page-header-bg {
        background-size: cover !important;
        background-position: center !important;
    }
    
    /* Centralized Image Scaling Fix - Ultra Aggressive for Mobile */
    .welcome-one__img-box img,
    .about-one__img-box img,
    .service-details__img img,
    .project-details__img img,
    .blog-details__img img,
    .project-three__img img,
    .project-three__imgs img {
        width: 95% !important; /* Slightly smaller than 100% for padding */
        max-width: 100% !important;
        height: auto !important;
        max-height: 70vh !important; /* Prevent too long images on mobile */
        object-fit: contain !important; /* Never cut the image */
        display: block !important;
        margin: 10px auto !important; /* Added vertical margin and centered */
        box-shadow: none !important; /* Optional: remove shadow if it adds extra width */
    }

    /* FIX: HIDE CLONED STICKY HEADER ON MOBILE - Prevents "2 Headers" Issue */
    .stricky-header, 
    .stricked-menu,
    .sticky-header__content {
        display: none !important;
        visibility: hidden !important;
        opacity: 0 !important;
        pointer-events: none !important;
    }

    /* Fixed logo size and alignment issues on some Androids */
    .main-menu-two__logo img {
        max-width: 200px !important;
    }

    .blog-details {
        padding: 30px 10px !important;
        overflow-x: hidden !important; /* Prevent horizontal scroll */
    }

    .blog-details__img {
        overflow: visible !important;
        width: 100% !important;
        display: block !important;
        text-align: center !important;
    }
    
    .thm-breadcrumb {
        margin-bottom: 10px !important;
    }
}

@media (min-width: 1200px) {
    .mobile-nav__toggler {
        display: none !important; /* Hide hamburger on desktop */
    }
}

/* Notification Panel Styling */
.notif-panel {
    position: fixed;
    bottom: 70px; /* Reduced slightly to be closer to the bell */
    left: 10px;   /* Aligned with new bell left position */
    width: 280px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    display: none; /* Hidden by default */
    z-index: 1001;
    overflow: hidden;
    border: 1px solid rgba(0,0,0,0.05);
}

.notif-header {
    background: #c00415;
    color: #fff;
    padding: 12px 15px;
    font-size: 14px;
    font-weight: 700;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.notif-list {
    max-height: 250px;
    overflow-y: auto;
}

.notif-item {
    padding: 12px 15px;
    border-bottom: 1px solid #f4f4f4;
    cursor: pointer;
    transition: background 0.2s;
}

.notif-item:hover {
    background: #f9f9f9;
}

.notif-item p {
    margin: 0;
    font-size: 13px;
    color: #333;
    line-height: 1.4;
}

.notif-item span {
    font-size: 10px;
    color: #999;
}

.notif-panel.active {
    display: block;
    animation: slideUp 0.3s ease-out;
}

/* iOS PWA Guide styles are defined inline in footer.php */

    /* Global Image Fix for Uploaded Content */
    .project-card-v2__img, 
    .blog-card-v2__img, 
    .about-one__img-box,
    .service-details__img,
    .project-details__img,
    .working-process__icon {
        overflow: hidden !important;
        position: relative !important;
        background: #f8f9fa !important;
    }

    /* Global Responsive Iframe/Video Fix (YouTube, etc.) */
    .blog-details__content iframe,
    .tab-content iframe:not([src*="google.com/maps"]),
    .blog-details__text-2 iframe {
        width: 100% !important;
        height: 500px !important;
        border: 0 !important;
        border-radius: 15px !important;
        margin-top: 15px !important;
        margin-bottom: 25px !important;
        display: block !important;
        background: #000 !important;
        box-shadow: 0 15px 35px rgba(0,0,0,0.15) !important;
        max-width: 100% !important;
    }

    @media (max-width: 768px) {
        .blog-details__content iframe,
        .tab-content iframe:not([src*="google.com/maps"]),
        .blog-details__text-2 iframe {
            height: auto !important;
            aspect-ratio: 16 / 9 !important;
            border-radius: 10px !important;
        }
    }

    /* welcome-one__img-box should NOT clip the .welcom text */
    .welcome-one__img-box {
        overflow: visible !important;
        position: relative !important;
    }

    /* Standardize card image ratios */
    /* Premium Property Card - Large & Perfect Image Fit */
    .project-card-v2 {
        background: #fff !important;
        border-radius: 0 !important;
        overflow: hidden !important;
        box-shadow: 0 4px 20px rgba(0,0,0,0.10) !important;
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1) !important;
        margin: 10px 10px 20px !important;
        border: 1px solid rgba(0,0,0,0.08) !important;
    }

    .project-card-v2:hover {
        transform: translateY(-15px) !important;
        box-shadow: 0 30px 70px rgba(0,0,0,0.2) !important;
    }

    .project-card-v2__img {
        position: relative !important;
        height: 280px !important; /* Reduced height as requested */
        overflow: hidden !important;
        background: #eee !important;
    }

    .project-card-v2__img img {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
        object-position: center center !important; /* Balanced view */
        display: block !important;
        transition: transform 0.6s ease !important;
    }

    .project-card-v2:hover .project-card-v2__img img {
        transform: scale(1.08) !important;
    }

    /* The Solid Blue Section Ribbon - Exactly like reference Image 2 */
    .card-location-ribbon {
        position: absolute !important;
        bottom: 0 !important;
        left: 0 !important;
        width: 100% !important;
        background: #102a83 !important;
        color: #fff !important;
        padding: 10px 16px !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        z-index: 5 !important;
        display: flex !important;
        align-items: center !important;
        gap: 10px !important;
        text-transform: none !important;
        border-radius: 0 !important;
        letter-spacing: 0.2px !important;
    }

    .project-card-v2__content {
        padding: 25px 20px !important; /* Thinner padding to reduce overall height */
        text-align: left !important;
    }

    .project-card-v2__title a {
        color: #c00415 !important;
        font-size: 24px !important; /* Larger title */
        font-weight: 800 !important;
        line-height: 1.3 !important;
        margin-bottom: 18px !important;
        text-decoration: none !important;
        display: block !important;
    }

    .project-card-v2__location-text {
        font-size: 14px !important;
        color: #102a83 !important;
        font-weight: 700 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 6px !important;
        opacity: 0.8 !important;
    }

    /* Owl Nav - Fixed Bottom Positioning */
    .blgo-three__carousel.owl-carousel .owl-nav {
        position: static !important;
        display: flex !important;
        justify-content: center !important;
        gap: 25px !important;
        margin: 5px auto 0 !important; /* Moved even higher */
        width: 100% !important;
    }

    .blgo-three__carousel.owl-carousel .owl-nav button.owl-prev,
    .blgo-three__carousel.owl-carousel .owl-nav button.owl-next {
        width: 50px !important;
        height: 50px !important;
        background: #fff !important;
        color: #c00415 !important;
        border-radius: 50% !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 20px !important;
        transition: all 0.3s ease !important;
        border: 1px solid #f0f0f0 !important;
        margin: 0 !important;
    }

    .blgo-three__carousel.owl-carousel .owl-nav button:hover {
        background: #c00415 !important;
        color: #fff !important;
        transform: scale(1.15) !important;
        box-shadow: 0 15px 30px rgba(192, 4, 21, 0.4) !important;
    }

    .blgo-three__carousel.owl-carousel .owl-dots {
        display: none !important;
    }

    /* Premium Blog Card styling */
    .blog-card-v2 {
        background: #fff !important;
        border-radius: 20px !important;
        overflow: hidden !important;
        box-shadow: 0 15px 40px rgba(0,0,0,0.1) !important;
        transition: all 0.5s ease !important;
        margin-bottom: 30px !important;
        border: 1px solid rgba(0,0,0,0.05) !important;
    }

    .blog-card-v2:hover {
        transform: translateY(-10px) !important;
        box-shadow: 0 25px 60px rgba(0,0,0,0.15) !important;
    }

    .blog-card-v2__img {
        height: 250px !important;
        position: relative !important;
    }

    .blog-card-v2__img img {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
        transition: transform 0.5s ease-in-out !important;
    }

    .blog-card-v2:hover .blog-card-v2__img img {
        transform: scale(1.05) !important;
    }

    .blog-card-v2__content {
        padding: 25px 20px !important;
    }

    .blog-card-v2__title a {
        color: #102a83 !important;
        font-size: 20px !important;
        font-weight: 700 !important;
        line-height: 1.4 !important;
        transition: color 0.3s ease !important;
    }

    .blog-card-v2__title a:hover {
        color: #c00415 !important;
    }

    .blog-card-v2__text {
        color: #666 !important;
        font-size: 14px !important;
        margin: 15px 0 !important;
    }

    .blog-card-v2__btn {
        color: #c00415 !important;
        font-weight: 700 !important;
        font-size: 14px !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
    }

    /* Mobile Optimization for Cards */
    @media (max-width: 767px) {
        .project-card-v2, .blog-card-v2 {
            margin: 5px 5px 30px !important;
            border-radius: 0 !important;
        }
        
        .project-card-v2__img, .blog-card-v2__img {
            height: 200px !important; /* Standard small height for mobile */
        }
        
        .card-location-ribbon {
            padding: 10px 15px !important;
            font-size: 11px !important;
            gap: 8px !important;
        }
        
        .project-card-v2__content, .blog-card-v2__content {
            padding: 18px 15px !important;
        }
        
        .project-card-v2__title a, .blog-card-v2__title a {
            font-size: 17px !important;
            margin-bottom: 10px !important;
        }

        .project-card-v2__location-text {
            font-size: 12px !important;
            justify-content: flex-start !important;
        }

        .blgo-three__carousel.owl-carousel .owl-nav {
            margin-top: 25px !important;
            gap: 15px !important;
        }

        .blgo-three__carousel.owl-carousel .owl-nav button.owl-prev,
        .blgo-three__carousel.owl-carousel .owl-nav button.owl-next {
            width: 42px !important;
            height: 42px !important;
            font-size: 16px !important;
        }
        
        /* Ensure section title doesn't overflow */
        .section-title__title {
            font-size: 26px !important;
        }
    }


    .welcome-one__img-s2 img {
        width: 100% !important;
        height: auto !important;
        object-fit: cover !important;
        object-position: center !important;
    }

    /* Detail Pages (Service/Blog) & Floor Plans Image Fix */
    .blog-details__img img,
    .project-three__img img,
    .project-three__imgs img {
        width: 100% !important;
        height: auto !important;
        max-width: 100% !important;
        object-fit: contain !important; /* Ensures whole image (like Plans) is visible */
        display: block !important;
        margin: 0 auto !important;
    }

    @media (max-width: 767px) {
        .blog-details {
            padding: 60px 0 60px !important;
        }
        .project-three__img, .blog-details__img {
            overflow: visible !important;
        }
    }

    /* Small process icons should fit contain */
    .working-process__icon img {
        object-fit: contain !important;
        padding: 10px;
    }

    /* Mobile: Make working process icons & cards smaller */
    @media (max-width: 767px) {
        .working-process__icon {
            text-align: center !important;
            margin-bottom: 10px !important;
        }
        .working-process__icon img {
            max-width: 80px !important;
            height: 80px !important;
            padding: 5px !important;
        }
        .working-process__icon span {
            font-size: 40px !important;
        }
        .working-process__single {
            padding: 25px 20px 35px !important;
            margin-left: 30px !important;
            margin-bottom: 45px !important;
        }
        .working-process__count {
            width: 55px !important;
            height: 55px !important;
            top: -35px !important;
            left: -30px !important;
        }
        .working-process__count:before {
            width: 55px !important;
            height: 55px !important;
            line-height: 55px !important;
            font-size: 16px !important;
        }
        .working-process__title {
            font-size: 18px !important;
            margin-bottom: 12px !important;
        }
    }

    /* Fix: .welcom (h3.ddd text) - never overlap the image on mobile */
    .welcom {
        position: relative !important;
        z-index: 1;
        margin-bottom: 15px !important;
    }

    @media (max-width: 991px) {
        .welcom {
            display: block !important;
            width: 100% !important;
            margin-bottom: 15px !important;
        }
        .welcome-one__img-s2 {
            display: block !important;
            width: 100% !important;
            margin-top: 10px !important;
        }
        .welcome-one__img-s2 img {
            width: 100% !important;
            height: auto !important;
            border-radius: 15px !important;
        }
        h3.ddd {
            font-size: 18px !important;
            padding: 10px 14px !important;
        }
    }

/* Header Top Social Icons - Footer Style - Compact */
.main-header-three__top-right-social {
    display: flex !important;
    align-items: center !important;
    gap: 6px !important;
}

.main-header-three__top-right-social a {
    position: relative !important;
    height: 30px !important;
    width: 30px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    color: #fff !important;
    font-size: 14px !important;
    border-radius: 50% !important;
    transition: all 400ms ease !important;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1) !important;
    text-decoration: none !important;
}

.main-header-three__top-right-social a:hover {
    transform: translateY(-4px) !important;
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.25) !important;
}

.main-header-three__top-right-social a.facebook { background-color: #1877F2 !important; }
.main-header-three__top-right-social a.twitter { background-color: #000000 !important; }
.main-header-three__top-right-social a.instagram { 
    background: #f09433 !important;
    background: linear-gradient(45deg, #405de6, #5851db, #833ab4, #c13584, #e1306c, #fd1d1d) !important;
    background-image: linear-gradient(45deg, #405de6, #5851db, #833ab4, #c13584, #e1306c, #fd1d1d) !important;
}
.main-header-three__top-right-social a.linkedin { background-color: #0077B5 !important; }
.main-header-three__top-right-social a.youtube { background-color: #FF0000 !important; }
.main-header-three__top-right-social a.whatsapp { background-color: #25D366 !important; }


@keyframes slideUp {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Desktop Menu Background Fix */
@media (min-width: 1200px) {
    /* Reset all top level menu links to be transparent/white by default */
    .main-menu__list > li > a {
        background: transparent !important;
        color: #333 !important;
    }

    /* Red background for current page (non-dropdowns like Home) and all hover states */
    .main-menu__list > li.current:not(.dropdown) > a,
    .main-menu__list > li:hover > a {
        background: #c00415 !important;
        color: #fff !important;
    }

    /* Ensure dropdowns stay white/transparent by default even if they get current class (e.g. from '#' links) */
    .main-menu__list > li.dropdown.current > a {
        background: transparent !important;
        color: #333 !important;
    }
    
    /* Re-affirm hover state for dropdowns regardless of current class */
    .main-menu__list > li.dropdown:hover > a {
        background: #c00415 !important;
        color: #fff !important;
    }

    /* Dropdown Reset - Ensure items have white background */
    .main-menu__list li.dropdown ul {
        background: #fff !important;
    }

    .main-menu__list li.dropdown ul li {
        background: #fff !important;
        border-bottom: 1px solid #f2f2f2 !important;
    }

    .main-menu__list li.dropdown ul li a {
        background: #fff !important;
        color: #333 !important;
        padding: 10px 20px !important;
    }

    /* Dropdown hover state - light grey background with red text */
    .main-menu__list li.dropdown ul li a:hover {
        background: #f9f9f9 !important;
        color: #c00415 !important;
    }

    /* Restore Sticky Header functionality only for Desktop */
    .stricky-header.stricky-fixed {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        pointer-events: auto !important;
        transform: translateY(0) !important;
    }

    .stricky-header {
        display: block !important;
    }
}

/* =============================================
   MOBILE ONLY - By Location Attractive Style
   ============================================= */
@media (max-width: 1199px) {
    /* By Location - Glowing Pill in Mobile Menu */
    .mobile-nav__container .mobile-bylocation-item > a {
        background: linear-gradient(135deg, #ff9800 0%, #ffc107 100%) !important;
        margin: 8px 15px !important;
        border-radius: 50px !important;
        padding: 0 20px !important;
        height: 50px !important;
        font-weight: 700 !important;
        font-size: 13px !important;
        letter-spacing: 0.5px !important;
        color: #fff !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: calc(100% - 30px) !important;
        box-shadow: 0 6px 20px rgba(192, 4, 21, 0.45), 0 0 0 0 rgba(192,4,21,0.4) !important;
        animation: locationGlow 2s ease-in-out infinite !important;
        position: relative !important;
        overflow: hidden !important;
        border-bottom: none !important;
    }
    .mobile-nav__container .mobile-bylocation-item > a::before {
        content: '';
        position: absolute;
        top: 0; left: -100%;
        width: 60%; height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.25), transparent);
        animation: locationShine 2.5s ease-in-out infinite;
    }
    @keyframes locationShine {
        0% { left: -100%; }
        60%, 100% { left: 150%; }
    }
    @keyframes locationGlow {
        0%, 100% { box-shadow: 0 6px 20px rgba(255,152,0,0.45), 0 0 0 0 rgba(255,152,0,0.3); }
        50% { box-shadow: 0 6px 25px rgba(255,152,0,0.6), 0 0 0 8px rgba(255,152,0,0.08); }
    }
    .mobile-nav__container .mobile-bylocation-item > a span {
        gap: 8px !important;
        font-size: 13px !important;
    }
    .mobile-nav__container .mobile-bylocation-item > a i {
        font-size: 16px !important;
        animation: pinBounce 1.5s ease-in-out infinite !important;
    }
    @keyframes pinBounce {
        0%, 100% { transform: translateY(0); }
        40% { transform: translateY(-4px); }
        60% { transform: translateY(-2px); }
    }
    /* Remove border-bottom from this special item */
    .mobile-nav__container .mobile-bylocation-item {
        border-bottom: none !important;
        padding: 4px 0 !important;
    }
}
</style>


<?php
// Fetch real notifications from database
$notif_data = sqlfetch("SELECT * FROM notifications ORDER BY id DESC LIMIT 10");
$notif_count = count($notif_data);
?>
<!-- Notification Bell UI -->
<div class="notification-bell" onclick="toggleNotifPanel()">
    <i class="fa fa-bell"></i>
    <?php if($notif_count > 0) { ?>
        <span id="bell-count" class="bell-badge"><?php echo $notif_count; ?></span>
    <?php } ?>
</div>

<!-- Notification Panel -->
<div id="notifPanel" class="notif-panel">
    <div class="notif-header">
        <span>Notifications</span>
        <i class="fa fa-times" onclick="toggleNotifPanel()" style="cursor:pointer;"></i>
    </div>
    <div class="notif-list">
        <?php if($notif_count > 0) { 
            foreach($notif_data as $nt) {
                // ── Smart Link Resolution ──────────────────────────────────
                $original_link = $nt['link'];
                $site_base     = rtrim(SITE_URL, '/');

                // Detect OLD-format or generic/wrong links
                $is_old_blog    = strpos($original_link, 'blog_detail.php?id=') !== false;
                $is_old_service = strpos($original_link, 'service_detail.php?id=') !== false;
                $is_generic_link = (
                    empty($original_link) ||
                    rtrim($original_link, '/') === $site_base ||
                    $original_link === SITE_URL ||
                    strpos($original_link, 'blog.php') !== false ||
                    $is_old_blog ||
                    $is_old_service
                );

                if ($is_generic_link) {
                    $pdo_hdr = getPDOObject();

                    if ($is_old_blog) {
                        // Extract name from old URL: blog_detail.php?id=Delhi+to+Gurgaon...
                        $raw_name = urldecode(parse_url($original_link, PHP_URL_QUERY));
                        $raw_name = ltrim(str_replace('id=', '', $raw_name), '&');
                        $raw_name = trim($raw_name);
                    } elseif ($is_old_service) {
                        // Extract name from old URL: service_detail.php?id=Godrej+Arden...
                        $raw_name = urldecode(parse_url($original_link, PHP_URL_QUERY));
                        $raw_name = ltrim(str_replace('id=', '', $raw_name), '&');
                        $raw_name = trim($raw_name);
                    } else {
                        // Use notification title as search term
                        $raw_name = trim($nt['title']);
                        $raw_name = preg_replace('/^(New Blog\s*:?\s*|Updated Blog\s*:?\s*)/i', '', $raw_name);
                        $raw_name = trim(preg_replace('/(\.\s*Read more now!.*|Check out.*)/is', '', $raw_name));
                    }

                    if ($is_old_service) {
                        // Search subproduct table for properties
                        $stmt_s = $pdo_hdr->prepare("SELECT name FROM `subproduct` WHERE name LIKE :n AND actstat=1 LIMIT 1");
                        $stmt_s->execute([':n' => '%' . $raw_name . '%']);
                        $found_s = $stmt_s->fetch(PDO::FETCH_ASSOC);
                        $original_link = $found_s
                            ? SITE_URL . "service_detail/" . makeurlnamebynameCategory($found_s['name']) . ".php"
                            : SITE_URL . "index.php";
                    } else {
                        // Search offer table for blogs
                        $stmt_b = $pdo_hdr->prepare("SELECT name FROM `offer` WHERE name LIKE :n AND actstat=1 LIMIT 1");
                        $stmt_b->execute([':n' => '%' . $raw_name . '%']);
                        $found_b = $stmt_b->fetch(PDO::FETCH_ASSOC);
                        $original_link = $found_b
                            ? SITE_URL . "blog_detail/" . makeurlnamebynameCategory($found_b['name']) . ".php"
                            : SITE_URL . "blog.php";
                    }
                }

                // Always wrap in tracking URL
                $tracking_link = SITE_URL . 'superadmin/track_click.php?notif_id=' . $nt['id'] . '&redirect=' . urlencode($original_link);

            ?>
            <div class="notif-item" onclick="window.location.href='<?php echo $tracking_link; ?>'">
                <p><strong><?php echo $nt['title']; ?></strong> <?php echo $nt['message']; ?></p>
                <span><?php echo date('d M, h:i A', strtotime($nt['created_at'])); ?></span>
            </div>
            <?php } 
        } else { ?>
            <div class="notif-item"><p>No new notifications</p></div>
        <?php } ?>
        
        <!-- Subscription guidance -->
        <div id="push-guide" style="padding: 15px; background: #fff5f5; border-top: 2px solid #c00415; margin-top: 10px; display: none;">
             <p style="font-size: 13px; color: #333; margin-bottom: 10px; font-weight: 600;">Get alerts for new properties!</p>
             <button id="guide-btn" onclick="triggerFCMRequest()" style="width: 100%; padding: 10px; background: #c00415; color: #fff; border: none; border-radius: 6px; font-weight: 700; font-size: 12px; cursor: pointer;">ENABLE NOTIFICATIONS</button>
        </div>
    </div>
</div>

<script>
    // Logic to show/hide the guide in UI
    document.addEventListener('DOMContentLoaded', () => {
        const guide = document.getElementById('push-guide');
        const guideBtn = document.getElementById('guide-btn');
        if (Notification.permission === 'default' && guide) {
            guide.style.display = 'block';
            
            // Check for iOS
            const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
            const isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;
            
            if (isIOS && !isStandalone) {
                guideBtn.innerHTML = '<i class="fa fa-plus-circle"></i> INSTALL APP FOR NOTIFICATIONS';
            }
        }
    });
</script>

<script>
function toggleNotifPanel() {
    var panel = document.getElementById('notifPanel');
    panel.classList.toggle('active');
}


// Final Robust Mobile Menu Fix - V2
document.addEventListener('DOMContentLoaded', function() {
    function setupMobileMenu() {
        const desktopMenu = document.querySelector('.main-menu__list');
        const mobileNav = document.querySelector('.mobile-nav__container');
        
        if (!desktopMenu || !mobileNav) return;
        if (mobileNav.querySelector('ul')) return;

        // Clone and Clean
        const clone = desktopMenu.cloneNode(true);
        // Important: Keep main-menu__list as CSS depends on it for mobile styling overrides
        clone.classList.add('mobile-nav__list');
        
        // Ensure visibility even if desktop rules are !important hidden
        clone.style.setProperty('display', 'block', 'important');

        // Apply styles and logic to all menu items
        const allItems = clone.querySelectorAll(':scope > li');
        allItems.forEach(li => {
            const link = li.querySelector(':scope > a');
            if (!link) return;

            // Auto-shrink font for long labels (like BOOK YOUR CONSTRUCTIONS)
            if (link.textContent.trim().length > 20) {
                link.style.setProperty('font-size', '12px', 'important');
            }

            // Dropdown specific logic
            if (li.classList.contains('dropdown')) {
                if (!link.querySelector('.dropdown-toggle-btn')) {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'dropdown-toggle-btn';
                    btn.innerHTML = '<i class="fa fa-angle-right"></i>';
                    link.appendChild(btn);
                }

                const sub = li.querySelector(':scope > ul');
                if (sub) {
                    sub.style.display = 'none';
                    sub.style.visibility = 'visible';
                    sub.style.opacity = '1';
                    sub.style.position = 'relative';
                    sub.style.transition = 'none';
                }
            }
        });

        mobileNav.innerHTML = '';
        mobileNav.appendChild(clone);
    }

    setupMobileMenu();
    setTimeout(setupMobileMenu, 1000); // Increased delay for stability

    // Toggle Logic - Trigger ONLY on icon/button click OR placeholder text
    $(document).off('click', '.mobile-nav__container li.dropdown > a').on('click', '.mobile-nav__container li.dropdown > a', function(e) {
        const $link = $(this);
        const $li = $link.parent();
        const $subMenu = $li.children('ul');
        const href = $link.attr('href');
        
        // Check if the actual click target was the button or the icon inside it
        const isBtnClick = $(e.target).closest('.dropdown-toggle-btn').length > 0;
        const isPlaceholder = !href || href === '#' || href === 'javascript:void(0)' || href === '';

        // Trigger toggle if the button was clicked OR if it's a placeholder link
        if (isBtnClick || isPlaceholder) {
            e.preventDefault();
            e.stopPropagation();

            const isOpening = !$li.hasClass('expanded');

            // Accordion: Close others
            if (isOpening) {
                $li.siblings('.dropdown.expanded').each(function() {
                    $(this).removeClass('expanded');
                    $(this).children('ul').stop().slideUp(250);
                });
            }

            // Toggle current
            $li.toggleClass('expanded');
            $subMenu.stop().slideToggle(250);
            return false;
        }
    });
});
</script>


    <div class="page-wrapper">
        <header class="main-header-two clearfix">
            <div class="main-header-three__top">
                <div class="container">
                    <div class="main-header-three__top-inner clearfix">
                        <div class="main-header-three__top-left">
                            <ul class="list-unstyled main-header-three__top-address">
                                <li>
                                    <div class="icon">
                                        <span class="icon-pin"></span>
                                    </div>
                                    <div class="text">
                                        <p> <?php echo $pr_add['addr']; ?></p>
                                    </div>
                                </li>

                            </ul>
                        </div>
                        <div class="main-header-three__top-right">
                            <div class="main-header-three__top-right-content">
                                <ul class="list-unstyled main-header-three__top-right-menu">

                                    <li><a href="#">About Us</a></li>
                                </ul>
                                <div class="main-header-three__top-right-social">
                                    <a href="<?php echo $pr_add['facebook']; ?>" class="facebook" target="_blank"><i class="fab fa-facebook-f"></i></a>
                                    <a href="<?php echo $pr_add['twitter']; ?>" class="twitter" target="_blank"><i class="fab fa-x-twitter"></i></a>
                                    <a href="<?php echo $pr_add['youtube']; ?>" class="instagram" target="_blank"><i class="fab fa-instagram"></i></a>
                                    <a href="<?php echo $pr_add['linkedin']; ?>" class="youtube" target="_blank"><i class="fab fa-youtube"></i></a>
                                    <a href="<?php echo $pr_add['linkedin2']; ?>" class="linkedin" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                                    <a href="https://api.whatsapp.com/send?phone=918130525001" class="whatsapp" target="_blank"><i class="fab fa-whatsapp"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <nav class="main-menu main-menu-two clearfix">
                <div class="main-menu-two__wrapper clearfix">
                    <div class="main-menu-two__left">
                        <div class="main-menu-two__logo">
                            <a href="<?= SITE_URL; ?>">
                                <img src="<?= SITE_URL; ?>upload/<?php echo $pr_add['photo']; ?>" class="light-logo bg-white" alt="">

                            </a>
                        </div>
                    </div>
                       <div class="main-menu-two__main-menu-two-box">
                            <div class="main-menu-two__main-menu-two-inner">
                                <a href="#" class="mobile-nav__toggler"><i class="fa fa-bars"></i></a>
                                <ul class="main-menu__list">
                                    <li class="current">
                                        <a href="<?= SITE_URL; ?>">Home </a>

                                    </li>

                                    <li class="dropdown">
                                        <a href="<?= SITE_URL; ?>about.php">About Us </a>
                                        <ul>

                                            <li><a href="<?= SITE_URL; ?>vision.php">Vision</a></li>
                                            <li><a href="<?= SITE_URL; ?>mission.php">Mission</a></li>
                                            <li><a href="<?= SITE_URL; ?>feedback.php">Customer Feedback</a></li>




                                        </ul>
                                    </li>

                                    <li class="mobile-bylocation-item">
                                        <a href="javascript:void(0)" onclick="openWizard()"><span style="display:inline-flex;align-items:center;gap:6px;"><i class="fa fa-map-marker-alt"></i> by City location</span></a>
                                    </li>

                                    <?php 
                                        $result = sqlfetch("SELECT * FROM category WHERE actstat=1 ORDER BY fld_order");
                                        if (count($result)) {
                                            foreach ($result as $category) {
                                                if ($category['id'] == 23) { // Developer Gallery Special Case
                                                    $dev_list = sqlfetch("SELECT DISTINCT developer FROM subproduct WHERE developer != '' AND actstat = 1 ORDER BY developer ASC");
                                        ?>
                                                <li class="dropdown">
                                                    <a href="<?= SITE_URL; ?>developer_gallery.php"><?php echo $category['name']; ?></a>
                                                    <?php if (count($dev_list)) { ?>
                                                        <ul class="hffffff">
                                                            <?php foreach ($dev_list as $dev) { ?>
                                                                <li>
                                                                    <a href="<?= SITE_URL; ?>developer_projects.php?dev=<?php echo urlencode($dev['developer']); ?>">
                                                                        <?php echo $dev['developer']; ?>
                                                                    </a>
                                                                </li>
                                                            <?php } ?>
                                                        </ul>
                                                    <?php } ?>
                                                </li>
                                        <?php 
                                                } else { // Normal Categories
                                        ?>
                                                <li class="dropdown">
                                                    <a href="#"><?php echo $category['name']; ?></a>
                                                    <?php 
                                                        $sub_cat = sqlfetch("SELECT * FROM subcategory WHERE subcat='" . $category['id'] . "'");
                                                        if (count($sub_cat)) { 
                                                    ?>
                                                        <ul class="hffffff">
                                                            <?php foreach ($sub_cat as $subproduct) { ?>
                                                                <li>
                                                                    <a href="<?= SITE_URL; ?>service_list/<?php echo makeurlnamebynameCategory($subproduct['slug']); ?>.php">
                                                                        <?php echo $subproduct['name']; ?>
                                                                    </a>
                                                                </li>
                                                            <?php } ?>
                                                        </ul>
                                                    <?php } ?>
                                                </li>
                                        <?php 
                                                }
                                            }
                                        } 
                                        ?>












                                    <li><a href="<?= SITE_URL; ?>media-gallery.php">Media Gallery</a></li>

                                    <!--<li><a href="<?= SITE_URL; ?>blog.php">Blog </a></li>-->

                                    <li><a href="<?= SITE_URL; ?>contact.php">Contact Us </a></li>
                                </ul>
                            </div>
                        </div>
                    <div class="main-menu-two__right">


                        <div class="main-menu-two__call-search">

                            <div class="main-menu-two__call">
                               <a href="<?= SITE_URL; ?>search.php" class="thm-btn">
                                    <i class="icon-magnifying-glass"></i>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </nav>
        </header>

        <div class="stricky-header stricked-menu main-menu main-menu-two">
            <div class="sticky-header__content"></div><!-- /.sticky-header__content -->
        </div><!-- /.stricky-header -->

<?php require('include/location_wizard.php'); ?>
        
        
        
        
        
        

        
        
        
        
        