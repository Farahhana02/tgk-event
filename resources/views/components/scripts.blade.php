<script>
/* === Sidebar open/close === */
const sidebar = document.getElementById("sidebar");
const overlay = document.getElementById("overlay");
const toggleBtn = document.getElementById("toggleSidebar");

const hero   = document.querySelector(".hero");
const footer = document.querySelector(".footer");
const social = document.querySelector(".social-bar");

/* Open */
function openSidebar() {
    sidebar.classList.add("open");
    overlay.classList.add("show");
    toggleBtn.classList.add("open");

    if (hero)   hero.classList.add("blur-active");
    if (footer) footer.classList.add("blur-active");
    if (social) social.classList.add("blur-active");
}

/* Close */
function closeSidebar() {
    sidebar.classList.remove("open");
    overlay.classList.remove("show");
    toggleBtn.classList.remove("open");

    if (hero)   hero.classList.remove("blur-active");
    if (footer) footer.classList.remove("blur-active");
    if (social) social.classList.remove("blur-active");
}

toggleBtn.addEventListener("click", () => {
    sidebar.classList.contains("open") ? closeSidebar() : openSidebar();
});

overlay.addEventListener("click", closeSidebar);

/* Dropdown */
function toggleDropdown() {
    const menu   = document.getElementById("dropdownMenu");
    const header = document.querySelector(".dropdown-header");
    const arrow  = document.querySelector(".dropdown-arrow");

    menu.classList.toggle("show");
    header.classList.toggle("open");
    arrow.classList.toggle("open");
}

const arrowBtn = document.querySelector(".dropdown-arrow");
if (arrowBtn) {
    arrowBtn.addEventListener("click", function (e) {
        e.stopPropagation();
        toggleDropdown();
    });
}

/* Back to top */
const btn = document.getElementById("backToTop");

window.addEventListener("scroll", () => {
    if (window.scrollY > 100) {
        btn.style.display = "block";
        btn.style.opacity = "1";
    } else {
        btn.style.opacity = "0";
        setTimeout(() => {
            if (window.scrollY <= 100) btn.style.display = "none";
        }, 200);
    }
});

btn.addEventListener("click", () => {
    window.scrollTo({ top: 0, behavior: "smooth" });
});
</script>
