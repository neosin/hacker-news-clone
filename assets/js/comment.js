// const replyButtons = document.querySelectorAll("button.reply");
// const form = document.querySelector("form");
// const label = form.querySelector("label");
// replyButtons.forEach((node) => {
//     node.addEventListener("click", (event) => {
//         let commentId = event.target.dataset.comment;
//         let input = document.createElement("input");
//         input.setAttribute("type", "hidden");
//         input.setAttribute("id", "reply_to");
//         input.setAttribute("name", "reply_to");
//         console.log(input);
//         if (node.classList.contains("active")) {
//             node.classList.remove("active");
//         } else {
//             node.classList.add("active");
//         }
//         if (node.classList.contains("active")) {
//             label.textContent = "reply";
//             node.textContent = "close";
//         } else {
//             label.textContent = "comment";
//             node.textContent = "reply";
//         }
//     });
// });
