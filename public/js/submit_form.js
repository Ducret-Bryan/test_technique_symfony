document.addEventListener("DOMContentLoaded", () => {
  const formContent = document.getElementById("formContent");
  const submit_btn = document.getElementById("submit_btn");
  submit_btn.addEventListener("click", (event) => {
    event.preventDefault();
    const form = document.querySelector("form");

    if (form && formIsEmpty(form)) fetchForm(form);
  });

  function formIsEmpty(form) {
    for (let i = 0; i < form.elements.length; i++) {
      let element = form.elements[i];
      if (!element.value) {
        return false;
      }
    }
    return true;
  }

  async function fetchForm(form) {
    const formData = new FormData(form);
    const url = createUrl();

    const response = await fetch(url, {
      method: "POST",
      body: formData,
    });
    const data = await response.json();

    if (data === "true" || data === true) window.location.reload();
    else formContent.innerHTML = data;
  }

  function createUrl() {
    const target = submit_btn.dataset.target;
    const id = submit_btn.dataset.id;

    switch (target) {
      case "new":
        return target;
      case "edit":
        return id + "/edit";
      case "delete":
        return id;
    }
  }
});
