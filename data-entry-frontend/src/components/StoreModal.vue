<script setup>
import { ref, watch } from "vue";

const API_BASE = "https://emsifa.github.io/api-wilayah-indonesia/api";

const props = defineProps({
    show: Boolean,
    store: {
        type: Object,
        default: null,
    },
    saving: Boolean,
});

const emit = defineEmits(["close", "save"]);

const storeCode = ref("");
const storeName = ref("");
const streetAddress = ref("");
const city = ref("");
const province = ref("");
const storeTier = ref("");

// data wilayah
const provinces = ref([]);
const regencies = ref([]);
const loadingProvinces = ref(false);
const loadingRegencies = ref(false);
const regionError = ref("");

const SOURCES = [
    {
        provinces: () =>
            "https://emsifa.github.io/api-wilayah-indonesia/api/provinces.json",
        regencies: (id) =>
            `https://emsifa.github.io/api-wilayah-indonesia/api/regencies/${id}.json`,
    },
    {
        provinces: () =>
            "https://www.emsifa.com/api-wilayah-indonesia/v2/provinces.json",
        regencies: (id) =>
            `https://www.emsifa.com/api-wilayah-indonesia/v2/regencies/${id}.json`,
    },
];

let activeSource = 0; // sumber yang berhasil dipakai untuk provinsi

// samakan bentuk data antar sumber (id/code, name)
function normalize(payload) {
    const list = Array.isArray(payload) ? payload : (payload?.data ?? []);
    return list.map((x) => ({
        id: String(x.id ?? x.code),
        name: x.name,
    }));
}

async function fetchJson(url) {
    const res = await fetch(url);
    if (!res.ok) throw new Error(`HTTP ${res.status} untuk ${url}`);
    return res.json();
}

async function loadProvinces() {
    if (provinces.value.length) return;

    loadingProvinces.value = true;
    regionError.value = "";

    for (let i = 0; i < SOURCES.length; i++) {
        try {
            const data = await fetchJson(SOURCES[i].provinces());
            const list = normalize(data);
            if (!list.length) throw new Error("Data kosong");
            provinces.value = list;
            activeSource = i;
            loadingProvinces.value = false;
            return;
        } catch (e) {
            console.error(`[wilayah] sumber ${i} gagal:`, e);
        }
    }

    regionError.value =
        "Data provinsi gagal dimuat. Cek koneksi lalu coba lagi.";
    loadingProvinces.value = false;
}

async function loadRegencies(provinceName) {
    regencies.value = [];
    const prov = provinces.value.find((p) => p.name === provinceName);
    if (!prov) return;

    loadingRegencies.value = true;
    regionError.value = "";
    try {
        const data = await fetchJson(SOURCES[activeSource].regencies(prov.id));
        regencies.value = normalize(data);
    } catch (e) {
        console.error("[wilayah] kota gagal:", e);
        regionError.value = "Data kota gagal dimuat. Coba lagi.";
    } finally {
        loadingRegencies.value = false;
    }
}

// dipanggil saat user mengganti provinsi
function onProvinceChange() {
    city.value = "";
    loadRegencies(province.value);
}

watch(
    () => [props.show, props.store],
    async ([open, store]) => {
        if (!open) return;

        storeCode.value = store?.store_code ?? "";
        storeName.value = store?.name ?? "";
        streetAddress.value = store?.address ?? "";
        storeTier.value = store?.tier_id ? String(store.tier_id) : "";
        await loadProvinces();

        // mode edit: isi provinsi & kota lama, lalu muat daftar kotanya
        province.value = store?.province ?? "";
        if (province.value) {
            await loadRegencies(province.value);
        } else {
            regencies.value = [];
        }
        city.value = store?.city ?? "";
    },
    { immediate: true },
);

function submit() {
    const code = storeCode.value.trim();
    const name = storeName.value.trim();
    const address = streetAddress.value.trim();
    const cityName = city.value.trim();
    const provinceName = province.value.trim();
    const tier = storeTier.value.trim();

    if (!code || !name || !address || !cityName || !provinceName || !tier) {
        return;
    }

    emit("save", {
        id: props.store?.id ?? null,
        store_code: code,
        name,
        address,
        city: cityName,
        province: provinceName,
        tier_id: Number(tier),
    });
}
</script>

<template>
    <div v-if="show" class="modal-backdrop" @click.self="emit('close')">
        <section
            class="detail-modal store-modal"
            role="dialog"
            aria-modal="true"
            aria-labelledby="store-modal-title"
        >
            <div class="modal-heading">
                <div class="heading-icon icon-blue">
                    <i class="fa-solid fa-store"></i>
                </div>

                <div>
                    <h2 id="store-modal-title">
                        {{ store ? "Edit Toko" : "Tambah Toko" }}
                    </h2>
                    <p>Lengkapi informasi toko berikut.</p>
                </div>

                <button
                    type="button"
                    class="modal-close"
                    aria-label="Tutup"
                    @click="emit('close')"
                >
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form class="modal-form" @submit.prevent="submit">
                <label class="field">
                    <span>Kode Toko <b>*</b></span>
                    <input
                        v-model="storeCode"
                        type="text"
                        maxlength="255"
                        placeholder="Contoh: STR001"
                        required
                    />
                </label>

                <label class="field">
                    <span>Nama Toko <b>*</b></span>
                    <input
                        v-model="storeName"
                        type="text"
                        placeholder="Masukkan nama toko"
                        required
                    />
                </label>

                <label class="field">
                    <span>Alamat Jalan <b>*</b></span>
                    <textarea
                        v-model="streetAddress"
                        placeholder="Masukkan alamat lengkap toko"
                        rows="3"
                        required
                    ></textarea>
                </label>

                <div class="modal-form-grid">
                    <label class="field">
                        <span>Provinsi <b>*</b></span>
                        <select
                            v-model="province"
                            required
                            :disabled="loadingProvinces"
                            @change="onProvinceChange"
                        >
                            <option value="" disabled>
                                {{
                                    loadingProvinces
                                        ? "Memuat..."
                                        : "Pilih Provinsi"
                                }}
                            </option>
                            <option
                                v-for="p in provinces"
                                :key="p.id"
                                :value="p.name"
                            >
                                {{ p.name }}
                            </option>
                        </select>
                    </label>

                    <label class="field">
                        <span>Kota <b>*</b></span>
                        <select
                            v-model="city"
                            required
                            :disabled="!province || loadingRegencies"
                        >
                            <option value="" disabled>
                                {{
                                    loadingRegencies
                                        ? "Memuat..."
                                        : "Pilih Kota/Kabupaten"
                                }}
                            </option>
                            <option
                                v-for="r in regencies"
                                :key="r.id"
                                :value="r.name"
                            >
                                {{ r.name }}
                            </option>
                        </select>
                    </label>
                </div>

                <p v-if="regionError" class="region-error">{{ regionError }}</p>

                <label class="field">
                    <span>Tier Toko <b>*</b></span>
                    <select v-model="storeTier" required>
                        <option value="" disabled>Pilih Tier Toko</option>
                        <option value="1">Tier A</option>
                        <option value="2">Tier B</option>
                        <option value="3">Tier C</option>
                    </select>
                </label>

                <button
                    class="save-button modal-done"
                    type="submit"
                    :disabled="saving"
                >
                    <i
                        :class="
                            saving
                                ? 'fa-solid fa-spinner fa-spin'
                                : 'fa-solid fa-floppy-disk'
                        "
                    ></i>
                    {{ saving ? "Menyimpan..." : "Simpan Toko" }}
                </button>
            </form>
        </section>
    </div>
</template>
