const articles = document.querySelectorAll("article.post");

articles.forEach((article) => {
    const upvoteButton = article.querySelector("button");
    const numberOfUpvotes = article.querySelector("p.upvotes");

    if (upvoteButton.classList.contains("vote", "up")) {
        upvoteButton.addEventListener("click", (e) => {
            upvoteButton.classList.toggle("active");
            const postID = e.target.dataset.post;
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
        });
    }
});
