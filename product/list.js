// categories.js

const Category = [
    { id: "", name: "Semua" },
    { id: "Aksesoris Perangkat Jaringan", name: "Aksesoris Perangkat Jaringan" },
    { id: "Alat Peraga Edukatif", name: "Alat Peraga Edukatif" },
    { id: "Buku Pendidikan", name: "Buku Pendidikan" },
    { id: "Furnitur Kantor/Sekolah", name: "Furnitur Kantor/Sekolah" },
    { id: "Laptop/PC/AiO", name: "Laptop/PC/AiO" },
    { id: "Laptop", name: "Laptop" },
    { id: "Meja dan Kursi Guru", name: "Meja dan Kursi Guru" },
    { id: "Meja dan Kursi Siswa", name: "Meja dan Kursi Siswa" },
    { id: "Meja dan Kursi Paud", name: "Meja dan Kursi Paud" },
    { id: "Mesin Welding Pipe", name: "Mesin Welding Pipe" },
    { id: "Pipa dan Fitting HDPE", name: "Pipa dan Fitting HDPE" },
    { id: "Pipa dan Fitting Limbah", name: "Pipa dan Fitting Limbah" },
    { id: "Pipa dan Fitting PPR", name: "Pipa dan Fitting PPR" },
    { id: "Pipa dan Fitting PVC", name: "Pipa dan Fitting PVC" }
];

const unit = [
    { id: "", name: "Pilih Satuan" },
    { id: "Batang", name: "Batang" },
    { id: "Pcs", name: "Pcs" },
    { id: "Meter", name: "Meter" },
    { id: "Unit", name: "Unit" },
    { id: "Paket", name: "Paket" },
    { id: "Roll-50m", name: "Roll-50m" },
    { id: "Roll-100m", name: "Roll-100m" },

];

function loadCategoryFilter() {
    const select = document.getElementById("category-select");
    Category.forEach(cat => {
        const option = document.createElement("option");
        option.value = cat.id;
        option.textContent = cat.name;

        // Jika kategori dari URL cocok, tandai sebagai selected
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('category') === cat.id) {
            option.selected = true;
        }

        select.appendChild(option);
    });
}

function loadCategory() {
    const CategorySelect = document.getElementById("productCategory");

    Category.forEach(Category => {
        const option = document.createElement("option");
        option.value = Category.id;
        option.textContent = Category.name;
        CategorySelect.appendChild(option);
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
