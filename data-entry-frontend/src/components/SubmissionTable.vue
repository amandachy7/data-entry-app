<script setup>
import { ref, computed, watch } from "vue";

const props = defineProps({
    submissions: { type: Array, default: () => [] },
});

const search = ref("");
const selectedDetail = ref(null);
const currentPage = ref(1);
const pageSize = 5;

const filteredSubmissions = computed(() => {
    const keyword = search.value.trim().toLowerCase();

    if (!keyword) return props.submissions;

    return props.submissions.filter((item) => {
        const values = [
            item.name,
            item.store?.name,
            item.store?.tier?.name,
            item.product?.name,
        ];

        return values.some((value) =>
            String(value ?? "")
                .toLowerCase()
                .includes(keyword),
        );
    });
});

const totalPages = computed(() =>
    Math.max(1, Math.ceil(filteredSubmissions.value.length / pageSize)),
);

const paginatedSubmissions = computed(() => {
    const start = (currentPage.value - 1) * pageSize;
    return filteredSubmissions.value.slice(start, start + pageSize);
});

// Setelah data baru dimuat (mis. habis simpan), kembali ke halaman 1
watch(
    () => props.submissions,
    () => {
        currentPage.value = 1;
    },
);

function formatDate(value) {
    if (!value) return "-";

    const date = new Date(value);

    if (Number.isNaN(date.getTime())) return "-";

    return new Intl.DateTimeFormat("id-ID", {
        day: "2-digit",
        month: "short",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    }).format(date);
}

function tierClass(tier) {
    const value = String(tier ?? "").toLowerCase();

    if (value.includes("tier a") || value.includes("gold")) return "tier-a";
    if (value.includes("tier b") || value.includes("silver")) return "tier-b";
    if (value.includes("tier c") || value.includes("bronze")) return "tier-c";

    return "tier-default";
}

function changePage(page) {
    currentPage.value = Math.min(Math.max(page, 1), totalPages.value);
}
</script>

<template>
    <section id="records-section" class="content-card records-card">
        <div class="section-heading records-heading">
            <div class="heading-icon icon-purple">
                <i class="fa-solid fa-clipboard-check"></i>
            </div>
            <div>
                <h2>Data Tersimpan</h2>
                <p>Daftar data yang berhasil disimpan ke database.</p>
            </div>

            <label class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input
                    v-model="search"
                    type="search"
                    placeholder="Cari nama, toko, atau produk..."
                    @input="currentPage = 1"
                />
            </label>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Toko</th>
                        <th>Produk</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(item, index) in paginatedSubmissions"
                        :key="item.id"
                    >
                        <td class="number-cell">
                            {{ (currentPage - 1) * pageSize + index + 1 }}
                        </td>
                        <td class="name-cell">{{ item.name }}</td>
                        <td>
                            <div class="store-badges">
                                <span class="store-name">{{
                                    item.store?.name ?? "-"
                                }}</span>
                                <span
                                    v-if="item.store?.tier?.name"
                                    class="tier-badge"
                                    :class="tierClass(item.store.tier.name)"
                                >
                                    {{ item.store.tier.name }}
                                </span>
                            </div>
                        </td>
                        <td>{{ item.product?.name ?? "-" }}</td>
                        <td class="date-cell">
                            {{ formatDate(item.created_at) }}
                        </td>
                        <td>
                            <button
                                class="action-button view-button"
                                title="Lihat detail"
                                aria-label="Lihat detail"
                                @click="selectedDetail = item"
                            >
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </td>
                    </tr>

                    <tr v-if="paginatedSubmissions.length === 0">
                        <td colspan="6" class="empty-state">
                            <i class="fa-regular fa-folder-open"></i>
                            <strong>{{
                                search
                                    ? "Data tidak ditemukan"
                                    : "Belum ada data"
                            }}</strong>
                            <span>
                                {{
                                    search
                                        ? "Coba kata kunci pencarian lain."
                                        : "Data yang berhasil disimpan akan muncul di sini."
                                }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="table-footer">
            <span>
                Menampilkan
                {{
                    filteredSubmissions.length
                        ? (currentPage - 1) * pageSize + 1
                        : 0
                }}
                -
                {{
                    Math.min(currentPage * pageSize, filteredSubmissions.length)
                }}
                dari {{ filteredSubmissions.length }} data
            </span>

            <div class="pagination">
                <button
                    aria-label="Halaman sebelumnya"
                    :disabled="currentPage === 1"
                    @click="changePage(currentPage - 1)"
                >
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button
                    v-for="page in totalPages"
                    :key="page"
                    :class="{ current: currentPage === page }"
                    @click="changePage(page)"
                >
                    {{ page }}
                </button>
                <button
                    aria-label="Halaman berikutnya"
                    :disabled="currentPage === totalPages"
                    @click="changePage(currentPage + 1)"
                >
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </section>

    <div
        v-if="selectedDetail"
        class="modal-backdrop"
        @click.self="selectedDetail = null"
    >
        <section
            class="detail-modal"
            role="dialog"
            aria-modal="true"
            aria-labelledby="detail-title"
        >
            <div class="modal-heading">
                <div class="heading-icon icon-blue">
                    <i class="fa-solid fa-file-lines"></i>
                </div>
                <div>
                    <h2 id="detail-title">Detail Data</h2>
                    <p>Informasi data entry yang dipilih.</p>
                </div>
                <button
                    class="modal-close"
                    aria-label="Tutup"
                    @click="selectedDetail = null"
                >
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="detail-row">
                <span>Nama</span><strong>{{ selectedDetail.name }}</strong>
            </div>
            <div class="detail-row">
                <span>Toko</span
                ><strong>{{ selectedDetail.store?.name ?? "-" }}</strong>
            </div>
            <div class="detail-row">
                <span>Tier Toko</span
                ><strong>{{ selectedDetail.store?.tier?.name ?? "-" }}</strong>
            </div>
            <div class="detail-row">
                <span>Produk</span
                ><strong>{{ selectedDetail.product?.name ?? "-" }}</strong>
            </div>
            <div class="detail-row">
                <span>Tanggal Input</span
                ><strong>{{ formatDate(selectedDetail.created_at) }}</strong>
            </div>
            <button
                class="save-button modal-done"
                @click="selectedDetail = null"
            >
                Tutup Detail
            </button>
        </section>
    </div>
</template>
