function scrollToTricks() {
    const navbarHeight = document.getElementById('navigation-sticky').offsetHeight;
    const element = document.getElementById("tricks");
    const elementPosition = element.getBoundingClientRect().top + window.pageYOffset;

    window.scrollTo({
        top: elementPosition - navbarHeight,
        behavior: "smooth"
    });
}
