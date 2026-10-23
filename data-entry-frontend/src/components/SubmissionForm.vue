<script setup>
defineProps({
    name: { type: String, default: "" },
    storeId: { type: [String, Number], default: "" },
    productId: { type: [String, Number], default: "" },
    stores: { type: Array, default: () => [] },
    products: { type: Array, default: () => [] },
    loadingStores: Boolean,
    loadingProducts: Boolean,
    saving: Boolean,
});

const emit = defineEmits([
    "update:name",
    "update:storeId",
    "update:productId",
    "store-change",
    "submit",
]);

function onStoreChange(event) {
    emit("update:storeId", event.target.value);
    emit("store-change");
}
</script>

<template>
    <form class="entry-form" @submit.prevent="emit('submit')">
        <div class="entry-field">
            <label class="entry-label" for="entry-name">
                <span class="label-icon"
                    ><i class="fa-regular fa-user"></i
                ></span>
                Nama <b>*</b>
            </label>
            <input
                id="entry-name"
                type="text"
                placeholder="Masukkan nama lengkap"
                :value="name"
                required
                @input="emit('update:name', $event.target.value)"
            />
        </div>

        <div class="entry-field">
            <label class="entry-label" for="entry-store">
                <span class="label-icon"
                    ><i class="fa-solid fa-store"></i
                ></span>
                Toko <b>*</b>
            </label>
            <select
                id="entry-store"
                :value="storeId"
                required
                :disabled="loadingStores"
                @change="onStoreChange"
            >
                <option value="" disabled>
                    {{ loadingStores ? "Memuat..." : "Pilih toko" }}
                </option>
                <option v-for="s in stores" :key="s.id" :value="s.id">
                    {{ s.name }}
                </option>
            </select>
        </div>

        <div class="entry-field">
            <label class="entry-label" for="entry-product">
                <span class="label-icon"
                    ><i class="fa-solid fa-box-open"></i
                ></span>
                Produk <b>*</b>
            </label>
            <select
                id="entry-product"
                :value="productId"
                required
                :disabled="!storeId || loadingProducts"
                @change="emit('update:productId', $event.target.value)"
            >
                <option value="" disabled>
                    {{
                        !storeId
                            ? "Pilih toko terlebih dahulu"
                            : loadingProducts
                              ? "Memuat..."
                              : "Pilih produk (sesuai tier toko)"
                    }}
                </option>
                <option v-for="p in products" :key="p.id" :value="p.id">
                    {{ p.name }}
                </option>
            </select>
        </div>

        <button class="entry-submit" type="submit" :disabled="saving">
            <i
                :class="
                    saving
                        ? 'fa-solid fa-spinner fa-spin'
                        : 'fa-solid fa-floppy-disk'
                "
            ></i>
            {{ saving ? "Menyimpan..." : "Simpan Data" }}
        </button>
    </form>
</template>
