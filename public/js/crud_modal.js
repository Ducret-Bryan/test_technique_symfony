document.addEventListener("DOMContentLoaded", () => {
  const backgroundModal = document.getElementById("backgroundModal");
  const modal = document.getElementById("modal");
  const formContent = document.getElementById("formContent");
  const buttons = document.querySelectorAll(".crud_btn");

  buttons.forEach((btn) => {
    btn.addEventListener("click", async (event) => {
      const crud = ["new", "edit", "delete", "cancel"];

      if (crud.includes(event.target.dataset.target)) {
        await choiceForm(event.target);
        toggleModal();
      }
    });
  });

  function toggleModal() {
    backgroundModal.classList.toggle("hidden");
    modal.classList.toggle("hidden");
  }

  async function choiceForm(button) {
    const target = button.dataset.target;
    const id = button.dataset.id;

    switch (target) {
      case "new":
        await getForm(target, target);
        break;
      case "edit":
        await getForm("edit", id + "/edit", id);
        break;
      case "delete":
        await getForm("delete", id + "/delete", id);
        break;
      case "cancel":
        formContent.innerHTML = "";
        break;
      default:
    }
  }

  async function getForm(type, url, id = null) {
    try {
      const response = await fetch(url, {
        method: "GET",
      });
      formContent.innerHTML = await response.json();
      document.getElementById("submit_btn").dataset.target = type;
      if (id) document.getElementById("submit_btn").dataset.id = id;
    } catch {}
  }
});

// Radio System
function handleChange() {
  const existVehicle = document.getElementById("existVehicle");
  existVehicle.classList.toggle("hidden");

  const newVehicle = document.getElementById("newVehicle");
  newVehicle.classList.toggle("hidden");
}
