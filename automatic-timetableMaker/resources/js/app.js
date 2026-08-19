import './bootstrap';
import * as bootstrap from 'bootstrap';

window.bootstrap = bootstrap;

window.addEventListener('DOMContentLoaded', () => {
    // Application-level browser setup can be added here.
    document.addEventListener('DOMContentLoaded', () => {

    const body = document.body;

    const desktopToggle =
        document.getElementById('sidebarToggleDesktop');

    const mobileToggle =
        document.getElementById('sidebarToggleMobile');

    const backdrop =
        document.getElementById('sidebarBackdrop');

    const STORAGE_KEY =
        'unitime.sidebar.collapsed';


    /*
    |--------------------------------------------------------------------------
    | Detect desktop
    |--------------------------------------------------------------------------
    */

    const isDesktop = () =>
        window.matchMedia('(min-width: 992px)').matches;


    /*
    |--------------------------------------------------------------------------
    | Desktop sidebar state
    |--------------------------------------------------------------------------
    */

    const setDesktopSidebar = (
        collapsed,
        persist = true
    ) => {

        if (collapsed) {
            body.classList.add('sidebar-collapsed');
        } else {
            body.classList.remove('sidebar-collapsed');
        }

        if (persist) {
            localStorage.setItem(
                STORAGE_KEY,
                collapsed ? 'true' : 'false'
            );
        }

    };


    /*
    |--------------------------------------------------------------------------
    | Restore saved sidebar preference
    |--------------------------------------------------------------------------
    */

    if (isDesktop()) {

        const savedState =
            localStorage.getItem(STORAGE_KEY);

        if (savedState === 'true') {
            setDesktopSidebar(true, false);
        }

    }


    /*
    |--------------------------------------------------------------------------
    | Desktop collapse button
    |--------------------------------------------------------------------------
    */

    if (desktopToggle) {

        desktopToggle.addEventListener(
            'click',
            () => {

                const collapsed =
                    body.classList.contains(
                        'sidebar-collapsed'
                    );

                setDesktopSidebar(!collapsed);

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Mobile sidebar
    |--------------------------------------------------------------------------
    */

    const openMobileSidebar = () => {
        body.classList.add(
            'sidebar-mobile-open'
        );
    };

    const closeMobileSidebar = () => {
        body.classList.remove(
            'sidebar-mobile-open'
        );
    };


    if (mobileToggle) {

        mobileToggle.addEventListener(
            'click',
            () => {

                if (
                    body.classList.contains(
                        'sidebar-mobile-open'
                    )
                ) {
                    closeMobileSidebar();
                } else {
                    openMobileSidebar();
                }

            }
        );

    }


    if (backdrop) {

        backdrop.addEventListener(
            'click',
            closeMobileSidebar
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Close sidebar after selecting link on mobile
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll(
            '.sidebar-link, ' +
            '.sidebar-submenu-link, ' +
            '.sidebar-generator-link'
        )
        .forEach((link) => {

            link.addEventListener(
                'click',
                () => {

                    if (!isDesktop()) {
                        closeMobileSidebar();
                    }

                }
            );

        });


    /*
    |--------------------------------------------------------------------------
    | Expand desktop sidebar when clicking a collapsed group
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'click',
        (event) => {

            const toggle =
                event.target.closest(
                    '.sidebar-group-toggle'
                );

            if (
                toggle &&
                isDesktop() &&
                body.classList.contains(
                    'sidebar-collapsed'
                )
            ) {

                setDesktopSidebar(false);

            }

        },
        true
    );


    /*
    |--------------------------------------------------------------------------
    | Handle resize
    |--------------------------------------------------------------------------
    */

    window.addEventListener(
        'resize',
        () => {

            if (isDesktop()) {

                closeMobileSidebar();

                const savedState =
                    localStorage.getItem(
                        STORAGE_KEY
                    );

                setDesktopSidebar(
                    savedState === 'true',
                    false
                );

            } else {

                body.classList.remove(
                    'sidebar-collapsed'
                );

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | ESC closes mobile sidebar
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'keydown',
        (event) => {

            if (
                event.key === 'Escape' &&
                !isDesktop()
            ) {
                closeMobileSidebar();
            }

        }
    );

});
});

