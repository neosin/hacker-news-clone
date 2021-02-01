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
            console.log(upvotes);
          });
      }
    });
  }
});

// const commentID = target.dataset.comment;
// console.log(commentID);

const comments = document.querySelectorAll("div.comment");
console.log(comments);
comments.forEach((comment) => {
  const commentUpvoteButton = comment.querySelector(".comment-vote-button");
  const numberOfCommentUpvotes = comment.querySelector("p.comment-upvotes");

  if (commentUpvoteButton.classList.contains("comment-vote-button")) {
    commentUpvoteButton.addEventListener("click", (e) => {
      const commentID = e.target.dataset.comment;

      console.log(commentID);

      if (commentID === undefined) {
        window.location.href = "/login.php";
      } else {
        commentUpvoteButton.classList.toggle("active");
        const data = new FormData();
        data.append("comment_id", commentID);
        const options = {
          method: "POST",
          body: data,
        };
        fetch("/app/posts/commentvote.php", options)
          .then((response) => response.json())
          .then((commentUpvotes) => {
            numberOfCommentUpvotes.textContent = commentUpvotes;
            console.log(commentUpvotes);
          });
      }
    });
  }
});
