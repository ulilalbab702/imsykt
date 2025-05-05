document.addEventListener("DOMContentLoaded", function () {
  const input = document.getElementById("searchInput");
  const rows = document.querySelectorAll("table tbody tr");

  input.addEventListener("keyup", function () {
    const searchTerm = input.value.toLowerCase();

    rows.forEach((row) => {
      const text = row.textContent.toLowerCase();
      row.style.display = text.includes(searchTerm) ? "" : "none";
    });
  });
});
