<script setup>
import { ref, computed, watch, onMounted } from "vue";
import logo from "./assets/logo.jpg";
import StatCard from "./components/StatCard.vue";
import SubmissionForm from "./components/SubmissionForm.vue";
import SubmissionTable from "./components/SubmissionTable.vue";
import StoreModal from "./components/StoreModal.vue";
import ProductModal from "./components/ProductModal.vue";

const theme = ref("light");
const API_URL = "http://127.0.0.1:8000/api";

const name = ref("");
const selectedDetailToko = ref(null);
const selectedDetailProduk = ref(null);
const selectedStore = ref("");
const selectedProduct = ref("");

const stores = ref([]);
const products = ref([]);
const allProducts = ref([]);
const submissions = ref([]);

const activeTab = ref("dashboard");
const loadingStores = ref(false);
const loadingProducts = ref(false);
const loadingSubmissions = ref(false);
const saving = ref(false);

const showStoreModal = ref(false);
const showProductModal = ref(false);
const editingStore = ref(null);
const editingProduct = ref(null);

const errorMessage = ref("");
const successMessage = ref("");

const totalStores = computed(() => stores.value.length);
const totalProducts = computed(() => allProducts.value.length);

function applyTheme(value) {
    document.documentElement.setAttribute("data-theme", value);
}

function toggleTheme() {
    theme.value = theme.value === "dark" ? "light" : "dark";
}

function goTo(tab, elementId) {
    activeTab.value = tab;

    document.getElementById(elementId)?.scrollIntoView({
        behavior: "smooth",
        block: "start",
    });
}

async function fetchJson(url, options = {}) {
    const response = await fetch(url, {
        ...options,
        headers: {
            Accept: "application/json",
            ...(options.body ? { "Content-Type": "application/json" } : {}),
            ...options.headers,
        },
    });

    const result = await response.json().catch(() => ({}));

    if (!response.ok) {
        const validationErrors = Object.values(result.errors || {})
            .flat()
            .join(" ");

        throw new Error(
            validationErrors ||
                result.message ||
                `Permintaan gagal (${response.status}).`,
        );
    }

    return result;
}

function getList(result) {
    // Mendukung respons API berupa array langsung
    // atau object dengan properti data berisi array.
    if (Array.isArray(result)) return result;
    if (Array.isArray(result.data)) return result.data;
    return [];
}

async function loadStores() {
    loadingStores.value = true;

    try {
        const result = await fetchJson(`${API_URL}/stores`);
        stores.value = getList(result);
    } catch (error) {
        errorMessage.value = `Data toko gagal dimuat: ${error.message}`;
    } finally {
        loadingStores.value = false;
    }
}

async function loadProducts() {
    selectedProduct.value = "";
    products.value = [];
    errorMessage.value = "";

    if (!selectedStore.value) return;

    loadingProducts.value = true;

    try {
        const result = await fetchJson(
            `${API_URL}/stores/${selectedStore.value}/products`,
        );

        products.value = getList(result);
    } catch (error) {
        errorMessage.value = `Data produk gagal dimuat: ${error.message}`;
    } finally {
        loadingProducts.value = false;
    }
}

async function loadAllProducts() {
    try {
        const result = await fetchJson(`${API_URL}/products`);
        allProducts.value = getList(result);
    } catch (error) {
        errorMessage.value = `Katalog produk gagal dimuat: ${error.message}`;
    }
}

async function loadSubmissions() {
    loadingSubmissions.value = true;

    try {
        const result = await fetchJson(`${API_URL}/entries`);
        submissions.value = getList(result);
    } catch (error) {
        errorMessage.value = `Data submission gagal dimuat: ${error.message}`;
    } finally {
        loadingSubmissions.value = false;
    }
}

async function loadDashboard() {
    await loadStores();

    await Promise.all([loadSubmissions(), loadAllProducts()]);
}

async function saveSubmission() {
    errorMessage.value = "";
    successMessage.value = "";

    if (!name.value.trim() || !selectedStore.value || !selectedProduct.value) {
        errorMessage.value = "Lengkapi nama, toko, dan produk terlebih dahulu.";
        return;
    }

    saving.value = true;

    try {
        await fetchJson(`${API_URL}/entries`, {
            method: "POST",
            body: JSON.stringify({
                name: name.value.trim(),
                store_id: Number(selectedStore.value),
                product_id: Number(selectedProduct.value),
            }),
        });

        name.value = "";
        selectedStore.value = "";
        selectedProduct.value = "";
        products.value = [];

        successMessage.value = "Data berhasil disimpan ke database.";

        await loadSubmissions();
    } catch (error) {
        errorMessage.value = `Data gagal disimpan: ${error.message}`;
    } finally {
        saving.value = false;
    }
}

async function handleStoreSave(formData) {
    errorMessage.value = "";
    successMessage.value = "";
    saving.value = true;

    try {
        const isEditing = Boolean(formData.id);

        const url = isEditing
            ? `${API_URL}/stores/${formData.id}`
            : `${API_URL}/stores`;

        await fetchJson(url, {
            method: isEditing ? "PUT" : "POST",
            body: JSON.stringify(formData),
        });

        showStoreModal.value = false;
        editingStore.value = null;

        await loadDashboard();

        successMessage.value = isEditing
            ? "Data toko berhasil diperbarui."
            : "Toko berhasil ditambahkan.";
    } catch (error) {
        errorMessage.value = `Toko gagal disimpan: ${error.message}`;
    } finally {
        saving.value = false;
    }
}

async function handleProductSave(formData) {
    errorMessage.value = "";
    successMessage.value = "";
    saving.value = true;

    try {
        const isEditing = Boolean(formData.id);

        const url = isEditing
            ? `${API_URL}/products/${formData.id}`
            : `${API_URL}/products`;

        await fetchJson(url, {
            method: isEditing ? "PUT" : "POST",
            body: JSON.stringify(formData),
        });

        showProductModal.value = false;
        editingProduct.value = null;

        await loadDashboard();

        successMessage.value = isEditing
            ? "Produk berhasil diperbarui."
            : "Produk berhasil ditambahkan.";
    } catch (error) {
        errorMessage.value = `Produk gagal disimpan: ${error.message}`;
    } finally {
        saving.value = false;
    }
}
watch(theme, (value) => {
    applyTheme(value);
    try {
        localStorage.setItem("theme", value);
    } catch {}
});

onMounted(() => {
    let saved = null;
    try {
        saved = localStorage.getItem("theme");
    } catch {}

    theme.value =
        saved ||
        (window.matchMedia("(prefers-color-scheme: dark)").matches
            ? "dark"
            : "light");
    applyTheme(theme.value);
});
onMounted(loadDashboard);
</script>

<template>
    <main class="app-shell">
        <header class="topbar">
            <a
                class="brand"
                href="#dashboard"
                @click.prevent="goTo('dashboard', 'dashboard')"
            >
                <span class="brand-icon">
                    <img :src="logo" alt="Logo" class="nav-logo" />
                </span>

                <span class="brand-copy">
                    <strong>DataEntry</strong>
                    <small>Management System</small>
                </span>
            </a>

            <nav class="top-nav" aria-label="Navigasi utama">
                <button
                    :class="{ active: activeTab === 'dashboard' }"
                    @click="goTo('dashboard', 'dashboard')"
                >
                    <i class="fa-solid fa-house"></i>
                    <span>Dashboard</span>
                </button>

                <button
                    :class="{ active: activeTab === 'entry' }"
                    @click="goTo('entry', 'entry-section')"
                >
                    <i class="fa-solid fa-file-circle-plus"></i>
                    <span>Data Entry</span>
                </button>

                <button
                    :class="{ active: activeTab === 'records' }"
                    @click="goTo('records', 'records-section')"
                >
                    <i class="fa-solid fa-clipboard-list"></i>
                    <span>Data Records</span>
                </button>

                <button
                    :class="{ active: activeTab === 'stores' }"
                    @click="goTo('stores', 'stores-section')"
                >
                    <i class="fa-solid fa-store"></i>
                    <span>Store Master</span>
                </button>

                <button
                    :class="{ active: activeTab === 'products' }"
                    @click="goTo('products', 'products-section')"
                >
                    <i class="fa-solid fa-box-open"></i>
                    <span>Product Catalog</span>
                </button>
            </nav>

            <button
                type="button"
                class="theme-toggle"
                :aria-label="theme === 'dark' ? 'Mode terang' : 'Mode gelap'"
                @click="toggleTheme"
            >
                <i
                    :class="
                        theme === 'dark'
                            ? 'fa-solid fa-sun'
                            : 'fa-solid fa-moon'
                    "
                ></i>
            </button>

            <div class="profile">
                <span class="profile-avatar">A</span>
                <span class="profile-name">Admin</span>
            </div>
        </header>

        <div class="page-content">
            <section id="dashboard" class="welcome-banner">
                <div class="banner-content">
                    <span class="banner-label">DATA ENTRY WORKSPACE</span>
                    <h1>Selamat Datang!</h1>
                    <p>Kelola data toko dan produk dengan mudah dan cepat.</p>

                    <button
                        class="banner-button"
                        @click="goTo('entry', 'entry-section')"
                    >
                        Mulai Input
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>

                <div class="banner-art" aria-hidden="true">
                    <i class="fa-solid fa-laptop-file"></i>
                    <i class="fa-solid fa-circle-check art-check"></i>
                    <i class="fa-solid fa-sparkles art-sparkle"></i>
                </div>

                <span class="banner-orb orb-one"></span>
                <span class="banner-orb orb-two"></span>
            </section>

            <section class="stats-grid">
                <StatCard
                    variant="blue"
                    icon="fa-solid fa-store"
                    label="Total Toko"
                    :value="totalStores"
                    hint-icon="fa-solid fa-database"
                    hint="Data master toko"
                    watermark="fa-solid fa-shop"
                />

                <StatCard
                    variant="purple"
                    icon="fa-solid fa-box-open"
                    label="Total Produk"
                    :value="totalProducts"
                    hint-icon="fa-solid fa-tags"
                    hint="Produk terdaftar"
                    watermark="fa-solid fa-cubes"
                />

                <StatCard
                    variant="green"
                    icon="fa-solid fa-file-circle-check"
                    label="Total Data Entry"
                    :value="submissions.length"
                    hint-icon="fa-solid fa-check-circle"
                    hint="Data tersimpan"
                    watermark="fa-solid fa-chart-line"
                />
            </section>

            <div v-if="errorMessage" class="notice notice-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>{{ errorMessage }}</span>

                <button
                    type="button"
                    aria-label="Tutup pesan"
                    @click="errorMessage = ''"
                >
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div v-if="successMessage" class="notice notice-success">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ successMessage }}</span>

                <button
                    type="button"
                    aria-label="Tutup pesan"
                    @click="successMessage = ''"
                >
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- FORM DATA ENTRY -->
            <section id="entry-section" class="content-card">
                <div class="section-heading">
                    <div class="heading-icon icon-blue">
                        <i class="fa-solid fa-file-circle-plus"></i>
                    </div>

                    <div>
                        <h2>Data Entry</h2>
                        <p>Masukkan nama, toko, dan produk.</p>
                    </div>
                </div>

                <SubmissionForm
                    v-model:name="name"
                    v-model:store-id="selectedStore"
                    v-model:product-id="selectedProduct"
                    :stores="stores"
                    :products="products"
                    :loading-stores="loadingStores"
                    :loading-products="loadingProducts"
                    :saving="saving"
                    @store-change="loadProducts"
                    @submit="saveSubmission"
                />
            </section>

            <!-- DATA RECORDS -->
            <div id="records-section">
                <SubmissionTable :submissions="submissions" />
            </div>

            <!-- STORE MASTER -->
            <section id="stores-section" class="content-card">
                <div class="section-heading">
                    <div class="heading-icon icon-blue">
                        <i class="fa-solid fa-store"></i>
                    </div>

                    <div>
                        <h2>Store Master</h2>
                        <p>Kelola data toko yang terdaftar.</p>
                    </div>

                    <button
                        class="save-button"
                        @click="
                            editingStore = null;
                            showStoreModal = true;
                        "
                    >
                        <i class="fa-solid fa-plus"></i>
                        Tambah Store
                    </button>
                </div>

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Toko</th>
                                <th>Tier</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr
                                v-for="(store, index) in stores"
                                :key="store.id"
                            >
                                <td class="number-cell">{{ index + 1 }}</td>
                                <td class="name-cell">{{ store.name }}</td>
                                <td>
                                    <span class="tier-badge tier-default">{{
                                        store.tier?.name
                                    }}</span>
                                </td>

                                <td>
                                    <!-- Tombol Lihat Detail -->
                                    <button
                                        class="action-button view-button"
                                        type="button"
                                        title="Lihat detail"
                                        aria-label="Lihat detail"
                                        @click="selectedDetailToko = store"
                                    >
                                        <i class="fa-solid fa-eye"></i>
                                    </button>

                                    <!-- Tombol Edit -->
                                    <button
                                        class="action-button view-button"
                                        type="button"
                                        title="Edit toko"
                                        aria-label="Edit toko"
                                        @click="
                                            editingStore = { ...store };
                                            showStoreModal = true;
                                        "
                                    >
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                </td>
                            </tr>

                            <tr v-if="stores.length === 0">
                                <td colspan="4" class="empty-state">
                                    Belum ada data toko.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- PRODUCT CATALOG -->
            <section id="products-section" class="content-card">
                <div class="section-heading">
                    <div class="heading-icon icon-purple">
                        <i class="fa-solid fa-box-open"></i>
                    </div>

                    <div>
                        <h2>Product Catalog</h2>
                        <p>Kelola daftar produk yang terdaftar.</p>
                    </div>

                    <button
                        class="save-button"
                        @click="
                            editingProduct = null;
                            showProductModal = true;
                        "
                    >
                        <i class="fa-solid fa-plus"></i>
                        Tambah Produk
                    </button>
                </div>

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Produk</th>
                                <th>Tier Produk</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr
                                v-for="(product, index) in allProducts"
                                :key="product.id"
                            >
                                <td class="number-cell">{{ index + 1 }}</td>
                                <td class="name-cell">{{ product.name }}</td>
                                <td>
                                    <span class="tier-badge tier-default">{{
                                        product.tier?.name ?? "-"
                                    }}</span>
                                </td>

                                <td>
                                    <!-- Tombol Lihat Detail -->
                                    <button
                                        class="action-button view-button"
                                        type="button"
                                        title="Lihat detail"
                                        aria-label="Lihat detail"
                                        @click="selectedDetailProduk = product"
                                    >
                                        <i class="fa-solid fa-eye"></i>
                                    </button>

                                    <!-- Tombol Edit -->
                                    <button
                                        class="action-button view-button"
                                        type="button"
                                        title="Edit toko"
                                        aria-label="Edit toko"
                                        @click="
                                            editingProduct = { ...store };
                                            showProductModal = true;
                                        "
                                    >
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                </td>
                            </tr>

                            <tr v-if="allProducts.length === 0">
                                <td colspan="4" class="empty-state">
                                    Belum ada data produk.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <footer class="app-footer">
                <span>
                    <i class="fa-solid fa-layer-group"></i>
                    DataEntry Management System
                </span>

                <span>Powered by Laravel · Vue 3 · PostgreSQL</span>
            </footer>
        </div>
    </main>

    <!-- MODAL TAMBAH / EDIT STORE -->
    <StoreModal
        :show="showStoreModal"
        :store="editingStore"
        :saving="saving"
        @close="showStoreModal = false"
        @save="handleStoreSave"
    />

    <!-- MODAL TAMBAH / EDIT PRODUCT -->
    <ProductModal
        :show="showProductModal"
        :product="editingProduct"
        :tiers="tiers"
        :saving="saving"
        @close="showProductModal = false"
        @save="handleProductSave"
    />

    <!-- Modal Detail Toko -->
    <div
        v-if="selectedDetailToko"
        class="modal-backdrop"
        @click.self="selectedDetailToko = null"
    >
        <section
            class="detail-modal store-modal"
            role="dialog"
            aria-modal="true"
        >
            <div class="modal-heading">
                <div class="heading-icon icon-blue">
                    <i class="fa-solid fa-store"></i>
                </div>

                <div>
                    <h2>Detail Toko</h2>
                    <p>Informasi lengkap toko.</p>
                </div>

                <button
                    type="button"
                    class="modal-close"
                    aria-label="Tutup"
                    @click="selectedDetailToko = null"
                >
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="modal-form">
                <p>
                    <strong>Kode Toko:</strong>
                    {{ selectedDetailToko.store_code ?? "-" }}
                </p>
                <p>
                    <strong>Nama Toko:</strong>
                    {{ selectedDetailToko.name ?? "-" }}
                </p>
                <p>
                    <strong>Alamat:</strong>
                    {{ selectedDetailToko.address ?? "-" }}
                </p>
                <p>
                    <strong>Kota:</strong> {{ selectedDetailToko.city ?? "-" }}
                </p>
                <p>
                    <strong>Provinsi:</strong>
                    {{ selectedDetailToko.province ?? "-" }}
                </p>
                <p>
                    <strong>Tier:</strong>
                    {{ selectedDetailToko.tier?.name ?? "-" }}
                </p>

                <button
                    type="button"
                    class="save-button modal-done"
                    @click="selectedDetailToko = null"
                >
                    Tutup
                </button>
            </div>
        </section>
    </div>
    <!-- Modal Detail Produk -->
    <div
        v-if="selectedDetailProduk"
        class="modal-backdrop"
        @click.self="selectedDetailProduk = null"
    >
        <section
            class="detail-modal"
            role="dialog"
            aria-modal="true"
            aria-labelledby="product-detail-title"
        >
            <div class="modal-heading">
                <div class="heading-icon icon-blue">
                    <i class="fa-solid fa-box"></i>
                </div>

                <div>
                    <h2 id="product-detail-title">Detail Produk</h2>
                    <p>Informasi lengkap produk.</p>
                </div>

                <button
                    type="button"
                    class="modal-close"
                    aria-label="Tutup"
                    @click="selectedDetailProduk = null"
                >
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="modal-form">
                <p>
                    <strong>Kode Produk:</strong>
                    {{ selectedDetailProduk.product_code ?? "-" }}
                </p>

                <p>
                    <strong>Nama Produk:</strong>
                    {{ selectedDetailProduk.name ?? "-" }}
                </p>

                <p>
                    <strong>Kategori:</strong>
                    {{ selectedDetailProduk.category ?? "-" }}
                </p>

                <p>
                    <strong>Tier:</strong>
                    {{ selectedDetailProduk.tier?.name ?? "-" }}
                </p>

                <p>
                    <strong>Harga:</strong>
                    {{
                        selectedDetailProduk.price != null
                            ? new Intl.NumberFormat("id-ID", {
                                  style: "currency",
                                  currency: "IDR",
                                  maximumFractionDigits: 0,
                              }).format(selectedDetailProduk.price)
                            : "-"
                    }}
                </p>

                <button
                    type="button"
                    class="save-button modal-done"
                    @click="selectedDetailProduk = null"
                >
                    Tutup
                </button>
            </div>
        </section>
    </div>
</template>
