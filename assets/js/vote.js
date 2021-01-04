// const data = new FormData();
// data.append("post_id", 1);
// data.append("last-name", "lindstedt");

// const options = {
//   method: "POST",
//   body: data,
// };

// fetch("/app/posts/test.php", options)
//   .then((response) => response.json())
//   .then((data) => {
//     console.log(data);
//   });

const articles = document.querySelectorAll("article");

articles.forEach((article) => {
  const upvoteButton = article.querySelector(".vote, .up");

  upvoteButton.addEventListener("click", (e) => {
    const postID = e.target.dataset.post;
    const data = new FormData();
    data.append("post_id", postID);
    const options = {
      method: "POST",
      body: data,
    };

    fetch("/app/posts/test.php", options)
      .then((response) => response.json())
      .then((resp) => {
        console.log(resp);
      });
  });
});
