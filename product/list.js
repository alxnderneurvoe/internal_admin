// categories.js

const categories = [
    { id: "", name: "Semua" },
    { id: "Aksesoris Perangkat", name: "Aksesoris Perangkat Jairnga" },
    { id: "Alat Peraga Edukatif", name: "Alat Peraga Edukatif" },
    { id: "Furnitur Kantor/Sekolah", name: "Furnitur Kantor/Sekolah" },
    { id: "Laptop/PC/AiO", name: "Laptop/PC/AiO" },
    { id: "Meja dan Kursi Guru", name: "Meja dan Kursi Guru" },
    { id: "Meja dan Kursi Siswa", name: "Meja dan Kursi Siswa" },
    { id: "Mesin Welding Pipe", name: "Mesin Welding Pipe" },
    { id: "Pipa dan Fitting HDPE", name: "Pipa dan Fitting HDPE" },
    { id: "Pipa dan Fitting Limbah", name: "Pipa dan Fitting Limbah" },
    { id: "Pipa dan Fitting PPR", name: "Pipa dan Fitting PPR" },
    { id: "Pipa dan Fitting PVC", name: "Pipa dan Fitting PVC" }
];

const unit = [
    { id: "", name: "Pilih Satuan" },
    { id: "Pcs", name: "Pcs" },
    { id: "Meter", name: "Meter" },
    { id: "Unit", name: "Unit" },
    { id: "Roll-50m", name: "Roll-50m" },
    { id: "Roll-100m", name: "Roll-100m" },

];

function populateCategories() {
    const selectElement = document.getElementById('category-select');
    selectElement.innerHTML = ''; // Clear existing options

    categories.forEach(category => {
        const option = document.createElement('option');
        option.value = category.id;
        option.textContent = category.name;
        
        // Check if the category is selected based on the URL query parameter
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('category') === category.id) {
            option.selected = true;
        }

        selectElement.appendChild(option);
    });
}

// Call the function to populate categories
populateCategories();

// Fungsi untuk memuat kategori ke dalam dropdown
function loadCategories() {
    const categorySelect = document.getElementById("productCategory");

    categories.forEach(category => {
        const option = document.createElement("option");
        option.value = category.id;
        option.textContent = category.name;
        categorySelect.appendChild(option);
    });
}

function loadUnit() {
    const unitSelect = document.getElementById("productUnit");

    unit.forEach(unit => {
        const option = document.createElement("option");
        option.value = unit.id;
        option.textContent = unit.name;
        unitSelect.appendChild(option);
    });
}
