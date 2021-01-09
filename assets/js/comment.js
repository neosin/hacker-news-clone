const replyButtons = document.querySelectorAll("button.reply");
const form = document.querySelector("form");
const label = form.querySelector("label");
replyButtons.forEach((node) => {
    node.addEventListener("click", (event) => {
        let commentId = event.target.dataset.comment;
        label.textContent = "Reply";
        if (label.textContent === "Reply") {
            label.textContent = "Comment";
        }
        console.log(commentId);
    });
});
