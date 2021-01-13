const forms = document.querySelectorAll("form");
if (forms.length > 0) {
  forms.forEach((form) => {
    const deleteWarning = form.querySelector(".delete");
    if (deleteWarning) {
      deleteWarning.addEventListener("click", (e) => {
        e.preventDefault();
        let question = window.confirm("Are you sure you want to delete?");
        if (question) {
          form.submit();
        }
      });
    }
  });
}
