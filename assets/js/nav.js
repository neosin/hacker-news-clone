const nav = document.querySelector("nav");
const navItems = nav.querySelectorAll("li");

navItems.forEach((navItem) => {
  if (navItem.classList.contains("active")) {
    navItem.classList.remove("active");
  }
});

if (window.location.href.includes("top")) {
  navItems[1].classList.toggle("active");
} else if (window.location.href.includes("new")) {
  navItems[2].classList.toggle("active");
} else if (
  window.location.href.includes("login") ||
  window.location.href.includes("profile.php") ||
  window.location.href.includes("edit.php")
) {
  navItems[3].classList.toggle("active");
} else if (
  window.location.href.includes("index.php") ||
  window.location.href === "http://localhost:8000/"
) {
  navItems[0].classList.toggle("active");
}

const articleBox = document.querySelector("section.posts");
if (articleBox) {
  const clickables = articleBox.querySelectorAll("article");
  clickables.forEach((clickable) => {
    let viewPage = clickable.querySelector("a.view");
    viewPage = viewPage.href;
    clickable.addEventListener("click", (e) => {
      if (!e.target.matches("button.vote")) {
        window.location.href = viewPage;
      }
    });
  });
}
