import "./bootstrap";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

// Handle shopping cart drawer closing animation
document.addEventListener("DOMContentLoaded", () => {
    const drawer = document.getElementById("drawer");

    if (drawer) {
        // Intercept all close button clicks
        const closeButtons = drawer.querySelectorAll('[command="close"]');

        closeButtons.forEach((button) => {
            button.addEventListener("click", (e) => {
                e.preventDefault();
                e.stopPropagation();

                // Add closing class to trigger animation
                drawer.classList.add("closing");

                // Wait for animation to complete before actually closing
                setTimeout(() => {
                    drawer.classList.remove("closing");
                    drawer.close();
                }, 250); // Match the animation duration
            });
        });

        // Also handle backdrop clicks
        const backdrop = drawer.querySelector(".drawer-backdrop");
        if (backdrop) {
            backdrop.addEventListener("click", (e) => {
                if (e.target === backdrop) {
                    e.preventDefault();
                    e.stopPropagation();

                    drawer.classList.add("closing");

                    setTimeout(() => {
                        drawer.classList.remove("closing");
                        drawer.close();
                    }, 250);
                }
            });
        }
    }

    // Rotating text animation
    const rotatingContainer = document.querySelector(
        ".rotating-text-container"
    );
    if (rotatingContainer) {
        const texts = rotatingContainer.querySelectorAll(".rotating-text");
        let currentIndex = 0;

        function rotateText() {
            const current = texts[currentIndex];
            const nextIndex = (currentIndex + 1) % texts.length;
            const next = texts[nextIndex];

            // Exit current text
            current.classList.add("exiting");
            current.classList.remove("active");

            // After exit animation, show next text
            setTimeout(() => {
                current.classList.remove("exiting");
                next.classList.add("active");
                currentIndex = nextIndex;
            }, 600); // Match the exit animation duration
        }

        // Show first text immediately
        if (texts.length > 0) {
            texts[0].classList.add("active");
        }

        // Rotate every 3 seconds (3000ms display + animation time)
        if (texts.length > 1) {
            setInterval(rotateText, 3600);
        }
    }
});
