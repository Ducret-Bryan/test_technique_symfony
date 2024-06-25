document.addEventListener("DOMContentLoaded", () => {
  const filterModal = document.getElementById("filterModal");
  const backgroundModal = document.getElementById("backgroundModal");
  const form = document.querySelector('form[name="filter"]');
  const formData_init = new FormData(form);
  const tbody = document.querySelector("tbody");

  // open FilterModal
  const modal_button = document.querySelectorAll("button[data-target='filter");
  modal_button.forEach((btn) => {
    btn.addEventListener("click", () => {
      toggleFilterModal();
    });
  });

  function toggleFilterModal() {
    backgroundModal.classList.toggle("hidden");
    filterModal.classList.toggle("hidden");
  }
  //sumbit FilterModal
  const filterSubmit_btn = document.getElementById("filterSubmit_btn");
  filterSubmit_btn.addEventListener("click", (event) => {
    event.preventDefault();
    filterFetch();
  });

  //reset FilterModal
  form.addEventListener("reset", () => {
    filterFetch(true);
  });

  //Fetch FilterModal
  async function filterFetch(init = false) {
    const formData = new FormData(form);
    try {
      const response = await fetch("", {
        method: "POST",
        body: init ? formData_init : formData,
      });
      const data = await response.json();
      if (!response.ok) {
        filterModal.replaceChild(data, form);
      } else {
        tbody.innerHTML = data;
      }
      toggleFilterModal();
    } catch {}
  }
});
