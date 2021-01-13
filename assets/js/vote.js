const articles = document.querySelectorAll("article");

articles.forEach((article) => {
  const upvoteButton = article.querySelector("button");
  const numberOfUpvotes = article.querySelector("p.upvotes");

  if (upvoteButton.classList.contains("vote")) {
    upvoteButton.addEventListener("click", (e) => {
      const postID = e.target.dataset.post;
      if (postID === undefined) {
        window.location.href = "/login.php";
      } else {
        upvoteButton.classList.toggle("active");
        const data = new FormData();
        data.append("post_id", postID);
        const options = {
          method: "POST",
          body: data,
        };

        fetch("/app/posts/vote.php", options)
          .then((response) => response.json())
          .then((upvotes) => {
            numberOfUpvotes.textContent = upvotes;
          });
      }
    });
  }
});
